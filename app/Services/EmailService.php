<?php

namespace App\Services;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send an email using a dynamic template and data.
     *
     * @param string $to Recipient email address
     * @param string $bladeTemplate Blade view template path (e.g., 'emails.order_confirmation')
     * @param array $data Custom data to pass to the email template
     * @param string|null $attachment File path for an optional attachment
     */
    public static function sendEmail($to, $blade_template, $data, $attachment = null)
    {
        // Send to customer
        Mail::to($to)->send(new SendMail($data, $blade_template, $attachment));

        // Send copy to admin
        $adminEmail = config('mail.from.address'); // Using the from address as admin email for now
        Mail::to($adminEmail)->send(new SendMail($data, $blade_template, $attachment));
    }
}
