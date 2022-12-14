<?php 

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("App\\Shared\\Domain\\Exceptions\\ErrorHandler::handleError");
set_exception_handler("App\\Shared\\Domain\\Exceptions\\ErrorHandler::handleException"); 

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-type: application/json; charset=UTF-8");
