<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function create()
    {
        return \View::make('user.create');
    }

    public function store()
    {
        $name = \Request::input('name');
        if (\User::where('name', '=', $name)->first()) {
            return \Response::make('error: user name is still used.');
        }
        $password = \Request::input('password');
        if (empty($password)) {
            return \Response::make('error: empty password.');
        }
        $password_confirm = \Request::input('password-confirm');
        if ($password !== $password_confirm) {
            return \Response::make('error password and password confirm didn\'t match');
        }
        $user = new \User;
        $user->name = $name;
        $user->setPassword($password);
        $user->save();
        return \Response::make('user create succeed.');
    }
}
