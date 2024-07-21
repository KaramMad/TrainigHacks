<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSize extends Model
{
    use HasFactory;
    protected $table = 'size_products';
    protected $fillable = ['size'];
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size', 'product_id', 'size_id');
    }
}
