<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\contract\UserRepositoryInterface;

class UserService
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository ){
        $this->userRepository = $userRepository;
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, int $id)
    {
        return $this->userRepository->update($data, $id);
    }

    public function delete(int $id)
    {
        $this->userRepository->delete($id);
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userRepository->all();
    }
}
