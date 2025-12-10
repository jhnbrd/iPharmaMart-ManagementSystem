<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'brand_name',
        'generic_name',
        'product_type',
        'category_id',
        'supplier_id',
        'barcode',
        'shelf_stock',
        'back_stock',
        'low_stock_threshold',
        'stock_danger_level',
        'price',
        'unit',
        'unit_quantity',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function shelfMovements(): HasMany
    {
        return $this->hasMany(ShelfMovement::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->shelf_stock + $this->back_stock;
    }

    public function getExpiringSoonBatchesAttribute()
    {
        return $this->batches()
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->where(function ($query) {
                $query->where('shelf_quantity', '>', 0)
                    ->orWhere('back_quantity', '>', 0);
            })
            ->get();
    }

    public function getExpiredBatchesAttribute()
    {
        return $this->batches()
            ->where('expiry_date', '<=', now())
            ->where(function ($query) {
                $query->where('shelf_quantity', '>', 0)
                    ->orWhere('back_quantity', '>', 0);
            })
            ->get();
    }

    public function isLowStock(): bool
    {
        return $this->total_stock <= $this->low_stock_threshold;
    }

    public function isDangerStock(): bool
    {
        return $this->total_stock <= $this->stock_danger_level;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isDangerStock()) {
            return 'danger';
        } elseif ($this->isLowStock()) {
            return 'warning';
        }
        return 'good';
    }

    public function getStockStatusLabelAttribute(): string
    {
        if ($this->isDangerStock()) {
            return 'Critical';
        } elseif ($this->isLowStock()) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getStatusAttribute(): string
    {
        return $this->getStockStatusLabelAttribute();
    }
}
