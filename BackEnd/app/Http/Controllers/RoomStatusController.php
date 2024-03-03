<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room_Statuse;
class RoomStatusController extends Controller
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

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:room__statuses,name',
        ]);

        if (!empty($validatedData)) {
            $roomType = Room_Statuse::create([
                'name' => $validatedData['name'],
            ]);
            return response()->json(['message' => 'RoomType created successfully', 'roomType' => $roomType], 200);
        }
    }

    public function show(string $id)
    {
        $roomStatus = Room_Statuse::find($id);

        if (!$roomStatus) {
            return response()->json(['message' => 'RoomStatus not found.'], 404);
        }

        return response()->json(['roomStatus' => $roomStatus], 200);
    }

    public function update(Request $request, string $id)
    {
        $roomType = Room_Statuse::find($id);

        if (!$roomType) {
            return response()->json(['message' => 'RoomType not found.'], 404);
        }

        $roomType->update($request->all());

        return response()->json(['message' => 'RoomType updated successfully', 'roomType' => $roomType], 200);
    }

    public function destroy(string $id)
    {
        $roomType = Room_Statuse::find($id);

        if (!$roomType) {
            return response()->json(['message' => 'RoomType not found.'], 404);
        }

        $roomType->delete();

        return response()->json(['message' => 'RoomType deleted successfully'], 200);
    }
}
