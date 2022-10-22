<?php

namespace App\Shared\Infrastructure\Database;

use PDO;
use PDOException;

class Database
{
    private string $host;
    private string $name;
    private string $user;
    private string $password;
    private ?PDO $connection = null;

    public function __construct(
    ) {
        $this->host = $_ENV["DB_HOST"];
        $this->name = $_ENV["DB_NAME"];
        $this->user = $_ENV["DB_USER"];
        $this->password = $_ENV["DB_PASS"];
    }

    public function getConnection(): PDO
    {
        try {
            if ($this->connection === null) {
                $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
    
                $this->connection = new PDO($dsn, $this->user, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false, 
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ]);
            }
    
            return $this->connection;

        } catch (PDOException $e) {
            echo json_encode(["message" => $e->getMessage()]); 
        }
    }
}