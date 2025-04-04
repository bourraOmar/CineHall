<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'film',
        'start_time',
        'end_time',
        'seat',
        'qr_code',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
