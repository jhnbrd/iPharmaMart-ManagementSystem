<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PwdTransaction extends Model
{
    protected $fillable = [
        'sale_id',
        'pwd_id_number',
        'pwd_name',
        'pwd_birthdate',
        'disability_type',
        'original_amount',
        'discount_amount',
        'final_amount',
        'discount_percentage',
        'items_purchased',
    ];

    protected $casts = [
        'pwd_birthdate' => 'date',
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
