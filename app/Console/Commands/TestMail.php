<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle()
    {
        $email = $this->argument('email');

        try {
            Mail::raw('This is a test email from the IT Ticket System. If you received this, your SMTP configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('SMTP Test - IT Ticket System');
            });

            $this->info("✅ Test email sent successfully to {$email}");
            $this->info("Check the inbox to verify delivery.");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->error("Check your .env file and ensure MAIL_* settings are correct.");
        }
    }
}
