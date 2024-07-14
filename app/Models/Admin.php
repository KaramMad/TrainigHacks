<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    public function posts():MorphMany
    {
        return $this->morphMany(Post::class, 'postable');
    }

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes():MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
