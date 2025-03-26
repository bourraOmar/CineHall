<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\contract\RoomRepositoryInterface;
use App\Models\Reservation;

class RoomRepository implements RoomRepositoryInterface
{
    public function create($data){
        return Room::create($data);
    }

    public function getall(){
        $rooms = Room::with('seats')->with('sessions')->get();

        foreach ($rooms as $room) {
            foreach ($room->seats as $seat) {
                $session = $room->sessions()->whereNotNull('room_id')->first();

                if ($session) {
                    $reservation = Reservation::where('seat_id', $seat->id)
                        ->where('session_id', $session->id)
                        ->first();

                    $seat->is_reserved = $reservation ? true : false;
                } else {
                    $seat->is_reserved = false;
                }
            }
        }

        return $rooms;
    }
}