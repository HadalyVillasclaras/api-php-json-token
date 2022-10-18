<?php

class TaskGateway
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function getAllByUserId(int $userId): array
    {
        $sql = "SELECT *
                FROM task
                WHERE user_id = :userId";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $row['is_completed'] = (bool) $row['is_completed'];
            $data[] = $row;
        }

        return $data;
    }

    public function getByUserId(int $userId, string $id)//: array | false
    {
        $sql = "SELECT *
                FROM task
                WHERE id = :id
                AND user_id = :userId";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC); //array or false if failure

        if($data !== false) {
            $data['is_completed'] = (bool) $data['is_completed'];
        }

        return $data;
    }

    public function createByUserId(int $userId, array $data): string
    {
        $sql = "INSERT INTO task (name, priority, is_completed, user_id)
                VALUES (:name, :priority, :is_completed, :userId)";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);

        if (empty($data["priority"])) {

            $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {

            $stmt->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
        }

        $stmt->bindValue(":is_completed", $data["is_completed"] ?? false,
                        PDO::PARAM_BOOL);

        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function updateByUserId(int $userId, string $id, array $data): int
    {
        $fields = [];

        if (!empty($data["name"])) {

            $fields["name"] = [
                $data["name"],
                PDO::PARAM_STR
            ];
        }

        if (array_key_exists("priority", $data)) {

            $fields["priority"] = [
                $data["priority"],
                $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }

        if (array_key_exists("is_completed", $data)) {

            $fields["is_completed"] = [
                $data["is_completed"],
                PDO::PARAM_BOOL
            ];
        }

        if (empty($fields)) {

            return 0;

        } else {

            $sets = array_map(function($value) {

                return "$value = :$value";
            }, array_keys($fields));
    
            $sql = "UPDATE task"
                . " SET " . implode(", ", $sets)
                . " WHERE id = :id"
                . "AND user_id = :userId";

            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {

                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }

    public function deleteByUserId(int $userId, string $id): int
    {
        $sql = "DELETE FROM task
                WHERE id = :id
                AND user_id = :userId";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
