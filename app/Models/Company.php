<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'tax_number',
        'signature',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
