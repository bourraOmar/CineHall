<?php

namespace App\Http\Controllers;

use App\Repositories\ReservationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\contract\ReservationRepositoryInterface;
use App\Models\Reservation;

class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'session_id' => 'required|integer|exists:sessions,id',
            'type' => 'required|string|in:couple,solo',
            'seats' => 'array|required',
            'seats.*' => 'integer|exists:seats,id'
        ]);

        $userId = Auth::id();
        $sessionId = $fields['session_id'];
        $seatsToReserve = [];

        if ($fields['type'] == 'couple') {
            foreach ($fields['seats'] as $seat) {
                $secondSeat = $seat + 1;

                $isSeatAvailable = !Reservation::where('session_id', $sessionId)
                    ->where('seat_id', $secondSeat)
                    ->exists();

                if (!$isSeatAvailable) {
                    return response()->json(['message' => "Seat $secondSeat is not available!"], 400);
                }

                $seatsToReserve[] = $seat;
                $seatsToReserve[] = $secondSeat;
            }
        } else {
            $seatsToReserve = $fields['seats'];
        }

        $reservedSeats = Reservation::where('session_id', $sessionId)
            ->whereIn('seat_id', $seatsToReserve)
            ->pluck('seat_id')
            ->toArray();

        $availableSeats = array_diff($seatsToReserve, $reservedSeats);

        if (empty($availableSeats)) {
            return response()->json(['message' => 'You have chosen reserved seats!'], 400);
        }

        $reservations = [];
        foreach ($availableSeats as $seat) {
            $reservation = $this->reservationRepository->reserveSeat($userId, $sessionId, $seat, count($availableSeats));
            if ($reservation) {
                $reservations[] = $reservation;
            }
        }

        $this->checkExpiredReservations();

        return response()->json([
            'message' => !empty($reservations) ? 'Reservation successful' : 'Reservation failed',
            'reserved_seats' => $reservations,
        ]);
    }


    public function checkExpiredReservations(){
        $reservations = Reservation::where('status', 'waiting')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
        }
    }

    public function update($id, Request $request)
    {
        $fields = $request->validate([
            'session_id' => 'required|integer|exists:sessions,id',
            'seat_id' => 'required|array',
            'seat_id.*' => 'integer|exists:seats,id',
        ]);

        $user = Auth::user();
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(["message" => "Reservation not found!"], 404);
        }

        if ($user->id !== $reservation->user_id) {
            return response()->json(["message" => "You don't own this reservation!"], 403);
        }

        $reservation->session_id = $fields['session_id'];

        $reservation->seat_id = $fields['seat_id'][0];

        $reservation->save();

        return response()->json(["message" => "Reservation updated!"]);
    }

    public function destroy($id){
        $reservation = Reservation::findOrFail($id);
        $user = Auth::user();
        if($reservation->status == 'waiting' && $user->id == $reservation->user_id){
            $this->reservationRepository->cancel($id);
            return response()->json(["message" => "Reservation cancelled!"], 200);
        }

        if(empty($reservation)){
            return response()->json(["message" => "Reservation not found!"], 404);
        }

        return response()->json(["message" => "You don't own this reservation!"], 403);
    }
}
