<?php

namespace App\User\Application;

use App\Shared\Domain\Exceptions\EmptyFieldException;
use Exception;

class RegisterDTO 
{
    private string $name;
    private string $email;
    private string $username;
    private string $password;
    private string $apiKey;

    public function __construct(
        string $name,
        string $username,
        string $email,
        string $password,
        string $apiKey
    )
    {
        if (empty($name)) {
            throw new EmptyFieldException('name');
        }

        if (empty($username)) {
            throw new EmptyFieldException('username');
        }
        if (empty($email)) {
            throw new EmptyFieldException('email');
        }

        if (empty($password)) {
            throw new EmptyFieldException('password');
        }

        if (empty($apiKey)) {
            throw new EmptyFieldException('apiKey');
        }
        
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
