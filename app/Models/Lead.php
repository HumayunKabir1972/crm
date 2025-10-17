<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'job_title',
        'website',
        'status',
        'stage',
        'lead_score',
        'quality',
        'industry',
        'estimated_value',
        'budget_range',
        'expected_close_date',
        'source',
        'campaign',
        'medium',
        'referring_url',
        'assigned_to',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'description',
        'notes',
        'last_contacted_at',
        'next_followup_at',
        'converted_customer_id',
        'converted_at',
        'custom_fields',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'estimated_value' => 'decimal:2',
        'last_contacted_at' => 'datetime',
        'next_followup_at' => 'datetime',
        'converted_at' => 'datetime',
        'expected_close_date' => 'date',
        'lead_score' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lead) {
            if (empty($lead->lead_code)) {
                $lead->lead_code = 'LEAD-' . str_pad(Lead::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeHot($query)
    {
        return $query->where('quality', 'hot');
    }

    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }
}
