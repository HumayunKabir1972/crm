<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'company_name',
        'website',
        'industry',
        'annual_revenue',
        'employees',
        'customer_type',
        'status',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zip',
        'linkedin',
        'twitter',
        'facebook',
        'tax_id',
        'currency',
        'language',
        'timezone',
        'assigned_to',
        'priority',
        'lifetime_value',
        'last_contact_date',
        'next_contact_date',
        'notes',
        'custom_fields',
        'source',
        'avatar',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'annual_revenue' => 'decimal:2',
        'lifetime_value' => 'decimal:2',
        'last_contact_date' => 'date',
        'next_contact_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = 'CUST-' . str_pad(Customer::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
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

    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'emailable');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }
}
