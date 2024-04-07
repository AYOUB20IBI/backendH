<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Admin;
use App\Models\Guest;
use Illuminate\Support\Facades\Notification;
use App\Models\Receptionist;
use App\Notifications\ReceptionistNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuestsController extends Controller
{
    public function create(RegisterUserRequest $request,$numero_id_Receptionist)
    {
        $data = $request->validated();
        $randomId = $this->generateUniqueNumeroId();

        if(!empty($data['image_profile'])){
            $image = $data['image_profile'];
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move('ProfileImages/', $fileName);
        } else {
            $fileName = 'logoprofile.png';
        }

        $user = Guest::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'numero_ID' => $randomId,
            'gender' => $data['gender'],
            'address' => $data['address'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'image_profile'=> 'ProfileImages/' . $fileName,
        ]);

        $admins = Admin::all();
        $title="Receptionist Add New Guest.";
        $message = "A Admin ".$numero_id_Receptionist." Add Guest. Guest ID: " . $user->numero_ID;
        foreach ($admins as $admin) {
            Notification::send($admin, new ReceptionistNotification($user->numero_ID,$user->numero_ID,$title,$message));
        }


        return response()->json([
            'user' => $user,
            'message' => 'Create Guest successful.',
        ]);
    }

    private function generateUniqueNumeroId()
    {
        $randomId = 'IBI-' . mt_rand(100000, 999999);

        // Check if the generated ID is unique
        while (Guest::where('numero_ID', $randomId)->exists()) {
            $randomId = 'IBI-' . mt_rand(100000, 999999);
        }

        return $randomId;
    }



    public function update(Request $request,$numero_id,$numero_id_Receptionist)
    {
        $guest = Guest::where('numero_ID', $numero_id)->first();

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
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

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
            $reciptions = Receptionist::where('numero_ID', '!=', $numero_id_Receptionist)->get();
            $title="Receptionist Updated Account Successful";
            $message = "A Admin ".$numero_id_Receptionist." Modified user. User ID: " . $guest->numero_ID;
            foreach ($reciptions as $Receptionist) {
                Notification::send($Receptionist, new ReceptionistNotification($guest->numero_ID,$guest->numero_ID,$title,$message));
            }

            return response()->json(['user'=>$guest,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'User not found'], 401);
        }

    }

    public function destroy(string $id)
    {
        //
    }
}
