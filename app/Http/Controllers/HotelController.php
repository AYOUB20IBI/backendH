<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    public function index()
    {
        $hotel = Hotel::all();
        if (!$hotel) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'hotel'=> $hotel,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }


    public function show($reference_ID)
    {
        $hotel = Hotel::where('reference_ID', $reference_ID)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }

        return response()->json([
            'hotel' => $hotel
        ], 200);
    }

    public function update(Request $request, $reference_ID)
    {
        $hotel = Hotel::where('reference_ID', $reference_ID)->first();

        if (!$hotel) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }

        $dataValidate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'url_location' => 'nullable|url',
            'star_rating' => 'required|string|between:1,5',
            'image_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:10240', 'dimensions:width=502,height=251'],
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
        ]);




        $dataUpdated = [
            'name' => $dataValidate['name'],
            'email' => $dataValidate['email'],
            'phone_number' => $dataValidate['phone_number'],
            'description' => $dataValidate['description'],
            'address' => $dataValidate['address'],
            'city' => $dataValidate['city'],
            'country' => $dataValidate['country'],
            'zip_code' => $dataValidate['zip_code'],
            'url_location' => $dataValidate['url_location'],
            'star_rating' => $dataValidate['star_rating'],
            'facebook' => $dataValidate['facebook'],
            'instagram' => $dataValidate['instagram'],
            'youtube' => $dataValidate['youtube'],
            'twitter' => $dataValidate['twitter'],
            'telegram' => $dataValidate['telegram'],
        ];

        if ($request->hasFile('image_logo')) {
            $image = $request->file('image_logo');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('HotelLogoImages/', $fileName);
            $dataUpdated['image_logo'] = 'HotelLogoImages/' . $fileName;
        }

        $hotel->update($dataUpdated);

        return response()->json([
            'message' => 'Hotel updated successfully',
            'hotel'=>$hotel
        ], 200);
    }


    public function destroy(Hotel $hotel)
    {
        //
    }
}
