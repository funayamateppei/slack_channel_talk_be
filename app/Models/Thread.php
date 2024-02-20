<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        "chat_id",
        "tenant_name",
        "user_name",
        "content",
        "ts",
    ];
}
