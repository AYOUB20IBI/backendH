<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Room_Food;

class RoomFoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function  index()
    {
        $RoomFood = Room_Food::all();
        if (!$RoomFood) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'RoomFood'=> $RoomFood,
        ],200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:room__food,name',
        ]);

        if (!empty($validatedData)) {
            $roomFood = Room_Food::create([
                'name' => $validatedData['name'],
            ]);
            return response()->json(['message' => 'RoomFood created successfully', 'RoomFood' => $roomFood], 200);
        }
    }

    public function show(string $id)
    {
        $roomFood = Room_Food::find($id);

        if (!$roomFood) {
            return response()->json(['message' => 'RoomFood not found.'], 404);
        }

        return response()->json(['RoomFood' => $roomFood], 200);
    }

    public function update(Request $request, string $id)
    {
        $roomFood = Room_Food::find($id);

        if (!$roomFood) {
            return response()->json(['message' => 'RoomFood not found.'], 404);
        }

        $roomFood->update($request->all());

        return response()->json(['message' => 'RoomFood updated successfully', 'RoomFood' => $roomFood], 200);
    }

    public function destroy(string $id)
    {
        $roomFood = Room_Food::find($id);

        if (!$roomFood) {
            return response()->json(['message' => 'RoomFood not found.'], 404);
        }

        $roomFood->delete();

        return response()->json(['message' => 'RoomFood deleted successfully'], 200);
    }
}
