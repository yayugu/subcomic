<?php

class AuthController extends \BaseController
{

    public function loginForm()
    {
        if (Auth::check()) {
            return Redirect::action('home');
        }
        return View::make('auth.form');
    }

    public function login()
    {
        $attempt = [
            'name' => Input::get('name'),
            'password' => Input::get('password'),
        ];
        if (Auth::attempt($attempt)) {
            return Redirect::intended('home');
        }
        return Redirect::action('login');
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::action('home');
    }

}