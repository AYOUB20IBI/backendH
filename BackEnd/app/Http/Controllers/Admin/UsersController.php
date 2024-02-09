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

    public function create()
    {

    }
}
