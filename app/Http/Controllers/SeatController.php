<?php

namespace App\Http\Controllers;

use App\Repositories\contract\SeatRepositoryInterface;
use App\Repositories\SeatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seat;

class SeatController extends Controller
{
    protected $seatRepository;

    public function __construct(SeatRepositoryInterface $seatRepository){
        $this->seatRepository = $seatRepository;
    }

    public function store(Request $request){
        $fields = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'seats' => 'required|array',
            'seats.*.seat_number' => 'required',
            'seats.*.price' => 'integer|required',
        ]);

        $user = Auth::user();

        if($user->is_admin){
            foreach($fields['seats'] as $seat){
                $seatsexists = Seat::where('seat_number', $seat['seat_number'])->exists();
                if($seatsexists){
                    return response()->json(["message" => "Seat already exists!"], 404);
                }

                $seat['room_id'] = $fields['room_id'];

                $this->seatRepository->store($seat);
            }
            return response()->json([
                'message' => 'Seats have been created to room id: ' . $fields['room_id']
            ], 201);
        }
        return response()->json([
            'message' => 'You do not have permission to create seats'
        ], 403);
    }
}