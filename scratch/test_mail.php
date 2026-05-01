<?php

use Illuminate\Support\Facades\Mail;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Mail::raw('This is a test email from Itsroop.', function ($message) {
        $message->to('scalvance.official@gmail.com')->subject('SMTP Test');
    });
    echo "Email sent successfully!\n";
} catch (\Exception $e) {
    echo "Failed to send email: " . $e->getMessage() . "\n";
}
