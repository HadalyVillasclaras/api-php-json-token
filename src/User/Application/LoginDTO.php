<?php

namespace App\User\Application;

class LoginDTO 
{
    private $username;
    private $password;

    public function __construct(
        string $username,
        string $password
    )
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}