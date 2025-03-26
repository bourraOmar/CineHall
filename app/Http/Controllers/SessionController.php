<?php

namespace App\Http\Controllers;

use App\Repositories\contract\FilmRepositoryInterface;
use App\Repositories\contract\SessionRepositoryInterface;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    protected $sessionRepository;
    protected $filmRepository;
    public function __construct(SessionRepositoryInterface $sessionRepository, FilmRepositoryInterface $filmRepository){
        $this->sessionRepository = $sessionRepository;
        $this->filmRepository = $filmRepository;
    }

    public function index(){
        return $this->sessionRepository->getall();
    }

    public function store(Request $request){
        $fields = $request->validate([
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'language' => 'required|string',
            'film.title' => 'string',
            'film.description' => 'string',
            'film.image' => 'string',
            'film.duration' => 'integer',
            'film.minimum_age' => 'integer',
            'film.trailer_url' => 'string|url',
            'film.genre' => 'string',
            'film.user_id' => 'nullable|integer',
            'room_id' => 'required|integer',
            'film_id' => 'integer',
        ]);

        $user = Auth::user();
        if(!$user || !$user->is_admin){
            return response()->json([
                'message' => "you can't create sessions",
            ], 401);
        }

        $fields['user_id'] = Auth::id();

        if(isset($fields['film'])){
        $fields['film']['user_id'] = Auth::id();

        $film = $this->filmRepository->store($fields['film']);

        $fields['film_id'] = $film->id;
        }

        $this->sessionRepository->store($fields);

        return response()->json([
            'message' => 'session created successfully!',
            'session' => $fields
        ]);
    }
    public function getByType(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        if(count($this->sessionRepository->getByType($request->type)) > 0) {
            $session = $this->sessionRepository->getByType($request->type);
            return response()->json([
                'message' => 'sessions found',
                'session' => $session
            ]);
        }else{
            return response()->json([
                'message' => 'session not found with this type',
            ]);
        }
    }
}
