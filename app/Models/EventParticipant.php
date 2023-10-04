<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventParticipant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'event_participants';
    protected $primaryKey = 'id';

    /**
     * Get the user related to the event participation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the event related to the event participation.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
