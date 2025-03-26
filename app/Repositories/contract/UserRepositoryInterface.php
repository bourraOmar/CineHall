<?php

namespace App\Repositories\contract;
use App\Models\User;

interface UserRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection;

    public function create(array $data): ?User;

    public function update(array $data, int $id): int;

    public function delete(int $id): bool;

}
