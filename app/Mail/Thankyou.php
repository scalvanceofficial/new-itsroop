<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class Thankyou extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;  // Store the enquiry data

    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function build()
    {
        return $this->markdown('emails.thankyou')
            ->from("scalvance.official@gmail.com", "Itsroop")
            ->subject("Itsroop - Thank You for Contacting Us")
            ->with([
                'enquiry' => $this->enquiry  // Pass data to the email view
            ]);
    }
}
