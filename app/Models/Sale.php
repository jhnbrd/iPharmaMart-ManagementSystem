<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'user_id',
        'total',
        'payment_method',
        'reference_number',
        'card_bank_name',
        'card_holder_name',
        'card_last_four',
        'paid_amount',
        'change_amount',
        'is_voided',
        'voided_at',
        'voided_by',
        'void_reason'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'is_voided' => 'boolean',
        'voided_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function seniorCitizenTransaction()
    {
        return $this->hasOne(SeniorCitizenTransaction::class);
    }

    public function pwdTransaction()
    {
        return $this->hasOne(PwdTransaction::class);
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}
