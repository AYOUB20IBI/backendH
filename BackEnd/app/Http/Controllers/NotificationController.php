<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guest;
use App\Models\Notification;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification as  FacadesNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showAdminNotification($adminID)
    {
        $admin = Admin::where('numero_ID', $adminID)->first();
        $notifications = Notification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', 'App\Models\Admin')
            ->orderBy('created_at', 'desc')
            ->get();

        $notificationsCount = Notification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', 'App\Models\Admin')
            ->where('read_at', null)
            ->count();

        return response()->json([
            'notifications' => [
                'data' => $notifications,
                'notificationsCount'=>$notificationsCount
            ]
        ], 200);
    }

    public function showGuestNotification($guestID)
    {
        $guest = Guest::where('numero_ID', $guestID)->first();
        $notifications = Notification::where('notifiable_id', $guest->id)
            ->where('notifiable_type', 'App\Models\Guest')
            ->orderBy('created_at', 'desc')
            ->get();

        $notificationsCount = Notification::where('notifiable_id', $guest->id)
            ->where('notifiable_type', 'App\Models\Guest')
            ->where('read_at', null)
            ->count();

        return response()->json([
            'notifications' => [
                'data' => $notifications,
                'notificationsCount'=>$notificationsCount
            ]
        ], 200);
    }


    public function create(Request $request, $adminID)
    {
        $dataValidate = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($adminID) {
            switch ($dataValidate['type']) {
                case 'AllGuest':
                    $guests = Guest::all();
                    foreach ($guests as $guest) {
                        FacadesNotification::send($guest, new AdminNotification($adminID, $dataValidate['title'], $dataValidate['message']));
                    }
                    return response()->json([
                        'message' => 'Message sent to all guests.'
                    ], 200);
                default:
                    return response()->json([
                        'message' => 'Invalid notification type.'
                    ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Admin ID not found.'
            ], 404);
        }
    }



    public function destroy(Notification $notification)
    {
        //
    }
}
