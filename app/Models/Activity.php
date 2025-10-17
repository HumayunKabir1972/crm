<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'due_date',
        'status',
        'activityable_id',
        'activityable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityable()
    {
        return $this->morphTo();
    }
}
