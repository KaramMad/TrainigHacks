<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function bill():MorphOne
    {
        return $this->morphOne(Bill::class, 'billable');
    }
    public function coach():BelongsTo{
        return $this->belongsTo(Coach::class);
    }public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
