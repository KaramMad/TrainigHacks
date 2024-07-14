<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Muscle extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public $timestamps = false;
    public function exercises():BelongsToMany
    {
        return $this->belongsToMany(Exercise::class,'muscle_exercise')->withPivot('exercise_count','total_time','total_calories');
    }
    public function muscleLevels():HasMany{
        return $this->hasMany(MuscleLevel::class);
    }

}
