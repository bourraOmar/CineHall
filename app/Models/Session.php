<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['film_id', 'room_id','start_date', 'end_date', 'language', 'type'];

    public function film(){
        return $this->belongsTo(Film::class);
    }

    public function room(){
        return $this->hasMany(Room::class);
    }
}
