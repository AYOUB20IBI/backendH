<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;


    public function roomType()
    {
        return $this->belongsTo(Room_type::class,"room_type_id");
    }

    public function roomStatuse()
    {
        return $this->belongsTo(Room_Statuse::class , "room_status_id");
    }

    public function roomFood()
    {
        return $this->belongsTo(Room_Food::class ,"food_id");
    }

}
