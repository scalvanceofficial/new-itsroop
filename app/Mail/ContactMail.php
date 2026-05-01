<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->markdown('emails.contact')
            ->from("scalvance.official@gmail.com", "Itsroop")
            ->subject("Itsroop - New Enquiry Received")
            ->with([
                'data' => $this->data // Pass the data array to the markdown view
            ]);
    }
}
