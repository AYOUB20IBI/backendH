<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Room_type;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function  index()
    {
        $RoomType = Room_type::all();
        if (!$RoomType) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'RoomType'=> $RoomType,
        ],200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:room_types,name',
        ]);

        if ($request->has('name')) {
            $roomType = Room_type::create([
                'name' => $request->name,
            ]);
            return response()->json(['message' => 'RoomType created successfully', 'RoomType' => $roomType], 200);
        }
    }

    public function show(string $id)
    {
        $roomType = Room_type::find($id);

        if (!$roomType) {
            return response()->json(['message' => 'RoomType not found.'], 404);
        }

        return response()->json(['RoomType' => $roomType], 200);
    }

    public function update(Request $request, string $id)
    {
        $roomType = Room_type::find($id);

        if (!$roomType) {
            return response()->json(['message' => 'RoomType not found.'], 404);
        }

        $roomType->update($request->all());

        return response()->json(['message' => 'RoomType updated successfully', 'RoomType' => $roomType], 200);
    }

    public function destroy(string $id)
    {
        $roomType = Room_type::find($id);

        if (!$roomType) {
            return response()->json(['message' => 'RoomType not found.'], 404);
        }

        $roomType->delete();

        return response()->json(['message' => 'RoomType deleted successfully'], 200);
    }
}
