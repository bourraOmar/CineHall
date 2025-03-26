<?php

namespace App\Repositories\contract;

interface RoomRepositoryInterface
{
    public function getall();
    public function create($data);
}