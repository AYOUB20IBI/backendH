<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function  index()
    {
        $booking = Booking::with('RoomNumber','guestNumber')->get();
        if (!$booking) {
            return response()->json(['message' => 'Data not found.'], 404);
        }

        return response()->json([
            'bookings'=> $booking,
        ],200);
    }

    public function searchAvailableRooms(Request $request)
    {
        $checkInDate = $request->input('check_in_date');
        $checkOutDate = $request->input('check_out_date');

        $availableRooms = Room::with('roomType','roomFood','roomStatuse')
            ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_number')
            ->whereNull('bookings.id')
            ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                $query->whereNotBetween('bookings.check_in_date', [$checkInDate, $checkOutDate])
                      ->whereNotBetween('bookings.check_out_date', [$checkInDate, $checkOutDate]);
            })
            ->select('rooms.*')
            ->get();

        return response()->json(['rooms'=>$availableRooms],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
