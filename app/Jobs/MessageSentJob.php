<?php

namespace App\Jobs;

use App\Models\ChatMessage;
use App\Models\User;
use App\Models\UserDevice;
use App\Notifications\MessageSentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class MessageSentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\ChatMessage
     */
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = $this->message;
        $chat = $message->chat;


        $tokens = [];
        $user_ids = explode(',', $chat->user_ids);
        $devices = UserDevice::query()->whereIn('user_id', $user_ids)->get();


        // send push notification
        foreach ($devices as $device) $tokens[] = $device->firebase_token;

        if (count($tokens) > 0) {
            Notification::send(null, new MessageSentNotification(
                'Nouveau message',
                $message->content,
                $tokens
            ));
        }
    }
}
