<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch unread emails from Microsoft 365 and create tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $client = Client::account('default');
            $client->connect();

            $folders = $client->getFolders();
            foreach ($folders as $folder) {
                $messages = $folder->query()->unseen()->get();

                foreach ($messages as $message) {
                    $this->createTicketFromEmail($message);
                    $message->setFlag(['Seen']);
                }
            }

            $this->info('Successfully fetched and processed emails.');
        } catch (\Exception $e) {
            $this->error('Error fetching emails: ' . $e->getMessage());
            Log::error('IMAP Fetch Error: ' . $e->getMessage());
        }
    }

    protected function createTicketFromEmail($message)
    {
        $subject = $message->getSubject();
        $body = $message->getTextBody() ?: $message->getHTMLBody(true);
        $from = $message->getFrom()[0];
        $email = $from->mail;
        $name = $from->personal ?: explode('@', $email)[0];

        // Generate Ticket Number
        $latest = Ticket::latest('id')->first();
        if ($latest) {
            $lastNumber = (int) str_replace('TIC-', '', $latest->ticket_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1001;
        }
        $ticketNumber = 'TIC-' . $newNumber;

        Ticket::create([
            'ticket_number' => $ticketNumber,
            'username' => $name,
            'email' => $email,
            'department' => 'ICT', // Default as discussed
            'fault' => 'IT Issue', // Default as discussed
            'device_name' => 'Email Submission: ' . $subject,
            'problem_description' => $body,
            'status' => 'Open',
        ]);

        $this->info('Created ticket ' . $ticketNumber . ' for ' . $email);
    }
}
