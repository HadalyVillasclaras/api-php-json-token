<?php

declare(strict_types=1);

ini_set("display_errors", "On");

// Autoload, Dotenv, error handlers
require __DIR__ . "/bootstrap.php";

// Routing
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode("/", $path);

$resource = $parts[3] ?? null;
$id = $parts[4] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

if ($resource != "tasks") {
    //header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found"); // == 'HTTP/1.1 404 Not Found'
    http_response_code(404);
    exit;
}

// $routing = new Routing();

$database =  new Database($_ENV["DB_HOST"], 
                        $_ENV["DB_NAME"], 
                        $_ENV["DB_USER"], 
                        $_ENV["DB_PASS"]);

$userGateway = new UserGateway($database);

//auth
$auth = new Auth($userGateway);

if (!$auth->authenticateAccessToken()) {
    exit;
}

// if (!$auth->authenticateAPIKey()) {
//     exit;
// }

$userId = $auth->getUserId();

$taskGateway = new TaskGateway($database);

$controller = new TaskController($taskGateway, $userId);

$controller->processRequest($method, $id);
