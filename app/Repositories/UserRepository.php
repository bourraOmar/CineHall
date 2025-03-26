<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\contract\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all();
    }

    public function create(array $data): ?User
    {
        return User::create($data);
    }

    public function update(array $data, int $id): int
    {
        $user = User::findOrFail($id);

        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }

}
