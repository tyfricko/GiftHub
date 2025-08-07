<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\GiftExchangeInvitation;
use App\Models\GiftExchangeEvent;

class GiftExchangeInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $event;
    public $invitationLink;

    /**
     * Create a new message instance.
     */
    public function __construct(GiftExchangeInvitation $invitation, GiftExchangeEvent $event)
    {
        $this->invitation = $invitation;
        $this->event = $event;
        $this->invitationLink = url('/gift-exchange/invitations/' . $invitation->token);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('You are invited to a Gift Exchange Event!')
            ->markdown('emails.gift_exchange.invitation')
            ->with([
                'event' => $this->event,
                'invitation' => $this->invitation,
                'invitationLink' => $this->invitationLink,
            ]);
    }
}