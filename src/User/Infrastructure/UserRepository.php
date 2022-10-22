<?php

namespace App\User\Infrastructure;

use App\Shared\Infrastructure\Database\Database;
use App\User\Domain\User;
use App\User\Domain\UserRepositoryInterface;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function register(User $user): void
    {
        $sql = "INSERT INTO user (name, email, username, password_hash, api_key)
        VALUES (:name, :email, :username, :password_hash, :api_key)";

        $stmt = $this->connection->prepare($sql);

        $password_hash = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $api_key = bin2hex(random_bytes(16));

        $stmt->bindValue(":name", $user->getName(), PDO::PARAM_STR);
        $stmt->bindValue(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
        $stmt->bindValue(":api_key", $api_key, PDO::PARAM_STR);

        $stmt->execute();

        echo "Thank you for registering. Your API key is ", $api_key;
    }

    public function getByUsername(string $username)//: array | false
    {
        $sql = "SELECT *
                FROM user
                WHERE username = :username";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":username", $username, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}