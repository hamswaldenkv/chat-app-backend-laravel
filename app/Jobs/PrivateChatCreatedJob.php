<?php

namespace App\Jobs;

use App\Mail\PrivateChatCreated;
use App\Models\Chat;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PrivateChatCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    private $userFrom;

    /**
     * @var \App\Models\User
     */
    private $userTo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $userFrom, User $userTo)
    {
        $this->userFrom = $userFrom;
        $this->userTo = $userTo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userFrom = $this->userFrom;
        $userTo = $this->userTo;


        // send push notification
        $tokens = [];
        $devices = UserDevice::query()->where('user_id', $userTo->id)->get();
        foreach ($devices as $device) $tokens[] = $device->firebase_token;

        if (count($tokens) > 0) {
            Notification::send(null, new MessageSentNotification(
                'Discussion privée créée',
                $userFrom->name . '  a crée la discussion',
                $tokens
            ));
        }

        // send emails
        Mail::to([$userFrom, $userTo])
            ->send(new PrivateChatCreated($userFrom, $userTo));
    }
}
