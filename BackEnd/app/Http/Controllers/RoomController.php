<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\Room_Food;
use App\Models\Room_Statuse;
use App\Models\Room_type;


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


    public function CounterRooms()
    {
        $countRooms = Room::count();
        $countRoomTypes = Room_type::count();
        $countRoomStatus = Room_Statuse::count();
        $countRoomFoods = Room_Food::count();

        return response()->json([
            'countRooms'=> $countRooms,
            'countRoomTypes'=> $countRoomTypes,
            'countRoomFoods'=> $countRoomFoods,
            'countRoomStatus'=> $countRoomStatus,
        ],200);
    }
}
