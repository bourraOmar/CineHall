<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seat;
use App\Models\Session;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'capacity'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
