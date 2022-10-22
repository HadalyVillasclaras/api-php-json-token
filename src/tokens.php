<?php

//si las credenciales del user están ok, generamos el access token
$payload = [
    "sub" => $user["id"],
    "name" => $user["username"],
    "exp" => time() + 300
]; 
 
// $accessToken = base64_encode(json_encode($payload));

//usando JWT token
$accessToken = $codec->encode($payload);

$refreshTokenExpiry = time() + 432000;

$refreshToken = $codec->encode([
    "sub" => $user["id"],
    "exp" => $refreshTokenExpiry
]);

echo json_encode([
    "access_token" => $accessToken,
    "refresh_token" => $refreshToken
]);