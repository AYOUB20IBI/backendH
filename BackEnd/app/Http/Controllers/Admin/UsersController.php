<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guest;
use App\Models\Admin;
use App\Models\Receptionist;

class UsersController extends Controller
{
    public function AllUsers ()
    {
        $users = Guest::all();
        $admin = Admin::all();
        $receptionist = Receptionist::all();
        return response()->json([
            'users'=>[
                'admins'=>$admin,
                'guests'=>$users,
                'receptionist'=>$receptionist
            ]
        ]);
    }
    public function AllGuests ()
    {
        $users = Guest::all();
        return response()->json([
            'guests'=>$users
        ]);
    }
    public function AllAdmins ()
    {
        $admin = Admin::all();
        return response()->json([
            'admins'=>$admin,
        ]);
    }
    public function AllReceptionists ()
    {
        $receptionist = Receptionist::all();
        return response()->json([
            'receptionist'=>$receptionist
        ]);
    }

    public function CountersUsers()
    {
        $counterGuests=Guest::count();
        $counterAdmins=Admin::count();
        $counterReceptionists=Receptionist::count();
        return response()->json([
            'counterGuests'=> $counterGuests,
            'counterAdmins'=> $counterAdmins,
            'counterReceptionists'=> $counterReceptionists
        ],200);
    }


    public function  ShowUsers($numeroID)
    {
        $guest = Guest::where('numero_ID', $numeroID)->first();
        $admin = Admin::where('numero_ID', $numeroID)->first();
        $receptionist = Receptionist::where('numero_ID', $numeroID)->first();


        if($guest){
            return response()->json([
                'user'=> $guest,
            ],200);
        }
        if($admin){
            return response()->json([
                'user'=> $admin,
            ],200);
        }
        if($receptionist){
            return response()->json([
                'user'=> $receptionist,
            ],200);
        }
    }
}
