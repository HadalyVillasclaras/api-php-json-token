<?php

namespace App;

use App\Shared\Infrastructure\Database\Database;
use PDO;

class UserGateway
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function getByAPIKey(string $key)//: array | false
    {
        $sql = "SELECT *
                FROM user
                WHERE api_key = :api_key";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":api_key", $key, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function getByID(int $id) /*array | false*/
    {
        $sql = "SELECT * 
                FROM user
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);        
    }
}