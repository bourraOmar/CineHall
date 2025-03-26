<?php

namespace App\Repositories\contract;

use App\Models\Film;

interface FilmRepositoryInterface
{
    public function getall();
    public function store(array $data) : Film;
    public function update(array $data, int $id) : int;
    public function destroy(int $id) : int;
}
