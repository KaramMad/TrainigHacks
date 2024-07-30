<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class,'order_products')->withPivot('quantity','price');
    }
    public function  bill():MorphOne
    {
        return $this->morphOne(Bill::class,'billable');
    }
}
