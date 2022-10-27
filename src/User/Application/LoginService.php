<?php

namespace App\User\Application;

use App\User\Domain\UserRepositoryInterface;
use Exception;

class LoginService
{
    protected UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function execute(LoginDTO $loginDTO)
    {
        $username = $loginDTO->getUsername();
        $password = $loginDTO->getPassword();

        $user = $this->userRepositoryInterface->getByUserName($username);

        if ($user === false) {
            throw new Exception('Wrong credentials', 401);
        }

        if (!password_verify($password, $user["password_hash"])) {
            throw new Exception('Wrong credentials', 401);
        }

        return $user;
    }
}
