<?php

declare(strict_types=1);

use App\RefreshTokenGateway;

require __DIR__ . "bootstrap.php";

$refreshTokenGateway = new RefreshTokenGateway();

echo $refreshTokenGateway->deleteExpired(), "\n";
