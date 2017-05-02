<?php

Route::get('/', function () {
    return view('welcome');
})->name('home');

Auth::routes();

// Route::get('home', 'HomeController@index')->name('home');
Route::get('/quotes/create', 'QuotesController@create');
Route::post('/quotes', 'QuotesController@store')->name('quotes');
Route::delete('/quotes/{quote}', 'QuotesController@destroy');
Route::post('/quotes/{quote}/likes', 'LikesController@store');
Route::delete('/quotes/{quote}/likes', 'LikesController@destroy');
Route::post('/quotes/{quote}/comments', 'CommentsController@store');
Route::get('/explore', 'QuotesController@explore')->name('explore');
