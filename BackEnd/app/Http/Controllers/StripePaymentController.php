<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guest;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Notification;

class StripePaymentController extends Controller
{
    public function makePayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $validatedData = $request->validate([
            'room_number' => 'required|string',
            'price' => 'required|numeric',
            'room_type' => 'required|string',
            'room_food' => 'required|string',
            'bed' => 'required',
            'bathroom' => 'required',
            'ability' => 'required',
            'date' => 'required',
            'time' => 'required|date_format:H:i:s',
            'description'=>'required'
        ]);

        if ($validatedData) {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Room '.$validatedData['room_type'].' '.$validatedData['room_number'],
                            'description' => $validatedData['description'],
                        ],
                        'unit_amount' => $validatedData['price'] * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => env('FRONTEND_URL') . '/',
                'cancel_url' => env('FRONTEND_URL') . '/show/room/checkout/'.$validatedData['room_number'],
            ]);


            $guest = Guest::find(13);

            $admins = Admin::all();

            $title = "Payment Successful";
            $message = "A user Payment. Guest ID: " . $guest->numero_ID;
            // Send notifications to all admins
            foreach ($admins as $admin) {
                Notification::send($admin, new AdminNotification($guest->numero_ID, $guest->numero_ID, $title, $message));
            }

            return response()->json(['id' => $checkout_session->id]);
        }
    }

    public function payment_Successs()
    {
        return response()->json([
            'message'=>'success'
        ], 200);
    }

    public function payment_Cancel()
    {
        return response()->json([
            'message'=>'error'
        ], 200);
    }
}






