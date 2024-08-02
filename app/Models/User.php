<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function trainingDays()
    {
        return $this->belongsToMany(TrainingDay::class, 'user_training_days', 'user_id', 'training_days_id');
    }
    public function favorites()
    {
        return $this->belongsToMany(Meal::class, 'favorites')->withTimestamps();
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function postsWithBio()
    {
        return $this->posts()->with('user', 'postable');
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users');
    }
    public function favorite():BelongsToMany{
        return $this->belongsToMany(Product::class,'product_favorites');
    }
    public function orders():HasMany{
        return $this->hasMany(Order::class);
    }
    public function subscribedCoaches():BelongsToMany
    {
        return $this->belongsToMany(Coach::class,'subscriptions')->withPivot('status','start_date','end_date');
    }
}
