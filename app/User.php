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
        'name', 'email', 'username', 'password',
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
     * User can like many quotes
     *
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Saves quote to Database
     * @param  App\Quote  $quote 
     * @return void
     */
    public function postQuote(Quote $quote)
    {
       $this->quotes()->save($quote);
    }

    public function likesQuote(Quote $quote)
    {
        $likes = Like::where([
                    [ 'user_id'  , '=' , $this->id ],
                    [ 'quote_id' , '=' , $quote->id ]
                ])->first();

        if (!$likes) {
            return false;
        }
        return true;
    }
}
