<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room_Statuse;
class RoomStatus extends Controller
{
    public function  index()
    {
        $RoomStatus = Room_Statuse::all();
        if (!$RoomStatus) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'RoomStatus'=> $RoomStatus,
        ],200);
    }
}
