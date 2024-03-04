<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\VerifyEmailController;
use Illuminate\Support\Facades\Auth;

//All Controller For Authentification
use App\Http\Controllers\Admin\UpdateAuthController;
use App\Http\Controllers\Guest\UpdateGuestAuthController;
use App\Http\Controllers\Admin\UsersController;

//All Controller For Rooms
use App\Http\Controllers\RoomController;
//All Controller For Room Food
use App\Http\Controllers\RoomFoodController;
//All Controller For Room Type
use App\Http\Controllers\RoomTypeController;
//All Controller For Room status
use App\Http\Controllers\RoomStatusController;


// Routes Auth  login

Route::post('login',[LoginController::class , 'login']);
Route::post('register',[RegisterUserController::class , 'register']);

//Routes Auth with Google
Route::post('google/login',[LoginController::class , 'GoogleLogin']);
Route::post('google/register',[RegisterUserController::class , 'GoogleRegister']);


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
            'role'=>'user',
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

Route::put('admin/update',[UpdateAuthController::class , 'update']);


Route::get('admin/users',[UsersController::class , 'AllUsers']);
Route::get('admin/users/admins',[UsersController::class , 'AllAdmins']);
Route::get('admin/users/guests',[UsersController::class , 'AllGuests']);
Route::get('admin/users/receptionists',[UsersController::class , 'AllReceptionists']);

Route::get('admin/users/show/{numeroID}',[UsersController::class , 'ShowUsers']);

Route::get('admin/users/counters',[UsersController::class , 'CountersUsers']);


Route::put('user/setting/update',[UpdateGuestAuthController::class , 'store']);
Route::post('user/deleted/account/{id}',[UpdateGuestAuthController::class , 'destroy']);
Route::post('user/changePassword/account',[UpdateGuestAuthController::class , 'changePassword']);



//Fetch Rooms and Room Food and Room Type
Route::get('counters',[RoomController::class , 'CounterRooms']);
Route::get('all/rooms',[RoomController::class , 'index']);

//Type
Route::get('all/room/type',[RoomTypeController::class , 'index']);
Route::post('admin/room/type/create',[RoomTypeController::class , 'create']);
Route::delete('admin/room/type/delete/{id}',[RoomTypeController::class , 'destroy']);
Route::get('admin/room/type/show/{id}',[RoomTypeController::class , 'show']);

//Food
Route::get('all/room/food',[RoomFoodController::class , 'index']);
Route::post('admin/room/food/create',[RoomFoodController::class , 'create']);
Route::delete('admin/room/food/delete/{id}',[RoomFoodController::class , 'destroy']);
Route::get('admin/room/food/show/{id}',[RoomFoodController::class , 'show']);

//Status
Route::get('all/room/status',[RoomStatusController::class , 'index']);
Route::post('admin/room/status/create',[RoomStatusController::class , 'create']);
Route::delete('admin/room/status/delete/{id}',[RoomStatusController::class , 'destroy']);
Route::get('admin/room/status/show/{id}',[RoomStatusController::class , 'show']);

Route::get('show/room/{id}',[RoomController::class , 'ShowRoom']);
Route::get('all/rooms/test',[RoomController::class , 'index2']);
