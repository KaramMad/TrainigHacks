<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ColorProduct extends Model
{
    use HasFactory;
    protected $fillable=['color'];
    public function products():BelongsToMany{
        return $this->belongsToMany(Product::class,'product_color','product_id','color_id');
    }
}
