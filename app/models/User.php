<?php

class User extends Eloquent implements Illuminate\Auth\UserInterface
{
    protected $fillable = ['name'];
    protected $hidden = ['password'];

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = Hash::make($password);
    }
}