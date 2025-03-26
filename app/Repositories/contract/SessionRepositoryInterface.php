<?php

namespace App\Repositories\contract;

interface SessionRepositoryInterface
{
    public function getall();
    public function store(array $data);
    public function update(array $data, int $id);
    public function destroy(int $id);
    public function getByType(string $type);
}
