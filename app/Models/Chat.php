<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'chats';
    protected $primaryKey = 'id';

    /**
     * Get the event related to the event participation.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function title()
    {
        $title = 'Conversation';

        if ($this->kind == 'group') {
            $title = $this->event->title;
        } else {
            $names = [];
            $user_ids = explode(',', $this->user_ids);
            $users = User::query()->whereIn('id', $user_ids)->get();
            foreach ($users as $user) $names[] = $user->name;

            $title = implode(', ', $names);
        }

        return $title;
    }

    public function userIds()
    {
        return explode(',', $this->user_ids);
    }
}
