<?php

class Database
{
    private $host;
    private $name;
    private $user;
    private $password;

    private ?PDO $conn = null;

    public function __construct(
        //private string $host
        string $host,
        string $name,
        string $user,
        string $password
    ) {

        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

            $this->conn = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false, //deshabilita la emulacion de sentencias preparadas
                PDO::ATTR_STRINGIFY_FETCHES => false //convierte todos los valores a strings x defecto (si true)
            ]);
        }

        return $this->conn;
    }
}