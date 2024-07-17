<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public $timestamps = false;


    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['category_id'] ?? false, function ($query, $category) {

            $query->withWhereHas('categories', function ($categoryQuery) use ($category) {
                $categoryQuery->where('categories.id', '=', $category);
            });
        });
    }
    public function muscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class, 'muscle_exercise')->withPivot('exercise_count', 'total_time', 'total_calories');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'exercise_category');
    }
    public function focusAreas(): BelongsToMany
    {
        return $this->belongsToMany(FocusArea::class, 'exercise_focus_area');
    }
    public function exerciseTypes(): BelongsTo{
        return $this->belongsTo(ExerciseType::class);
    }

    public function days(): BelongsToMany{
        return $this->belongsToMany(TrainingDay::class,'training_day_exercises');
    }
    public function coachPlan(): BelongsToMany{
        return $this->belongsToMany(coachPlan::class,'exercise_plan','exercise_id','plan_id');
    }
}
