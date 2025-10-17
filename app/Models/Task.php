<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'taskable_id',
        'taskable_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->task_code)) {
                $task->task_code = 'TASK-' . str_pad(Task::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskable()
    {
        return $this->morphTo();
    }
}
