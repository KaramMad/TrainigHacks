<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected array $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments():HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('comment_id');
    }
    public function likes():MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function likesCount():int
    {
        return $this->likes()->count();
    }
    public function commentsCount(): int
    {
        return $this->comments()->count();
    }
    public function postable():MorphTo
    {
        return $this->morphTo();
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function islike(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
