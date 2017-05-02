<?php

namespace App\Http\Controllers;

use App\Quote;
use App\Comment;

use Illuminate\Http\Request;

class CommentsController extends Controller
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
      // validate request
      $this->validate($request, [
          'text' => 'required|min:3'
      ]);

      try {
        // create a comment by user
        $comment = auth()
                      ->user()
                      ->comments()
                      ->save(new Comment($request->only('text')));
        // save the comment for the quote
        $quote->comments()->save($comment);
      } catch (Exception $e) {
        return [
          'success' => false
        ];
      }

      return [
        'comment' => $comment,
        'success' => true
      ];
    }

 }
