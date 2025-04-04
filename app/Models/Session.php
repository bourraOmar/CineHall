<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Film;
use App\Models\Room;
use App\Models\Reservation;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['film_id', 'room_id','start_date', 'end_date', 'language', 'type'];

    public function film(){
        return $this->belongsTo(Film::class);
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
