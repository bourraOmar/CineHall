<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilmController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    Route::post('/logout', [UserController::class, 'logout']);

    //films
    Route::post('/film', [FilmController::class, 'store']);
    Route::put('film/{id}', [FilmController::class, 'update']);
    Route::delete('film/{id}', [FilmController::class, 'destroy']);
    Route::get('/film', [FilmController::class, 'index']);

    //session
    Route::post('/session', [SessionController::class, 'store']);
    Route::get('/session', [FilmController::class, 'index']);
    Route::get('/sessions', [SessionController::class, 'getByType']);

    //room
    Route::post('/room', [RoomController::class, 'store']);
    Route::get('/room', [RoomController::class, 'index']);

    //seats
    Route::post('/seats', [SeatController::class, 'store']);

    //reservation
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    //payment
    Route::post('/paypal/capture', [ReservationController::class, 'capture']);

    //Dashboard
    Route::get('/dashboard/overview', [DashboardController::class, 'getOverview']);
    Route::get('/dashboard/occupation-rate', [DashboardController::class, 'getOccupationRate']);
    Route::get('/dashboard/popular-films', [DashboardController::class, 'getPopularFilms']);
});
