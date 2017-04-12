<?php

namespace App\Http\Controllers;

use App\Quote;
use App\Comment;
use App\Like;

use Illuminate\Http\Request;

class QuotesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Quotes index page
        $quotes = Quote::all();
        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quotes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        $this->validate($request, [
            'text' => 'required',
            'font_family' => 'required'
        ]);

        // Create  the Quote
        auth()->user()->postQuote(new Quote($request->only('text', 'font_family')));

        //$request->session()->flash('message', 'Quote posted successfully!');
        
        return [
            'success' => true,
            'message' => 'Quote created successfully.'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        //
    }

    public function explore()
    {
        $quotes = Quote::latest()->get();
        return view('quotes.explore', compact('quotes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        if( $ $quote->likes()->delete()) {
           if( $quote->delete())
           {
               return [
                   'success' => true
               ];
           }
        }
        return [
            'success' => false
        ];
    }

    public function quotesByUser($user)
    {
        # code...
    }
}
