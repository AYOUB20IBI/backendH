<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Guest;
use Illuminate\Support\Facades\Hash;

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
}
