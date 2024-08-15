<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function category():BelongsToMany{
        return $this->belongsToMany(Catproduct::class,'category_product','product_id','category_id');
    }
    public function colors():BelongsToMany{
        return $this->belongsToMany(ColorProduct::class,'product_color','product_id','color_id');
    }
    public function sizes():BelongsToMany{
        return $this->belongsToMany(ProductSize::class,'product_size','product_id','size_id');

    }
    public function favoritedby():BelongsToMany{
        return $this->belongsToMany(User::class,'product_favorites')->withPivot('interactions');
    }
    public function isfav(): bool
    {
        return auth()->user()->favorite->contains($this->id);
    }
    public function scopeFilter($query,array $filters){
        $query->when($filters['search_text'] ?? false, function ($query, $search) {

            $query
                ->where('name', 'like', $search . "%");
        });
        $query->when($filters['category'] ?? false, function ($query, $category) {

            $query->whereHas('category', function ($categoryQuery) use ($category) {
                $categoryQuery->where('category_name', '=', $category)->where('parent_id',1);
            });
        });
        $query->when($filters['home_Category'] ?? false, function ($query, $category) {

            $query->whereHas('category', function ($categoryQuery) use ($category) {
                $categoryQuery->where('category_name', '=', $category)->where('parent_id',null);
            });
        });

    }
    public function orders():BelongsToMany{
        return $this->belongsToMany(Order::class,'order_products')->withPivot('quantity');
    }
}
