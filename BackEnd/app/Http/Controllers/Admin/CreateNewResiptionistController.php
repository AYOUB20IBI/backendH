<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateReceptionistRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Notification;
use App\Models\Receptionist;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CreateNewResiptionistController extends Controller
{
    public function create(CreateReceptionistRequest $request)
    {
        $data = $request->validated();
        $randomId = $this->generateUniqueNumeroId();

        if(!empty($data['image_profile'])){
            $image = $data['image_profile'];
            $fileName = time() . '_' . uniqid() . '.'. $image->getClientOriginalExtension();

            $image->move('ProfileImages/', $fileName);
        }else {
            $fileName = 'logoprofile.png';
        }

        $user = Receptionist::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'numero_ID' => $randomId,
            'gender' => $data['gender'],
            'address' => $data['address'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'receptionist',
            'image_profile'=> 'ProfileImages/' . $fileName,
        ]);



        return response()->json([
            'user' => $user,
            'message' => 'Create Receptionist successful.',
        ]);
    }

    private function generateUniqueNumeroId()
    {
        $randomId = 'RE-' . mt_rand(100000, 999999);

        // Check if the generated ID is unique
        while (Receptionist::where('numero_ID', $randomId)->exists()) {
            $randomId = 'RE-' . mt_rand(100000, 999999);
        }

        return $randomId;
    }

    public function update(Request $request,$numero_id,$numero_id_Admin)
    {
        $receptionist = Receptionist::where('numero_ID', $numero_id)->first();

        if ($receptionist) {
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
                $receptionist->image_profile = 'ProfileImages/' . $fileName;
            }


            $receptionist->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
            ]);
            $admins = Admin::where('numero_ID', '!=', $numero_id_Admin)->get();
            $title="Admin Updated Account Successful";
            $message = "A Admin ".$numero_id_Admin." Modified Receptionist. User ID: " . $receptionist->numero_ID;
            foreach ($admins as $admin) {
                Notification::send($admin, new AdminNotification($receptionist->numero_ID,$receptionist->numero_ID,$title,$message));
            }

            return response()->json(['user'=>$receptionist,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'User not found'], 401);
        }

    }
    public function destroy(string $id)
    {
        //
    }
}
