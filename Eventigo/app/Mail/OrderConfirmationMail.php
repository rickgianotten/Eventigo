<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Actions\Checkout\CreateTicketsAction;
use Illuminate\Mail\Mailables\Attachment;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'noreply@inventigo.nl',
            subject: "Ready for {$this->order->event}? Here are your tickets",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.OrderConfirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(CreateTicketsAction $action): array
    {
        $qrCodes = $action->handle($this->order);
        $attachments = [];

        foreach ($qrCodes as $index => $qrCode){
            $ticketNumber = $index + 1;
            $attachments[] =  Attachment::fromData(fn() => $qrCode, "ticket-{$ticketNumber}.png");
        }
        
        return $attachments;
    }
}
