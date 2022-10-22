<?php

namespace App\Task\Domain;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;

    public function getAll(int $userId): ?array;

    public function getByUserId(int $userId, string $taskId);

    public function deleteByUserId(int $userId, string $taskId): int;

}