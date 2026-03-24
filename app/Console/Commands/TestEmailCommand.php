<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {--to=support@elasto.lk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('');
        $this->info('═══════════════════════════════════════════');
        $this->info('ITTICKET EMAIL CONFIGURATION TEST');
        $this->info('═══════════════════════════════════════════');
        $this->line('');

        // Display current configuration
        $this->line('Current Mail Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Mailer', config('mail.default')],
                ['Host', config('mail.mailers.smtp.host')],
                ['Port', config('mail.mailers.smtp.port')],
                ['Username', config('mail.mailers.smtp.username')],
                ['Encryption', config('mail.mailers.smtp.scheme') ?? 'None'],
                ['From Address', config('mail.from.address')],
                ['From Name', config('mail.from.name')],
            ]
        );

        $this->line('');

        // Get recipient email
        $recipient = $this->option('to');
        $this->line("Sending test email to: <fg=cyan>$recipient</>");
        $this->line('');

        try {
            // Send test email
            Mail::raw(
                "This is a test email from ITTICKET IT Ticket Management System.\n\n" .
                "Timestamp: " . now()->format('Y-m-d H:i:s') . "\n\n" .
                "If you received this email, your SMTP configuration is working correctly!",
                function ($message) use ($recipient) {
                    $message
                        ->to($recipient)
                        ->subject('ITTICKET Test Email - ' . now()->format('Y-m-d H:i:s'));
                }
            );

            $this->line('');
            $this->info('✓ SUCCESS! Email sent successfully!');
            $this->line('');
            $this->warn('→ Check your inbox at: ' . $recipient);

            return 0;

        } catch (Exception $e) {
            $this->line('');
            $this->error('✗ FAILED! Error sending email:');
            $this->error($e->getMessage());
            $this->line('');
            $this->warn('Troubleshooting Steps:');
            $this->line('1. Verify MAIL_HOST in .env (for Office365: smtp.office365.com)');
            $this->line('2. Verify MAIL_PORT in .env (for Office365: 587)');
            $this->line('3. Verify MAIL_ENCRYPTION in .env (for Office365: tls)');
            $this->line('4. Verify MAIL_USERNAME and MAIL_PASSWORD credentials');
            $this->line('5. Ensure account allows SMTP access');
            $this->line('6. Check firewall is not blocking port 587');

            return 1;
        }
    }
}
