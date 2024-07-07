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
        $metafield = json_decode($message->metafield);
        $chat = $message->chat;
        $user = $message->user;


        $tokens = [];
        $user_ids = [];
        foreach (explode(',', $chat->user_ids) as $key) {
            if ($key != $user->id) $user_ids[]  = $key;
        }
        $devices = UserDevice::query()->whereIn('user_id', $user_ids)->get();
        foreach ($devices as $device) $tokens[] = $device->firebase_token;


        // send push notification
        if (count($tokens) > 0) {
            Notification::send(null, new MessageSentNotification(
                $tokens,
                $chat->chat_id,
                'Nouveau message de ' . $user->name,
                $message->content,
                $metafield->photo_url,
            ));
        }
    }
}
