<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get All the Quotes by the User
     */
    public function quotes()
    {
      return $this->hasMany(Quote::class);
    }

    /**
     * Get All Likes by User
     */
    public function likes()
    {
      return $this->hasMany(Like::class);
    }

    /**
     * Get all Comments
     */
    public function comments()
    {
      return $this->hasMany(Comment::class);
    }
}
