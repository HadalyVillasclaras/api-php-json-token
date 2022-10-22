<?php

namespace App\Task\Domain;

class Task 
{
    private $id;
    private $taskName;
    private $priority;
    private $isCompleted;
    private $userId;

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

    public function getId()
    {
        return $this->id;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function setTaskName(string $taskName)
    {
        $this->taskName = $taskName;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }
}