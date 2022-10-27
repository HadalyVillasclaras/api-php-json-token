<?php

declare(strict_types=1);

use App\Task\Controller\TaskController;
use App\User\Controller\UserController;

require dirname(__DIR__) . "/src/bootstrap.php";

// Routing
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/my_api/public/tasks', 'TaskController::getAllByUserId');
    $r->addRoute('GET', '/my_api/public/tasks/{id}', 'TaskController::getByUserId');
    $r->addRoute('POST', '/my_api/public/tasks', 'TaskController::addByUserId');
    $r->addRoute('PATCH', '/my_api/public/tasks/{id}', 'TaskController::updateByUserId');
    $r->addRoute('DELETE', '/my_api/public/tasks/{id}', 'TaskController::deleteByUserId');
    
    $r->addRoute('POST', '/my_api/public/user/logout', 'UserController::logout');
    $r->addRoute('POST', '/my_api/public/user/register', 'UserController::register');
    $r->addRoute('POST', '/my_api/public/user/login', 'UserController::login');
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$controllerMethod = explode("::",  $routeInfo[1]);
$controller = $controllerMethod[0];
$method = $controllerMethod[1];
$vars = $routeInfo[2];

// Process request
switch ($routeInfo[0]) {

    case \FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 'not found';
        break;

    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // ... 405 Method Not Allowed
        echo 'not found';
        break;

    case \FastRoute\Dispatcher::FOUND:
        if ($controller == "TaskController") {
            $controllerToLoad = new TaskController();
        } elseif ($controller == "UserController") {
            $controllerToLoad = new UserController();
        }
     
        if (array_key_exists('id', $vars)) {
            $controllerToLoad->$method((int)$vars['id']); 
        } else {
            $controllerToLoad->$method(); 
        }
        break;
}
