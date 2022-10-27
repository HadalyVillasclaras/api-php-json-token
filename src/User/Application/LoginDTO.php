<?php

namespace App\User\Application;

use App\Shared\Domain\Exceptions\EmptyFieldException;

class LoginDTO 
{
    private string $username;
    private string $password;

    public function __construct(
        string $username,
        string $password
    )
    {
        if (empty($username)) {
            throw new EmptyFieldException('username');
        }

        if (empty($password)) {
            throw new EmptyFieldException('password');
        }

        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
