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

        if (
            empty($username) || $username == null
        ) {
            throw new Exception('Empty username');
        }

        if (
            empty($password) || $password == null
        ) {
            throw new Exception('Empty password');
        }

        $user = $this->userRepositoryInterface->getByUserName($username);

        if ($user === false) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid login credentials"]);
            exit;
        }

        if (!password_verify($password, $user["password_hash"])) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid login credentials"]);
            exit;
        }

        return $user;
    }
}
