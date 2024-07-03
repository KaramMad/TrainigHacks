<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function day()
    {
        return $this->belongsTo(TrainingDay::class);
    }
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
    public function favoritedby()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isfav(): bool
    {
        return auth()->user()->favorites->contains($this->id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search_text'] ?? false, function ($query, $search) {

            $query
                ->where('name', 'like', $search . "%");
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'meal_ingredient');
    }
}
