<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function category():BelongsTo{
        return $this->belongsTo(Catproduct::class);
    }
    public function colors():BelongsToMany{
        return $this->belongsToMany(ColorProduct::class,'product_color','product_id','color_id');
    }
    public function sizes():BelongsToMany{
        return $this->belongsToMany(ProductSize::class,'product_size','product_id','size_id');

    }
}
