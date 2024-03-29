<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function create()
    {
        return View::make('user.create');
    }

    public function store()
    {
        $name = Request::get('name');
        if (User::where('name', '=', $name)->first()) {
            return Response::make('error: user name is still used.');
        }
        $password = Request::get('password');
        if (empty($password)) {
            return Response::make('error: empty password.');
        }
        $password_confirm = Request::get('password-confirm');
        if ($password !== $password_confirm) {
            return Response::make('error password and password confirm didn\'t match');
        }
        $user = new User;
        $user->name = $name;
        $user->setPassword($password);
        $user->save();
        return Response::make('user create succeed.');
    }
}
