<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{

   public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the request
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);
        // get the credentials
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');  // If user has checked remember me checkbox

        // attempt to login the user
        if (! auth()->attempt($credentials, $remember)) {
            // if it fails give the message
            return [
                'success' => false,
                'credentials'=> $credentials,
                'remember' => $remember,
                'message' => 'Username or password is incorrect'
            ];
        }
        // return the success message and redirect url
        return [
            'success' => true,
            'redirect' => url('/home')
        ];
    }

    /**
     * Logout the user and destroy curent session.
     */
    public function destroy()
    {
        // Logs out the session user
        auth()->logout();
        // redirect the user to start page of app
        return redirect()->route('start');
    }
}
