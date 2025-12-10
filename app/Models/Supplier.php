<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'contact_person', 'email', 'phone', 'address'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
