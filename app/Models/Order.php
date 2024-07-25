<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function users():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class,'order_products');
    }
}
