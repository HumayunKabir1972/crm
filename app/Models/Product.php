<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost',
        'sku',
        'quantity',
    ];

    public function deals()
    {
        return $this->belongsToMany(Deal::class)->withPivot('quantity', 'price');
    }
}
