<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Room;


class RoomController extends Controller
{
    public function  index()
    {
        $rooms = Room::with('roomType','roomFood','roomStatuse')->get();
        if (!$rooms) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'rooms'=> $rooms,
        ],200);
    }
}
