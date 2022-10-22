<?php

namespace App;

use App\Shared\Infrastructure\Database\Database;
use PDO;

class RefreshTokenGateway
{
    private PDO $connection;
    private string $key;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->key = $_ENV["SECRET_KEY"];

    }

    public function create(string $token, int $expiry): bool
    {
        $hash = hash_hmac("sha256", $token, $this->key);

        $sql = "INSERT INTO refresh_token (token_hash, expires_at)
                VALUES (:token_hash, :expires_at)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $expiry, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(string $token): int
    {
        $hash = hash_hmac("sha256", $token, $this->key);

        $sql = "DELETE FROM refresh_token
                WHERE token_hash = :token_hash";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getByToken(string $token) /*array | false*/
    {
        $hash = hash_hmac("sha256", $token, $this->key);

        $sql = "SELECT *
                FROM refresh_token
                WHERE token_hash = :token_hash";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteExpired(): int
    {
        $sql = "DELETE FROM refresh_token
                WHERE expires_at < UNIX_TIMESTAMP()";

        $stmt = $this->connection->query($sql);

        return $stmt->rowCount();
    }
}