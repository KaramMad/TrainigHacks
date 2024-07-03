<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function likesCount()
    {
        return $this->likes()->count();
    }
    public function commentable()
    {
        return $this->morphTo();
    }
}
