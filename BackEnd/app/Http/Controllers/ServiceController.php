<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        if (!$services) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'services'=> $services,
        ],200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:services,name',
            'iconHtml'=>'required|string',
            'image1' => ['required', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image2' => ['required', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'description' => 'required|string',
        ]);


        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $fileName1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move('ServicesImages/', $fileName1);
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $fileName2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move('ServicesImages/', $fileName2);
        }


        $services = Service::create([
            'name' => $validatedData['name'],
            'iconHtml' => $validatedData['iconHtml'],
            'image1' =>  'ServicesImages/' . $fileName1,
            'image2' => 'ServicesImages/' . $fileName2,
            'description' => $validatedData['description'],
        ]);

        return response()->json([
            'message' => 'Services created successfully',
            'services' => $services
        ]);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'service not found.'], 404);
        }

        return response()->json([
            'services' => $service
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $service = Service::find($id);

        $validatedData = $request->validate([
            'name' => 'required|string|unique:services,name',
            'iconHtml'=>'required|string',
            'image1' => ['nullable', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'image2' => ['nullable', 'image','mimes:png,jpg,jpeg,svg|max:10240'],
            'description' => 'required|string',
        ]);

        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        $dataUpdate =[
            'name' => $validatedData['name'],
            'iconHtml'=>$validatedData['iconHtml'],
            'description' => $validatedData['description'],
        ];

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $fileName1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move('ServicesImages/', $fileName1);
            $dataToUpdate['image1'] = 'ServicesImages/' . $fileName1;
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $fileName2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move('ServicesImages/', $fileName2);
            $dataToUpdate['image2'] = 'ServicesImages/' . $fileName2;
        }

        $service->update($dataUpdate);

        return response()->json(['message' => 'Service updated successfully'], 200);
    }


    public function destroy(string $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'service not found.'], 404);
        }

        $service->delete();

        return response()->json(['message' => 'service deleted successfully'], 200);
    }
}
