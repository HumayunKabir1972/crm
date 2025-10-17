<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'deal_code',
        'title',
        'description',
        'customer_id',
        'lead_id',
        'amount',
        'currency',
        'stage',
        'probability',
        'expected_close_date',
        'actual_close_date',
        'status',
        'lost_reason',
        'assigned_to',
        'created_by',
        'pipeline',
        'priority',
        'source',
        'campaign',
        'notes',
        'products',
        'discount',
        'tax',
        'total_amount',
        'weighted_amount',
        'days_to_close',
        'custom_fields',
    ];

    protected $casts = [
        'products' => 'array',
        'custom_fields' => 'array',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'weighted_amount' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'probability' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            if (empty($deal->deal_code)) {
                $deal->deal_code = 'DEAL-' . str_pad(Deal::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });

        static::saving(function ($deal) {
            $deal->weighted_amount = $deal->amount * ($deal->probability / 100);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }

    }