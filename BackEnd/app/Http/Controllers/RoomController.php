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

    public function  index2()
    {
        $rooms = Room::with('roomType','roomFood','roomStatuse')->paginate(10);
        if (!$rooms) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'rooms'=> $rooms,
        ],200);
    }

    public function  ShowRoom($id)
    {
        $room = Room::with('roomType','roomFood','roomStatuse')->find($id);
        if (!$room) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'room'=> $room,
        ],200);
    }
}
