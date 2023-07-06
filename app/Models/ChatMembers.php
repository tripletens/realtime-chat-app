<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMembers extends Model
{
    use HasFactory;

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'id', 'chat_id');
    }

    public $fillable = ['user_id', 'chat_code', 'chat_id', 'status'];
}
