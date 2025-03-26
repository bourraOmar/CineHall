<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'duration', 'minimum_age', 'trailer_url', 'genre'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function session(){
        return $this->hasMany(Session::class);
    }
}
