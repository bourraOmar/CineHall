<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Repositories\contract\FilmRepositoryInterface;
use App\Services\FilmService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    protected $filmRepository;
    public function __construct(FilmRepositoryInterface $filmRepository) {
        $this->filmRepository = $filmRepository;
    }

    public function index(){
        return $this->filmRepository->getall();
    }

    public function store(Request $request){
        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string|string',
            'duration' => 'required',
            'minimum_age' => 'required|integer',
            'trailer_url' => 'required|string',
            'genre' => 'required|string'
        ]);

        $user = Auth::user();

        if (!$user || !$user->is_admin) {
            return response()->json(["message" => "You can't create a film!"], 401);
        }

        $film = $this->filmRepository->store($fields);

        return response()->json([
            "message" => "Film created!",
            "film" => $film
        ], 201);
    }

    public function update(Request $request, int $id) {
        if(Film::where('user_id', Auth::id())->where('id', $id)->exists()) {
            $this->filmRepository->update($request->all(), $id);

            return response()->json([
                "message" => "Film updated!",
            ], 200);
        }
        return response()->json([
            "message" => "Film not found!",
        ]);
    }

    public function destroy(int $id) {
        $film = Film::find($id);
        if($film->user_id == Auth::id()) {
            $this->filmRepository->destroy($id);
            return response()->json([
                'message' => 'Film deleted!'
            ]);
        }
    }
}
