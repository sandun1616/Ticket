<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Mail\TicketCreated;
use App\Mail\TicketStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    private $departments = [
        'Sales',
        'Production',
        'Production - Finishing',
        'Production - Engineering',
        'Business Compliance - Quality',
        'Business Compliance - Audit',
        'Business Compliance - ISO',
        'R & D',
        'Strategy',
        'Finance',
        'HR - Human Resource',
        'HR - Fire',
        'Security Premises',
        'ICT',
        'Supply Chain - FG Stores',
        'Supply Chain - RM Stores',
        'Supply Chain - Import & Export',
        'Supply Chain - Procurement'
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $this->applyFilters(Ticket::query(), $request);

        if ($user->isAdmin()) {
            $tickets = $query->with('user')->latest()->get();
        } else {
            $tickets = $query->where('user_id', $user->id)->latest()->get();
        }

        $departments = $this->departments;
        return view('tickets.index', compact('tickets', 'departments'));
    }

    public function create()
    {
        $departments = $this->departments;
        return view('tickets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'department' => ['required', Rule::in($this->departments)],
            'fault' => ['required', Rule::in(['IT Issue', 'ERP Issue'])],
            'device_name' => 'required|string|max:255',
            'problem_description' => 'required|string',
        ]);

        // Generate Ticket Number
        $latest = Ticket::latest('id')->first();
        if ($latest) {
            $lastNumber = (int) str_replace('TIC-', '', $latest->ticket_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1001;
        }

        $ticketNumber = 'TIC-' . $newNumber;

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'user_id' => Auth::id(), // Still link if admin is logged in, but not required
            'department' => $validated['department'],
            'fault' => $validated['fault'],
            'device_name' => $validated['device_name'],
            'problem_description' => $validated['problem_description'],
            'status' => 'Open',
        ]);

        // Send Email to Admin(s)
        try {
            $admins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_HEAD_ADMIN, User::ROLE_SUPER_ADMIN])->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new TicketCreated($ticket));
            }
        } catch (\Exception $e) {
            // Log error but continue
        }

        return redirect()->route('tickets.track.form')->with('success', 'Ticket created successfully! Your Ticket ID is: ' . $ticketNumber . '. Please keep this ID to track progress.');
    }

    public function showTrackForm()
    {
        return view('tickets.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string|exists:tickets,ticket_number',
        ], [
            'ticket_number.exists' => 'No ticket found with this number.'
        ]);

        $ticket = Ticket::where('ticket_number', $request->ticket_number)->first();

        return view('tickets.track', compact('ticket'));
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Admin only check for detailed management view
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Closed',
            'admin_reply' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;

        // Handle closed_at timestamp
        if ($validated['status'] === 'Closed' && $oldStatus !== 'Closed') {
            $validated['closed_at'] = now();
        } elseif ($validated['status'] !== 'Closed') {
            $validated['closed_at'] = null;
        }

        $ticket->update($validated);

        // Send Email to Guest User
        try {
            if ($ticket->email && ($oldStatus !== $ticket->status || $request->filled('admin_reply'))) {
                Mail::to($ticket->email)->send(new TicketStatusUpdated($ticket));
            }
        } catch (\Exception $e) {
            // Log error
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully!');
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $query = $this->applyFilters(Ticket::query(), $request);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $tickets = $query->latest()->get();

        return Excel::download(new TicketExport($tickets), 'tickets_report_' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $query = $this->applyFilters(Ticket::query(), $request);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $tickets = $query->latest()->get();

        $pdf = Pdf::loadView('reports.tickets_pdf', compact('tickets'));
        return $pdf->download('tickets_report_' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('fault')) {
            $query->where('fault', $request->fault);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('device_name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }
}
