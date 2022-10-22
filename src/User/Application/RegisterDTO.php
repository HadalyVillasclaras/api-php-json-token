<?php

namespace App\User\Application;

class RegisterDTO 
{
    private $name;
    private $email;
    private $username;
    private $password;
    private $apiKey;

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

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}