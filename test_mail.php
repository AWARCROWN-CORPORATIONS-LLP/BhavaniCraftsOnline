<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email to verify SMTP configuration.', function ($message) {
        $message->to('info-services@bhavanicrafts.com')
                ->subject('SMTP Verification Test');
    });
    echo "Success: Email sent successfully!\n";
} catch (\Exception $e) {
    echo "Error: Failed to send email.\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
