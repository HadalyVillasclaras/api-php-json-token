<?php

class UserGateway
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function getByAPIKey(string $key)
    {
        $sql = "SELECT *
                FROM user
                WHERE api_key = :api_key";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":api_key", $key, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUsername(string $username)
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
