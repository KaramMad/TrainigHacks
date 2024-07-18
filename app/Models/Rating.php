<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rating extends Model
{
    use HasFactory;
    protected $guarded=[''];

    public function rateable():MorphTo
    {
        return $this->morphTo(Rating::class,'rateable');
    }
}
