<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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
