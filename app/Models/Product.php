<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_type',
        'category_id',
        'supplier_id',
        'barcode',
        'stock',
        'low_stock_threshold',
        'stock_danger_level',
        'price',
        'unit',
        'unit_quantity',
        'expiry_date',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
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

    public function isLowStock(): bool
    {
        return $this->stock <= $this->low_stock_threshold;
    }

    public function isDangerStock(): bool
    {
        return $this->stock <= $this->stock_danger_level;
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
