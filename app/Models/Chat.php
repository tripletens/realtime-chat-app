<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    public $table = "chat";
    public function chat_members(): HasMany
    {
        return $this->hasMany(ChatMembers::class, 'chat_id', 'id');
    }

    public $fillable = ['title','max_no','host_id', 'code'];
}

