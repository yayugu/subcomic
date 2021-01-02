<?php

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Eloquent implements
    AuthenticatableContract,
    AuthorizableContract
{
    protected $fillable = ['name'];
    protected $hidden = ['password'];

    use Authenticatable, Authorizable, hasFactory;

    public function setPassword($password)
    {
        $this->password = Hash::make($password);
    }
}
