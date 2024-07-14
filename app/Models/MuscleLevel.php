<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MuscleLevel extends Model
{
    use HasFactory;

    protected $guarded=[''];
    public function muscle():BelongsTo{
        return $this->belongsTo(Muscle::class);
    }
}
