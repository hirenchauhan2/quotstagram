<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Mass assignable value for a modek
     * @var string[]
     */
    protected $fillable = [ 'text', 'user_id', 'quote_id', 'author_liked' ];

    /*
    *  Get the Quote on which this comment  has been made
    */
    public function quote()
    {
      return $this->belongsTo(Quote::class);
    }

    /**
     * Get the author of the comment
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
