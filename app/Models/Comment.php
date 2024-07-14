<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected array $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function replies():HasMany
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
    public function likes():MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function likesCount(): int
    {
        return $this->likes()->count();
    }
    public function commentable():MorphTo
    {
        return $this->morphTo();
    }
}
