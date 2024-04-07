<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number' ,
        'room_type_id',
        'food_id',
        'room_status_id',
        'price',
        'bed',
        'bathroom',
        'image1',
        'image2',
        'image3',
        'description',
        'ability',
    ];



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


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_number', 'room_number');
    }

}
