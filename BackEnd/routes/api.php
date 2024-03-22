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
use App\Http\Controllers\Admin\CreateNewAdminController;
use App\Http\Controllers\Admin\CreateNewResiptionistController;
use App\Http\Controllers\Admin\CreateNewGuestController;
//All Controller For Rooms
use App\Http\Controllers\RoomController;
//All Controller For Room Food
use App\Http\Controllers\RoomFoodController;
//All Controller For Room Type
use App\Http\Controllers\RoomTypeController;
//All Controller For Room status
use App\Http\Controllers\RoomStatusController;

//All Controller For Hootel
use App\Http\Controllers\HotelController;
use App\Http\Controllers\NotificationController;
//All Controller For Services
use App\Http\Controllers\ServiceController;


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
Route::post('admin/deleted/account/{id}',[UpdateAuthController::class , 'destroy']);
Route::post('admin/changePassword/account',[UpdateAuthController::class , 'changePassword']);

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
Route::put('admin/room/type/update/{id}',[RoomTypeController::class , 'update']);

//Food
Route::get('all/room/food',[RoomFoodController::class , 'index']);
Route::post('admin/room/food/create',[RoomFoodController::class , 'create']);
Route::delete('admin/room/food/delete/{id}',[RoomFoodController::class , 'destroy']);
Route::get('admin/room/food/show/{id}',[RoomFoodController::class , 'show']);
Route::put('admin/room/food/update/{id}',[RoomFoodController::class , 'update']);

//Status
Route::get('all/room/status',[RoomStatusController::class , 'index']);
Route::post('admin/room/status/create',[RoomStatusController::class , 'create']);
Route::delete('admin/room/status/delete/{id}',[RoomStatusController::class , 'destroy']);
Route::get('admin/room/status/show/{id}',[RoomStatusController::class , 'show']);
Route::put('admin/room/status/update/{id}',[RoomStatusController::class , 'update']);

//Room
Route::get('show/room/{id}',[RoomController::class , 'ShowRoom']);
Route::get('all/rooms/test',[RoomController::class , 'index2']);
Route::post('admin/rooms/create',[RoomController::class , 'create']);
Route::delete('admin/rooms/delete/{id}',[RoomController::class , 'destroy']);
Route::put('admin/rooms/update/{id}',[RoomController::class , 'update']);


//Hotel
Route::get('admin/get/hotel',[HotelController::class , 'index']);
Route::get('admin/get/hotel/show/{id}',[HotelController::class , 'show']);
Route::post('admin/get/hotel/create',[HotelController::class , 'create']);
Route::put('admin/get/hotel/update/{id}',[HotelController::class , 'update']);



//Services
Route::get('admin/get/services',[ServiceController::class , 'index']);
Route::get('admin/get/service/show/{id}',[ServiceController::class , 'show']);
Route::post('admin/get/service/create',[ServiceController::class , 'create']);
Route::delete('admin/get/service/delete/{id}',[ServiceController::class , 'destroy']);
Route::put('admin/get/service/update/{id}',[ServiceController::class , 'update']);




//Notification
Route::get('admin/get/notifications/{adminID}',[NotificationController::class , 'showAdminNotification']);
Route::get('guest/get/notifications/{guestID}',[NotificationController::class , 'showGuestNotification']);
Route::get('receptionist/get/notifications/{receptionistID}',[NotificationController::class , 'showReceptionistNotification']);
Route::get('receptionist/get/rooms/counters',[RoomController::class , 'CounterRooms']);
Route::post('admin/notifications/create/{adminID}',[NotificationController::class , 'create']);


Route::post('admin/notifications/create/forOneGuest/{adminID}',[NotificationController::class , 'createForOneGuest']);

Route::post('admin/notifications/create/forOneReceptionist/{adminID}',[NotificationController::class , 'createForOneReceptionist']);

Route::get('admin/notifications/read/{guest_ID}/{notification_id}', [NotificationController::class, 'readNotification']);
Route::get('admin/notifications/read/admin/{admin_ID}/{notification_id}', [NotificationController::class, 'adminreadNotification']);


//Admin : Gestion Users
Route::post('admin/user/guest/create',[CreateNewGuestController::class , 'create']);

Route::post('admin/user/receptionist/create',[CreateNewResiptionistController::class , 'create']);

Route::post('admin/user/admin/create',[CreateNewAdminController::class , 'create']);

Route::put('admin/user/guest/update/{numero_id}/{numero_id_Admin}',[CreateNewGuestController::class , 'update']);

Route::put('admin/user/receptionist/update/{numero_id}/{numero_id_Admin}',[CreateNewResiptionistController::class , 'update']);

Route::put('admin/user/admin/update/{numero_id}/{numero_id_Admin}',[CreateNewAdminController::class , 'update']);
