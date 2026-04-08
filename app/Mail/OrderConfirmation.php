<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $items;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->items = $order->items()->with('book')->get();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - Bookshop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}