<?php

declare(strict_types=1);

use App\JWTCodec;
use App\RefreshTokenGateway;
use App\User\Infrastructure\UserRepository;

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); 
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if (!array_key_exists("token", $data)) {
        http_response_code(400);
        echo json_encode(["message" => "missing token"]);
        exit;
}


$codec = new JWTCodec();

try {
    $payload = $codec->decode($data["token"]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["message" => $e->getMessage()]);
    exit;
}

$userId = $payload["sub"];

$refreshTokenGateway = new RefreshTokenGateway();

$refreshToken = $refreshTokenGateway->getByToken($data["token"]);

if ($refreshToken === false) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid token (not on whitelist)"]);
    exit;
}

$userRepository = new UserRepository();
$user = $userRepository->getByID($userId);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

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

$refreshTokenGateway->delete($data["token"]);
$refreshTokenGateway->create($refreshToken, $refreshTokenExpiry);

