<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public $timestamps = false;


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category_id'] ?? false, function ($query, $category) {

            $query->withWhereHas('categories', function ($categoryQuery) use ($category) {
                $categoryQuery->where('categories.id', '=', $category);
            });
        });
    }
    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_exercise')->withPivot('exercise_count', 'total_time', 'total_calories');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'exercise_category');
    }
    public function focusAreas()
    {
        return $this->belongsToMany(FocusArea::class, 'exercise_focus_area');
    }
}
