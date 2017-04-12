<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'quote_id'];

    /**
     * Get the User who liked the Quote
     * @return User User who liked the quote
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Quote
     * @return Quote Get the parent Quote on which is liked by user
     */
    public function quote()
    {
      return $this->belongsTo(Quote::class);
    }
}
