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
        $rooms = Room::with('roomType','roomFood','roomStatuse')->paginate(9);
        if (!$rooms) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'rooms'=> $rooms,
        ],200);
    }

    public function  ShowRoom($id)
    {
        $room = Room::with('roomType', 'roomFood', 'roomStatuse')
            ->where('room_number', $id)
            ->first();
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





    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                'unique:rooms,room_number',
                function ($attribute, $value, $fail) {
                    if (strpos($value, 'SGB-') !== 0) {
                        $fail('The ' . $attribute . ' must start with SGB-');
                    }
                },
            ],
            'room_type_id' => 'required|exists:room_types,id',
            'food_id' => 'required|exists:room__food,id',
            'room_status_id' => 'required|exists:room__statuses,id',
            'price' => 'required|numeric|min:0',
            'bed' => 'required|integer|min:0',
            'bathroom' => 'required|integer|min:0',
            'image1' => ['required', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image2' => ['required', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image3' => ['required', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'description' => 'required|string',
            'ability' => 'required|integer|min:0',
        ]);


        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $fileName1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move('RoomImages/', $fileName1);
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $fileName2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move('RoomImages/', $fileName2);
        }

        if ($request->hasFile('image3')) {
            $image3 = $request->file('image3');
            $fileName3 = time() . '_' . uniqid() . '.' . $image3->getClientOriginalExtension();
            $image3->move('RoomImages/', $fileName3);
        }


        $room = Room::create([
            'room_number' => $validatedData['room_number'],
            'room_type_id' => $validatedData['room_type_id'],
            'food_id' => $validatedData['food_id'],
            'room_status_id' => $validatedData['room_status_id'],
            'price' => $validatedData['price'],
            'bed' => $validatedData['bed'],
            'bathroom' => $validatedData['bathroom'],
            'image1' =>  'RoomImages/' . $fileName1,
            'image2' => 'RoomImages/' . $fileName2,
            'image3' => 'RoomImages/' . $fileName3,
            'description' => $validatedData['description'],
            'ability' => $validatedData['ability'],
        ]);

        return response()->json([
            'message' => 'Room created successfully',
        ]);
    }



    public function update(Request $request, $id)
    {
        $room = Room::where('room_number', $id)->first();
        if(!$room) {
            return response()->json([
                'message' => 'Room not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'room_number' => [
                'required',
                'string',
                'max:255',
                'unique:rooms,room_number,'.$room->id,
                function ($attribute, $value, $fail) {
                    if (strpos($value, 'SGB-') !== 0) {
                        $fail('The ' . $attribute . ' must start with SGB-');
                    }
                },
            ],
            'room_type_id' => 'required|exists:room_types,id',
            'food_id' => 'required|exists:room__food,id',
            'room_status_id' => 'required|exists:room__statuses,id',
            'price' => 'required|numeric|min:0',
            'bed' => 'required|integer|min:0',
            'bathroom' => 'required|integer|min:0',
            'image1' => ['nullable', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image2' => ['nullable', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image3' => ['nullable', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'description' => 'required|string',
            'ability' => 'required|integer|min:0',
        ]);

        $dataToUpdate = [
            'room_number' => $validatedData['room_number'],
            'room_type_id' => $validatedData['room_type_id'],
            'food_id' => $validatedData['food_id'],
            'room_status_id' => $validatedData['room_status_id'],
            'price' => $validatedData['price'],
            'bed' => $validatedData['bed'],
            'bathroom' => $validatedData['bathroom'],
            'description' => $validatedData['description'],
            'ability' => $validatedData['ability'],
        ];

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $fileName1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move('RoomImages/', $fileName1);
            $dataToUpdate['image1'] = 'RoomImages/' . $fileName1;
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $fileName2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move('RoomImages/', $fileName2);
            $dataToUpdate['image2'] = 'RoomImages/' . $fileName2;
        }

        if ($request->hasFile('image3')) {
            $image3 = $request->file('image3');
            $fileName3 = time() . '_' . uniqid() . '.' . $image3->getClientOriginalExtension();
            $image3->move('RoomImages/', $fileName3);
            $dataToUpdate['image3'] = 'RoomImages/' . $fileName3;
        }

        $room->update($dataToUpdate);

        return response()->json([
            'message' => 'Room updated successfully',
        ]);
    }



    public function destroy(string $id)
    {
        $room = Room::where('room_number', $id)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }

        $room->delete();
        return response()->json(['message' => 'Room deleted successfully'], 200);
    }
}
