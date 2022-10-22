<?php

namespace App\Task\Application;

use App\Task\Domain\TaskRepositoryInterface;

class GetAllTasksService
{
    protected $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function execute(GetAllTasksDTO $getAllTasksDTO)
    {
        $userId = $getAllTasksDTO->getUserId();

        $tasks = $this->taskRepositoryInterface->getAll($userId);

        return $tasks;
    }

}