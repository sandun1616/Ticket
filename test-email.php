#!/usr/bin/env php
<?php

// Test Email Configuration for ITTICKET
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

use Illuminate\Support\Facades\Mail;

$kernel->bootstrap();

echo "Testing Email Configuration\n";
echo "===========================\n\n";

// Get config
$config = config('mail');
echo "SMTP Configuration:\n";
echo "- Host: " . config('mail.mailers.smtp.host') . "\n";
echo "- Port: " . config('mail.mailers.smtp.port') . "\n";
echo "- Username: " . config('mail.mailers.smtp.username') . "\n";
echo "- Encryption: " . config('mail.mailers.smtp.scheme') . "\n";
echo "- From: " . config('mail.from.address') . "\n\n";

echo "Attempting to send test email...\n";

try {
    Mail::raw('Test email body from ITTICKET application', function($msg) {
        $msg->to('support@elasto.lk')
            ->subject('ITTICKET - Test Email at ' . now());
    });
    
    echo "✓ Email sent successfully!\n";
} catch (\Exception $e) {
    echo "✗ Error sending email:\n";
    echo $e->getMessage() . "\n";
}
