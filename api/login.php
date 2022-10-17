<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); //method not allowed
    header("Allow: POST");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if (!array_key_exists("username", $data) ||
    !array_key_exists("password", $data)) {

        http_response_code(400);
        echo json_encode(["message" => "Please, introduce login credentials"]);
        exit;
}

//Authenticate
$database =  new Database($_ENV["DB_HOST"], 
                        $_ENV["DB_NAME"], 
                        $_ENV["DB_USER"], 
                        $_ENV["DB_PASS"]);

$userGateway = new UserGateway($database);

$user = $userGateway->getByUsername($data["username"]);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

if (!password_verify($data["password"], $user["password_hash"])) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

//Generate access token
$payload = [
    "sub" => $user["id"],
    "name" => $user["username"]
]; 

// $accessToken = base64_encode(json_encode($payload));

//JWT token
$codec = new JWTCodec($_ENV["SECRET_KEY"]);
$accessToken = $codec->encode($payload);

echo json_encode([
    "access_token" => $accessToken
]);
