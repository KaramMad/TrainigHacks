<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catproduct extends Model
{
    use HasFactory;
    protected
     $guarded=[''];

    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class,'category_product','category_id','product_id');
    }
    public function subCategories():HasMany{
        return $this->hasMany(Catproduct::class,'parent_id');
    }
    public function parent():BelongsTo{
        return $this->belongsTo(Product::class,'parent_id');
    }

}
