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
            $data = $request->validate([
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,gif'],
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'date_of_birth' => 'required|date',
                'phone_number' => 'required|string',
                'email' => 'nullable',
            ]);


            if ($request->image) {
                $image = $data['image'];
                $fileName = time() . '.' . $image->getClientOriginalExtension();

                $image->move('ProfileImages/', $fileName);
                $admin->image_profile = 'ProfileImages/' . $fileName;
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

            return response()->json(['user'=>$admin,'image'=>$request->image_profile,'message' => 'Profile updated successfully'],200);

        } else {
            return response()->json(['message' => 'Admin not authenticated'], 401);
        }
    }

    public function destroy( $id)
    {
        $admin = Admin::find($id);

        // dd($guest);
        if (!$admin) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }else{
            $admin->delete();
            return response()->json([
                "message" => "Successfully deleted !"
            ],200);
        }
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
            'password' => 'required|string|confirmed',
        ]);

        $admin = Admin::where('id', $data['id'])->first();

        if($admin){
            $admin->update([
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                "message" => "Password Changed Successfully!"
            ],200);
        }else{
            return response()->json([
                'message' => 'not found'
            ], 404);
        }
    }
}
