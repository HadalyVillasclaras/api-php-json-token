<?php

namespace App\User\Controller;

use App\JWTCodec;
use App\RefreshTokenGateway;
use App\User\Application\LoginDTO;
use App\User\Application\LoginService;
use App\User\Application\RegisterDTO;
use App\User\Application\RegisterService;
use App\User\Infrastructure\UserRepository;

class UserController
{
    private $userId;
    private $taskId;

    public function __construct()
    {
    }

    public function register()
    {
        $userRepository = new UserRepository();

        $data = (array) json_decode(file_get_contents("php://input"), true); 

        $registerParams = [];
        $registerParams['name'] = $data['name'];
        $registerParams['email'] = $data['email'];
        $registerParams['username'] = $data['username'];
        $registerParams['password'] = $data['password'];
        $registerParams['api_key'] = $data['api_key'];

        $registerRequest = new RegisterDTO(
            $registerParams['name'],
            $registerParams['email'],
            $registerParams['username'],
            $registerParams['password'],
            $registerParams['api_key']
        );

        $registerService = new RegisterService($userRepository);
        $serviceResponse = $registerService->execute($registerRequest);

        print_r($serviceResponse);
    }

    public function login()
    {
        $userRepository = new UserRepository();

        $data = (array) json_decode(file_get_contents("php://input"), true); //datos enviados en la request
       
        $loginParams = [];
        $loginParams['username'] = $data['username'];
        $loginParams['password'] = $data['password'];

        $loginRequest = new LoginDTO(
            $loginParams['username'],
            $loginParams['password']
        );

        $loginService = new LoginService($userRepository);
        $serviceResponse = $loginService->execute($loginRequest);

        echo "logeado";

        //generar token
        $codec = new JWTCodec();

        require __DIR__ . "/../../tokens.php"; //a tokens hay que pasarle los datos del user

        $refreshTokenGateway = new RefreshTokenGateway();
        $refreshTokenGateway->create($refreshToken, $refreshTokenExpiry);
    }

}