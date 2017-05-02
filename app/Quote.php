<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'font_family'
    ];

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

    /**
     * Add Like on Quote
     *
     * @param Like $like
     * @return mixed
     */
    public function addLike(Like $like)
    {
      return $this->likes()->save($like);
    }

    /**
    * Add comment on Quote
    */
    public function addComment(Comment $comment) {
      return $this->comments()->save($comment);
    }
}
