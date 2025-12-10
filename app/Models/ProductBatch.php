<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ProductBatch extends Model
{
    use LogsActivity;
    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',
        'shelf_quantity',
        'back_quantity',
        'expiry_date',
        'manufacture_date',
        'supplier_invoice',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'manufacture_date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'batch_id');
    }

    public function shelfMovements(): HasMany
    {
        return $this->hasMany(ShelfMovement::class, 'batch_id');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isFuture() && $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->shelf_quantity + $this->back_quantity;
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }
        return now()->diffInDays($this->expiry_date, false);
    }
}
