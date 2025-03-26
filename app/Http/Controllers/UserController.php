<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = $this->userService->create($fields);

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'token' => $token,
            'user' => Auth::guard('api')->user()
        ], 201);
    }

    public function login(Request $request){

        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if(Auth::guard('api')->attempt($fields)){
            $user = Auth::guard('api')->user();

            $token = Auth::guard('api')->login($user);

            return response()->json([
                'token' => $token,
                'user' => Auth::guard('api')->user()
            ], 201);
        }
        return response()->json([
            'message' => 'please check your credentials'
        ], 401);
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        $fields = $request->validate([
            'name' => 'string',
            'email' => 'string|email',
            'password' => 'string'
        ]);
        if(Auth::id() === $user->id){
            $update = $this->userService->update($fields, $id);
            return response()->json([
                'message' => 'user updated',
            ]);
        }
        return response()->json([
            'message' => 'you cannot edit this user'
        ]);
    }

    public function destroy($id){
        if(Auth::id() === $id){
            $this->userService->delete($id);
            return response()->json([
                'message' => 'user deleted'
            ], 200);
        }else{
            return response()->json([
                'message' => 'you cannot delete this user'
            ], 401);
        }
    }
    public function logout(){
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
