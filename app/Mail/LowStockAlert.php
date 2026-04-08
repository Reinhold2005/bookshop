<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $lowStockBooks;
    public $outOfStockBooks;

    public function __construct($lowStockBooks, $outOfStockBooks)
    {
        $this->lowStockBooks = $lowStockBooks;
        $this->outOfStockBooks = $outOfStockBooks;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Low Stock Alert - Bookshop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
        );
    }
}