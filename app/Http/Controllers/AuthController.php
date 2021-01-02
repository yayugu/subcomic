<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{

    public function loginForm()
    {
        if (\Auth::check()) {
            return \Redirect::action('HomeController@index');
        }
        return \View::make('auth.form');
    }

    public function login()
    {
        $attempt = [
            'name' => \Request::input('name'),
            'password' => \Request::input('password'),
        ];
        if (\Auth::attempt($attempt)) {
            return \Redirect::intended();
        }
        return \Redirect::action('AuthController@login');
    }

    public function logout()
    {
        \Auth::logout();
        return \Redirect::action('HomeController@index');
    }

}
