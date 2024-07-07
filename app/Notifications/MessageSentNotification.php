<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class MessageSentNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    protected $chat_id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var array
     */
    protected $tokens;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $tokens, string $chat_id, string $title, string $message, string $image = null)
    {
        $this->tokens = $tokens;
        $this->chat_id = $chat_id;
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage())
            ->withTitle($this->title)
            ->withBody($this->message)
            ->withImage($this->image)
            ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
            ->withSound('default')
            // ->withClickAction('https://www.google.com')
            ->withPriority('high')
            ->withAdditionalData([
                'color'     => '#037ef3',
                'badge'     => 0,
                'chatId'    => $this->chat_id,
                'eventName' => 'onIncomingMessage',
                'eventData' => ['chat_id' => $this->chat_id, 'thread_id' => $this->chat_id],
            ])
            ->asNotification($this->tokens);
    }
}
