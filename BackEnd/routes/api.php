<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\VerifyEmailController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\UpdateAuthController;
use App\Http\Controllers\Admin\UsersController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Routes Auth  login

Route::post('login',[LoginController::class , 'login']);

Route::post('register',[RegisterUserController::class , 'register']);


//email virification
Route::post('email/verify/send',[VerifyEmailController::class,'sendMail']);

Route::post('email/verify',[VerifyEmailController::class,'verify'])->name('ayoub');



//Data user
Route::middleware(['auth:admin,guest,receptionist'])->get('/user', function () {
    if (Auth::guard('admin')->check()) {
        return response()->json([
            'user' => Auth::user(),
            'message' => 'hello admin',
            'role'=>'admin'
        ]);
    } elseif (Auth::guard('guest')->check()) {
        return response()->json([
            'user' => Auth::user(),
            'message' => 'hello user',
            'role'=>'user'
        ]);
    }elseif (Auth::guard('receptionist')->check()) {
        return response()->json([
            'user' => Auth::user(),
            'message' => 'hello receptionist',
            'role'=>'receptionist'
        ]);
    } else {
        return response()->json([
            'message' => 'User not authenticated with admin or frontuser guard',
        ], 401);
    }
});



//Update Profile Admin

Route::patch('admin/update',[UpdateAuthController::class , 'update']);


Route::get('admin/users',[UsersController::class , 'AllUsers']);
