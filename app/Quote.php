<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /**
     * Get the Author of the quote
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

	/**
     * Get comments of the quote
     */
    public function comments()
    {
      return $this->hasMany(Comment::class);
    }

    /**
     * Get Likes of the quote
     */
    public function likes()
    {
      return $this->hasMany(Like::class);
    }
}
