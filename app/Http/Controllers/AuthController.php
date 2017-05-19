<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{

    public function loginForm()
    {
        if (\Auth::check()) {
            return \Redirect::route('home');
        }
        return \View::make('auth.form');
    }

    public function login()
    {
        $attempt = [
            'name' => \Input::get('name'),
            'password' => \Input::get('password'),
        ];
        if (\Auth::attempt($attempt)) {
            return \Redirect::intended('home');
        }
        return \Redirect::route('login');
    }

    public function logout()
    {
        \Auth::logout();
        return \Redirect::route('home');
    }

}
