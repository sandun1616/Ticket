<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'Ticket #',
            'Department',
            'Fault',
            'Submitter Name',
            'Submitter Email',
            'Device Name',
            'Problem Description',
            'Status',
            'Admin Reply',
            'Created At',
            'Closed At',
            'Duration',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->department,
            $ticket->fault,
            $ticket->username,
            $ticket->email,
            $ticket->device_name,
            $ticket->problem_description,
            $ticket->status,
            $ticket->admin_reply,
            $ticket->created_at->format('Y-m-d H:i:s'),
            $ticket->closed_at ? $ticket->closed_at->format('Y-m-d H:i:s') : 'N/A',
            ($ticket->status === 'Closed' && $ticket->closed_at)
            ? $ticket->created_at->diffForHumans($ticket->closed_at, ['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE])
            : 'N/A',
        ];
    }
}
