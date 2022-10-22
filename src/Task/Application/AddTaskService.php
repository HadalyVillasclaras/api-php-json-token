<?php

namespace App\Task\Application;

use App\Task\Domain\Task;
use App\Task\Domain\TaskRepositoryInterface;

class AddTaskService
{
    protected $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function execute(AddTaskDTO $addTaskDTO)
    {
        $task = new Task(
            $addTaskDTO->getTaskName(),
            $addTaskDTO->getPriority(),
            $addTaskDTO->getIsCompleted(),
            $addTaskDTO->getUserId()
        );

        $this->taskRepositoryInterface->save($task);

        return $task;
    }
}