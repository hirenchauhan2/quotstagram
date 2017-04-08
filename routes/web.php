<?php

Route::get('/', function () {
    return view('welcome');
})->name('start');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/register', 'RegistrationController@create')->name('register');
// Route::post('/register', 'RegistrationController@store')->name('register');

// Route::get('/login', 'SessionsController@create')->name('login');
// Route::post('/login', 'SessionsController@store')->name('login');

// Route::post('/logout', 'SessionsController@destroy')->name('logout');
