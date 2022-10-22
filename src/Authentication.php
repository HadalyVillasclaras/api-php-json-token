<?php
/*
 * Authentication methods:
 * - API key
 * - Access token
 * - JWT token
*/

namespace App;

use App\Exceptions\InvalidSignatureException;
use App\Exceptions\TokenExpiredException;
use Exception;

class Authentication
{
    private UserGateway $userGateway;
    private JWTCodec $codec;

    private int $userId;

    public function __construct(/*private*/ UserGateway $userGateway,
                                            JWTCodec $codec)
    {
        $this->userGateway = $userGateway;
        $this->codec = $codec;
    }
    
    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
            http_response_code(400);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }

        $apiKey = $_SERVER["HTTP_X_API_KEY"];

        $user = $this->userGateway->getByAPIKey($apiKey);

        if ($user === false) {
            http_response_code(401); 
            echo json_encode(["message" => "invalid API key"]);
            return false;
        }

        $this->userId = $user["id"];

        return true;
    }

    public function authenticateAccessToken(): bool
    {
        //check if authentication matches the scheme
        if (!preg_match("/^Bearer\s+(.*)$/", $_SERVER["HTTP_AUTHORIZATION"], $matches)) {
            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

        $decodeMatch = base64_decode($matches[1], true);

        if ($decodeMatch === false) {
            http_response_code(400);
            echo json_encode(["message" => "invalid authorization header"]);
            return false;
        }

        $data = json_decode($decodeMatch, true);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(["message" => "invalid JSON"]);
            return false;
        }

        $this->userId = $data["id"];

        return true;
    }

    public function authenticateJwtToken(): bool
    {
        if (!preg_match("/^Bearer\s+(.*)$/", $_SERVER["HTTP_AUTHORIZATION"], $matches)) {
            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

        try {
            $data = $this->codec->decode($matches[1]);

        } catch (InvalidSignatureException $e) {

            http_response_code(401);
            echo json_encode(["message" => "invalid signature"]);
            return false;

        } catch (TokenExpiredException $e) {

            http_response_code(401);
            echo json_encode(["message" => "token has expired"]);
            return false;
        
        } catch (Exception $e) {

            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
            return false;
        }

        $this->userId = $data["sub"];

        return true;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}