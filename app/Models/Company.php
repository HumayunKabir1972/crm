<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'website',
        'industry',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->company_code)) {
                $company->company_code = 'COMP-' . str_pad(Company::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
