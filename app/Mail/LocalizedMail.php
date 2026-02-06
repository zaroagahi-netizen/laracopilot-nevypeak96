<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class LocalizedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $locale;
    public $viewName;
    public $data;

    public function __construct(string $locale, string $viewName, array $data = [])
    {
        $this->locale = $locale;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        App::setLocale($this->locale);
        
        return new Envelope(
            subject: $this->data['subject'] ?? __('Notification from ZARO'),
        );
    }

    public function content(): Content
    {
        App::setLocale($this->locale);
        
        return new Content(
            view: $this->viewName,
            with: $this->data,
        );
    }
}