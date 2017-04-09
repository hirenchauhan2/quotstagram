<?php

Route::get('/', function () {
    return view('welcome');
})->name('start');

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
