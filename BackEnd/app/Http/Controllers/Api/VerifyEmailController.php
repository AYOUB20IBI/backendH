<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth:sanctum');
    }
    /**dd/** */
    //mark email as verified
    public function sendMail()
    {
        \Mail::to(auth()->user())->send(new EmailVerification(auth()->user()));
        return response(['message' => ' Please check your email to verify your account.']);
    }

    public function verify(Request $request)
    {
        if(!$request->user()->email_verified_at){
            $request->user()->forceFill([
                'email_verified_at'=> now()
            ])->save();
        }
        return response()->json([
            'message'=> 'Email verified'
        ]);
    }


}
