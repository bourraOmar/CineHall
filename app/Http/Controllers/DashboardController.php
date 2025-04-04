<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\session;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getOverview()
    {
        $user = Auth::user();

        if($user->is_admin) {
        return response()->json([
            'total_films' => Film::count(),
            'total_seances' => Session::count(),
            'total_reservations' => Reservation::count(),
        ]);
        }
        return response()->json([
            'message' => "You don't have access to this resources",
        ], 403);
    }

    public function getOccupationRate()
    {
        $totalPlaces = session::with('room')->get()->sum(function ($seance) {
            return $seance->room->capacity ?? 0;
        });

        $totalReservations = Reservation::where('status', 'payed')->count();

        $occupationRate = $totalPlaces > 0
            ? ($totalReservations / $totalPlaces) * 100
            : 0;

        return response()->json([
            'taux_occupation' => round($occupationRate, 2) . '%',
        ]);
    }
    public function getPopularFilms(): JsonResponse
    {
        $films = Film::with('session.reservations')
            ->get()
            ->map(fn($film) => [
                'film' => $film->title,
                'tickets_sold' => $film->session->sum(fn($seance) => $seance->reservations->count())
            ])
            ->sortByDesc('tickets_sold')
            ->values();

        return response()->json($films);
    }
}
