<?php

namespace App\User\Application;

use App\User\Domain\User;
use App\User\Domain\UserRepositoryInterface;

class RegisterService
{
    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function execute(RegisterDTO $registerDTO)
    {
        $user = new User(
            $registerDTO->getName(),
            $registerDTO->getEmail(),
            $registerDTO->getUsername(),
            $registerDTO->getPassword(),
            $registerDTO->getApiKey()
        );

        $this->userRepositoryInterface->register($user);

        return $user;
    }
}