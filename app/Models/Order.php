<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [''];
    public function getOrderDateAttribute($value): string
    {
        return $this->attributes['order_date'] = Carbon::parse($value)->format('h:i A d F Y');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'price');
    }

    public function bill(): MorphOne
    {
        return $this->morphOne(Bill::class, 'billable');
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status','=',$status);
        });
        $query->when($filters['paid'] ?? null, function ($query, $paid) {
            $query->whereHas('bill', function ($paidQuery) use ($paid) {
                $paidQuery->where('paid', '=', $paid);
            });
        });
    }
}
