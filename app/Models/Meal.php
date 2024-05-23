<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    //protected $fillable = ['name', 'calories', 'protein', 'image', 'description', 'day_id', 'coach_id'];
    protected $guarded = ['id'];
    public function day()
    {
        return $this->belongsTo(TrainingDay::class);
    }
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
    public function mealType()
    {
        return $this->belongsTo(MealType::class);
    }
    public function setMealTypeIdAttribute($value)
    {
        $this->attributes['meal_type_id'] = $value;

        $mealType = MealType::find($value);
        if ($mealType) {
            $this->attributes['type'] = $mealType->name;
        }
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
