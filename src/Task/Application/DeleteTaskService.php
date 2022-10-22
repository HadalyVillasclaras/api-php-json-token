<?php

namespace App\Task\Application;

use App\Task\Domain\TaskRepositoryInterface;

class DeleteTaskService
{
    protected $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function execute(DeleteTaskDTO $deleteTaskDTO)
    {
        $userId = $deleteTaskDTO->getUserId();
        $taskId = $deleteTaskDTO->getTaskId();

        $tasks = $this->taskRepositoryInterface->deleteByUserId($userId, $taskId);

        return $tasks;
    }
}