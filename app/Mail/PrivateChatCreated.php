<?php

namespace App\Mail;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrivateChatCreated extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var \App\Models\User
     */
    private $userFrom;

    /**
     * @var \App\Models\User
     */
    private $userTo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $userFrom, User $userTo)
    {
        $this->userFrom = $userFrom;
        $this->userTo = $userTo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userFrom = $this->userFrom;
        $userTo = $this->userTo;

        return $this->from('no_reply@acgtonline.com', 'My Event App')
            ->subject("Nouvelle discussion créée")
            ->replyTo('no_reply@acgtonline.com')
            ->view('emails.chat.privatecreated')
            ->with([
                'senderName' => $userFrom->name,
                'recipientName' => $userTo->name,
            ]);
    }
}
