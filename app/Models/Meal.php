<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function day():BelongsTo
    {
        return $this->belongsTo(TrainingDay::class);
    }
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }
    public function favoritedby():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isfav(): bool
    {
        return auth()->user()->favorites->contains($this->id);
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search_text'] ?? false, function ($query, $search) {

            $query
                ->where('name', 'like', $search . "%");
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'meal_ingredient');
    }
}
