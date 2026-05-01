<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $blade_template;
    public $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $blade_template, $attachment = null)
    {
        $this->data = $data;
        $this->blade_template = $blade_template;
        $this->attachment = $attachment;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->data['subject'] ?? 'No Subject',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: $this->blade_template,
            with: ['data' => $this->data],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $attachments = [];

        if ($this->attachment) {
            $attachments[] = Attachment::fromPath($this->attachment);
        }

        return $attachments;
    }
}
