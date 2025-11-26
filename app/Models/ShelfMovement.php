<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShelfMovement extends Model
{
    protected $fillable = [
        'product_id',
        'batch_id',
        'user_id',
        'quantity',
        'previous_shelf_stock',
        'new_shelf_stock',
        'previous_back_stock',
        'new_back_stock',
        'remarks',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
