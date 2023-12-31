<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'events';
    protected $primaryKey = 'id';

    /**
     * Get the participants related to the event.
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
