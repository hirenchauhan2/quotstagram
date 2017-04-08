<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\Registration;

class RegistrationController extends Controller
{
	// Only guest user can register.
	// Already registered user should not have access
	// to this controller.
	function __construct()
	{
		$this->middleware('guest');
	}

    public function create()
    {
    	return view('auth.register');
    }

    public function store(Registration $request)
    {
    	// Create and save the user.
    	$user = User::create($request->only([
    		'name',
    		'email',
    		'username',
    		'password'
    	]));

    	// Sign them in.
    	auth()->login($user);

    	return [
            'success' => true,
            'redirect' => url('/home')
        ];
    }
}
