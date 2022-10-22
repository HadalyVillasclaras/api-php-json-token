<?php

namespace App\Task\Application;

use App\Task\Domain\TaskRepositoryInterface;

class GetTaskService
{
    protected $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function execute(GetTaskDTO $getTaskDTO)
    {
        $userId = $getTaskDTO->getUserId();
        $taskId = $getTaskDTO->getTaskId();

        $tasks = $this->taskRepositoryInterface->getByUserId($userId, $taskId);

        return $tasks;
    }

}