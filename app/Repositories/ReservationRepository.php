<?php

namespace App\Repositories;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Reservation;
use App\Repositories\contract\ReservationRepositoryInterface;
use App\Models\Session;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function reserveSeat(int $userId, int $sessionId, int $seatId, int $seatsCount)
    {
        $seat = Seat::findOrFail($seatId);
        $session = Session::findOrFail($sessionId);


        if ($seat->room_id !== $session->room_id) {
            return null;
        }

        if ($session->type == 'VIP' && $seatsCount < 2){
            return null;
        }

        $room = Room::findOrFail($session->room_id);

        if(Reservation::where('seat_id', $seatId)->count() >= $room->capacity){
            return null;
        }

        $existingReservation = Reservation::where('session_id', $sessionId)
            ->where('seat_id', $seatId)
            ->exists();

        if ($existingReservation) {
            return null;
        }

        return Reservation::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'seat_id' => $seatId,
            'seats_count' => $seatsCount,
            'created_at' => Carbon::now(),
        ]);
    }
    public function modify(int $reservationId, $data){
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update($data);
    }

    public function cancel(int $reservationId){
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();
    }
}