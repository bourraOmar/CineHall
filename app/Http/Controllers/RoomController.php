<?php

namespace App\Http\Controllers;

use App\Repositories\contract\RoomRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    protected $roomRepository;
    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }
    public function index(){
        return $this->roomRepository->getall();
    }

    public function store(Request $request){
        $fields = $request->validate([
            'name' => 'string|required',
            'capacity' => 'integer|required',
        ]);

        $user = Auth::user();

        if($user->is_admin){
            $this->roomRepository->create($fields);
            return response()->json([
                'message' => 'Room created',
                'room' => $fields
            ]);
        }
        if(!$user->is_admin){
            return response()->json([
                'message' => 'You are not allowed to create room',
            ]);
        }
    }
}