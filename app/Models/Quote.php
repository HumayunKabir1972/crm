<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'deal_id',
        'quote_number',
        'quote_date',
        'expiry_date',
        'total_amount',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
