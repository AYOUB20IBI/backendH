<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function RoomNumber()
    {
        return $this->belongsTo(Room::class , "id");
    }

    public function guestNumber()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }
}
