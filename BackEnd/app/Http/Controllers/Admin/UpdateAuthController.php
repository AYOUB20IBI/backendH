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
        $id = $request->id;
        $admin = Admin::find($id);
        if ($admin) {
            $data = $request->validated();

            if ($request->hasFile('image_profile')) {
                $image = $data['image_profile'];
                $fileName = time() . '.' . $image->getClientOriginalExtension();

                $image->move('profileimages/', $fileName);
                $admin->image_profile = 'profileimages/' . $fileName;
            }

            $admin->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
            ]);

            return response()->json(['user'=>$admin,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }


    }
}
