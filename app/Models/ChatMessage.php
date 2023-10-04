<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'chat_messages';
    protected $primaryKey = 'id';

    /**
     * Get the user related to the event participation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user related to the event participation.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
}
