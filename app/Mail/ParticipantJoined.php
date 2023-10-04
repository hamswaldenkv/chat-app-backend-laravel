<?php

namespace App\Mail;

use App\Models\EventParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class ParticipantJoined extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var \App\Models\EventParticipant
     */
    protected $eventParticipant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventParticipant $eventParticipant)
    {
        $this->eventParticipant = $eventParticipant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->eventParticipant->user;
        $event = $this->eventParticipant->event;

        return $this->from('no_reply@acgtonline.com', 'My Event App')
            ->subject("{$event->title} : votre participation reçu")
            ->replyTo('no_reply@acgtonline.com')
            ->view('emails.participant.joined')
            // ->attachFromStorage('/public/example.txt')
            ->with([
                'eventTitle' => $event->title,
                'pageTitle' => 'Votre participation à ' . $event->title
            ]);
    }
}
