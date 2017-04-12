<?php

namespace App\Http\Controllers;

use App\Like;
use App\Quote;

use Illuminate\Http\Request;

class LikesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Quote $quote)
    {
        $existLike = Like::where([
            ['user_id', '=', auth()->id() ],
            ['quote_id', '=', $quote->id ]
        ])->first();

        if($existLike) {
            $existLike->delete();
            return [
                'success' => false,
                'deleted' => true
            ];
        }
        $like = $quote->addLike(new Like([
            'user_id' => auth()->id()
        ]));

        if (!$like) {
            return [
                'success' => false
            ];
        }
        return [
            'success' => true
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $like = Like::where([
            ['user_id', '=' , auth()->id() ],
            ['quote_id', '=' , $quote->id  ]
        ])->first();
        if ($like) {
            $like->delete();
            return [
                'success' => true
            ];
        }
        return [
            'success' => false,
            'message' => 'Like does not exists'
        ];
    }
}
