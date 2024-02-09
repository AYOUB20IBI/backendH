<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\Admin\UpdateProfileRequest;

class UpdateAuthController extends Controller
{

    public function update(UpdateProfileRequest $request)
    {
        // dd($request->all());
        $id = $request->id;
        $admin = Admin::find($id);

        if ($admin) {
            // return  response()->json(['image'=>$request->all()], 200);
            $data = $request->validated();

            // if ($request->hasFile('image')) {
            //     $image = $data['image'];
            //     $fileName = time() . '.' . $image->getClientOriginalExtension();

            //     $image->move('ProfileImages/', $fileName);
            //     $admin->image_profile = 'ProfileImages/' . $fileName;
            // }


            // $admin->update([
            //     'first_name' => $data['first_name'],
            //     'last_name' => $data['last_name'],
            //     'gender' => $data['gender'],
            //     'address' => $data['address'],
            //     'date_of_birth' => $data['date_of_birth'],
            //     'phone_number' => $data['phone_number'],
            //     'email' => $data['email'],
            // ]);

            return response()->json(['user'=>$admin,'image'=>$request->image_profile,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }


    }
}
