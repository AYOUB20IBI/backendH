<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Guest;
use App\Models\Admin;
use App\Models\Receptionist;
use App\Http\Requests\LoginRequest;
class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $user = Guest::where('email', $input['email'])->first();
        $admin = Admin::where('email', $input['email'])->first();
        $receptionist = Receptionist::where('email', $input['email'])->first();

        if ($user && Hash::check($input['password'], $user->password)) {
            $token = $user->createToken('my_app', ['guest'])->plainTextToken;
            $cookie = cookie('token', $token, 60 * 24); // 1 day

            return response()->json([
                'token' => $token,
                'user'=>$user,
                'role'=>'user'
            ])->withCookie($cookie);
        }

        if ($receptionist && Hash::check($input['password'], $receptionist->password)) {
            $token = $receptionist->createToken('my_app', ['receptionist'])->plainTextToken;
            $cookie = cookie('token', $token, 60 * 24); // 1 day

            return response()->json([
                'token' => $token,
                'user'=>$receptionist,
                'role'=>'receptionist'
            ])->withCookie($cookie);
        }

        if ($admin && Hash::check($input['password'], $admin->password)) {
            $token = $admin->createToken('my_app', ['admin'])->plainTextToken;
            $cookie = cookie('token', $token, 60 * 24); // 1 day

            return response()->json([
                'token' => $token,
                'admin'=>$admin,
                'role'=>'admin'
            ])->withCookie($cookie);
        }



        return response()->json([
            'message' => "Email or password is incorrect"
        ], 401);
    }


    public function userDetail(){
        $user = Auth::user();
        return response()->json(
            [
                'user'=>$user
            ]
        );
    }
}
