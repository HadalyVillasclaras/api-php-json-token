<?php

namespace App\Task\Application;

class AddTaskDTO
{
    private string $taskName;
    private int $priority;
    private bool $isCompleted;
    private int $userId;

    public function __construct(
        string $taskName,
        int $priority,
        bool $isCompleted,
        int $userId
    )
    {
        $this->taskName = $taskName;
        $this->priority = $priority;
        $this->isCompleted = $isCompleted;
        $this->userId = $userId;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
