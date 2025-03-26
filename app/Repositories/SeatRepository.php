<?php

namespace App\Repositories;

use App\Models\Seat;
use App\Repositories\contract\SeatRepositoryInterface;

class SeatRepository implements SeatRepositoryInterface
{
    public function store($data){
        return Seat::create($data);
    }
}