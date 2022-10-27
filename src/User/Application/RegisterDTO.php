<?php

namespace App\User\Application;

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
