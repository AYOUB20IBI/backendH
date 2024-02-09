<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guest;
use Illuminate\Support\Facades\Hash;

class UpdateGuestAuthController extends Controller
{
    public function store(Request $request)
    {


        $id = $request->id;
        $guest = Guest::find($id);

        if ($guest) {
            $data = $request->validate([
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,gif'],
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'date_of_birth' => 'required|date',
                'phone_number' => 'required|string',
                'email' => 'nullable',
            ]);


            if ($request->image) {
                $image = $data['image'];
                $fileName = time() . '.' . $image->getClientOriginalExtension();

                $image->move('ProfileImages/', $fileName);
                $guest->image_profile = 'ProfileImages/' . $fileName;
            }


            $guest->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
            ]);

            return response()->json(['user'=>$guest,'image'=>$request->image_profile,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

    }

    public function destroy( $id)
    {
        $guest = Guest::find($id);

        // dd($guest);
        if (!$guest) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }else{
            $guest->delete();
            return response()->json([
                "message" => "Successfully deleted !"
            ],200);
        }
    }



    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
            'password' => 'required|string|confirmed',
        ]);

        $guest = Guest::where('id', $data['id'])->first();

        if($guest){
            $guest->update([
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                "message" => "Password Changed Successfully!"
            ],200);
        }else{
            return response()->json([
                'message' => 'not found'
            ], 404);
        }
    }
}

