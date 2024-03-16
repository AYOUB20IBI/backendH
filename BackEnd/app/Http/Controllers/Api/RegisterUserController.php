<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Admin;
use App\Models\Guest;
use App\Notifications\RegisterNotification;
use App\Notifications\WellcomGuestNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;


class RegisterUserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $randomId = $this->generateUniqueNumeroId();

        if(!empty($data['image_profile'])){
            $image = $data['image_profile'];
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            $image->move('ProfileImages/', $fileName);
        }else {
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



        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        $admins = Admin::all();

        $title="Registration Successful";
        $message = "A new user has successfully registered. User ID: " . $user->numero_ID;
        foreach ($admins as $admin) {
            Notification::send($admin, new RegisterNotification($user->numero_ID,$title,$message));
        }

        $title_1="welcome";
        $message_1 = "Welcome !! ".$user->first_name." ".$user->last_name." In Hotel Sahara.";
        Notification::send($user, new WellcomGuestNotification($user->numero_ID,$title_1,$message_1));

        return response()->json([
            'user' => $user,
            'message' => 'Registration successful.',
        ])->withCookie($cookie);
    }

    private function generateUniqueNumeroId()
    {
        $randomId = 'IBI-' . mt_rand(100000, 999999);

        // Check if the generated ID is unique
        while (Guest::where('numero_ID', $randomId)->exists()) {
            $randomId = 'BI-' . mt_rand(100000, 999999);
        }

        return $randomId;
    }



    public function GoogleRegister(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'string',
            'address' => 'string',
            'date_of_birth' => 'nullable|date',
            'phone_number' => 'string',
            'email' => 'required|email|unique:guests,email',
            'password' => 'string',
            'image_profile' => 'url',
        ]);

        $randomId = $this->generateUniqueNumeroId();
        if (!empty($data['image_profile'])) {
            $imageUrl = $data['image_profile'];
            $imageContents = file_get_contents($imageUrl);
            $fileName = time() . '.' . pathinfo($imageUrl, PATHINFO_EXTENSION);
            $filePath = 'ProfileImages/' . $fileName.'png';
            file_put_contents(public_path($filePath), $imageContents);

            $data['image_profile'] = $filePath;
        }

        $user = Guest::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'numero_ID' => $randomId,
            'gender' => $data['gender'] ?? '',
            'address' => $data['address'] ?? '',
            'date_of_birth' => $data['date_of_birth'] ?? now(),
            'phone_number' => $data['phone_number'] ?? '',
            'email' => $data['email'],
            'password' => $data['password'] ?? '',
            'role' => 'user',
            'image_profile' => $fileName ? 'ProfileImages/' . $fileName.'png' : 'ProfileImages/logoprofile.png', // Si $fileName est vide, utilisez le profil par défaut
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return response()->json([
            'user' => $user,
            'message' => 'Registration successful.',
        ])->withCookie($cookie);
    }

}
