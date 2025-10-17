<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_email',
        'to_email',
        'subject',
        'body',
        'sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emailable()
    {
        return $this->morphTo();
    }
}
