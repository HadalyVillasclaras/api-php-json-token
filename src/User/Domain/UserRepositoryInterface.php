<?php

namespace App\User\Domain;

interface UserRepositoryInterface
{
    public function register(User $user): void;

    // public function getAll(int $userId): ?array;

    public function getByUserName(string $userName);

    // public function deleteByUserId(int $userId, string $taskId): int;

}