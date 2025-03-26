<?php

namespace App\Repositories\contract;

interface ReservationRepositoryInterface
{
    public function reserveSeat(int $userId, int $sessionId, int $seatId, int $seatsCount);
    public function modify(int $reservationId, $data);
    public function cancel(int $reservationId);
}