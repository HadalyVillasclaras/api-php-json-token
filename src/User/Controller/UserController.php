<?php

namespace App\User\Controller;

use App\JWTCodec;
use App\RefreshTokenGateway;
use App\User\Application\LoginDTO;
use App\User\Application\LoginService;
use App\User\Application\RegisterDTO;
use App\User\Application\RegisterService;
use App\User\Infrastructure\UserRepository;
use Exception;

class UserController
{
    public function __construct()
    {
    }

    public function register()
    {
        $userRepository = new UserRepository();

        try {
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
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function login()
    {
        $userRepository = new UserRepository();

        try {
            $data = (array) json_decode(file_get_contents("php://input"), true);
       
            $loginParams = [];
            $loginParams['username'] = $data['username'];
            $loginParams['password'] = $data['password'];
    
            $loginRequest = new LoginDTO(
                $loginParams['username'],
                $loginParams['password']
            );
    
            $loginService = new LoginService($userRepository);
            $serviceResponse = $loginService->execute($loginRequest);
            $user = $serviceResponse;

            $this->generateToken($user);
            print json_encode(["message" => "logeado"]);

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function logout() 
    {
        $userRepository = new UserRepository();
        $refreshTokenGateway = new RefreshTokenGateway();

        $data = (array) json_decode(file_get_contents("php://input"), true);

        // check if token key exists in array
        if (!array_key_exists("token", $data)) {
            http_response_code(400);
            echo json_encode(["message" => "missing token"]);
            exit;
        }

        // check if refresh token format is valid
        $codec = new JWTCodec();

        try {
            $payload = $codec->decode($data["token"]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
            exit;
        }
        
        // Check if reshesh token exists in db
        $refreshToken = $refreshTokenGateway->getByToken($data["token"]);
        if ($refreshToken === false) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid token (not on whitelist)"]);
            exit;
        }

        // Check if user from refresh token exists
        $userId = $payload["sub"];
        $user = $userRepository->getByID($userId);
        if ($user === false) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid authentication"]);
            exit;
        }

        // Delete refresh token
        $deleteResponse = $refreshTokenGateway->delete($data["token"]);

        if ($deleteResponse === 1) {
            echo "logout";
        }
    }

    
    // TOKENS
    public function generateToken(array $user) 
    {
        $codec = new JWTCodec();

        $payload = [
            "sub" => $user["id"],
            "name" => $user["username"],
            "exp" => time() + 300
        ]; 
        
        // $accessToken = base64_encode(json_encode($payload));

        // JWT token
        $accessToken = $codec->encode($payload);

        // JWT refresh token
        $refreshTokenExpiry = time() + 432000;
        $refreshToken = $codec->encode([
            "sub" => $user["id"],
            "exp" => $refreshTokenExpiry
        ]);

        echo json_encode([
            "access_token" => $accessToken,
            "refresh_token" => $refreshToken
        ]);

        // Persist refreshToken
        $refreshTokenGateway = new RefreshTokenGateway();
        $refreshTokenGateway->create($refreshToken, $refreshTokenExpiry);
    }

    public function deleteExpiredRefreshTokens() {
        $refreshTokenGateway = new RefreshTokenGateway();
        echo $refreshTokenGateway->deleteExpired(), "\n";
    }
}
