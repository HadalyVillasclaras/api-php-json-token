<?php

namespace App\Task\Infrastructure;

use App\Shared\Infrastructure\Database\Database;
use App\Task\Domain\Task;
use App\Task\Domain\TaskRepositoryInterface;
use PDO;

class TaskRepository implements TaskRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function save(Task $task):void
    {
        $sql = "INSERT INTO task (name, priority, is_completed, user_id)
        VALUES (:name, :priority, :is_completed, :userId)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":name", $task->getTaskName(), PDO::PARAM_STR);
        $stmt->bindValue(":priority", $task->getPriority(), PDO::PARAM_INT);
        $stmt->bindValue(":is_completed", $task->getIsCompleted(), PDO::PARAM_BOOL);
        $stmt->bindValue(":userId", $task->getUserId(), PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getAll(int $userId): ?array
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

    public function createByUserId(int $userId, array $data): string //seria el save
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



    public function getByUserId(int $userId, string $taskId) /*: array| false*/
    {
        $sql = "SELECT *
                FROM task
                WHERE id = :taskId
                AND user_id = :userId";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":taskId", $taskId, PDO::PARAM_INT);
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC); //array or false if failure

        if($data !== false) {
            $data['is_completed'] = (bool) $data['is_completed'];
        }

        return $data;
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
    
            $sql = "UPDATE task SET " . implode(", ", $sets)
                . " WHERE id = :id AND user_id = :userId";

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

    public function deleteByUserId(int $userId, string $taskId): int
    {
        $sql = "DELETE FROM task
                WHERE id = :taskId
                AND user_id = :userId";
        
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":taskId", $taskId, PDO::PARAM_INT);
        $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}