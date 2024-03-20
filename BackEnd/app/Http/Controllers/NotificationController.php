<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guest;
use App\Models\Notification;
use App\Models\Receptionist;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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


    public function showReceptionistNotification($receptionistID)
    {
        $receptionist = Receptionist::where('numero_ID', $receptionistID)->first();
        $notifications = Notification::where('notifiable_id', $receptionist->id)
            ->where('notifiable_type', 'App\Models\Receptionist')
            ->orderBy('created_at', 'desc')
            ->get();

        $notificationsCount = Notification::where('notifiable_id', $receptionist->id)
            ->where('notifiable_type', 'App\Models\Receptionist')
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
                        FacadesNotification::send($guest, new AdminNotification($guest->numero_ID,$adminID, $dataValidate['title'], $dataValidate['message']));
                    }
                    return response()->json([
                        'message' => 'Message sent to all guests.'
                    ], 200);
                case 'AllReceptionist':
                    $receptionists = Receptionist::all();
                    foreach ($receptionists as $receptionist) {
                        FacadesNotification::send($receptionist, new AdminNotification($receptionist->numero_ID,$adminID, $dataValidate['title'], $dataValidate['message']));
                    }
                    return response()->json([
                        'message' => 'Message sent to all Receptionist.'
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


    public function createForOneGuest(Request $request, $adminID)
    {
        $dataValidate = $request->validate([
            'guestID' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($adminID) {
            $guest = Guest::where('numero_ID', $request->guestID)
                  ->orWhere('email', $request->guestID)
                  ->first();
            if ($guest) {
                FacadesNotification::send($guest, new AdminNotification($guest->numero_ID,$adminID, $dataValidate['title'], $dataValidate['message']));
                return response()->json([
                    'message' => 'Message sent to all guests.'
                ], 200);
            }

            return response()->json(['message' => "The guest doesn't exist."],  404);
        } else {
            return response()->json([
                'message' => 'Admin ID not found.'
            ], 404);
        }
    }

    public function createForOneReceptionist(Request $request, $adminID)
    {
        $dataValidate = $request->validate([
            'receptionistID' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($adminID) {
            $receptionist = Receptionist::where('numero_ID', $request->receptionistID)
                  ->orWhere('email', $request->receptionistID)
                  ->first();
            if ($receptionist) {
                FacadesNotification::send($receptionist, new AdminNotification($receptionist->numero_ID,$adminID, $dataValidate['title'], $dataValidate['message']));
                return response()->json([
                    'message' => 'Message sent to all guests.'
                ], 200);
            }

            return response()->json(['message' => "The receptionist doesn't exist."],  404);
        } else {
            return response()->json([
                'message' => 'Admin ID not found.'
            ], 404);
        }
    }



    public function readNotification($guest_ID, $notification_id)
    {
        $guest = Guest::where('numero_ID', $guest_ID)->first();

        if (!$guest) {
            return response()->json([
                'message' => 'Guest not found.'
            ], 404);
        }


        $unreadNotifications = $guest->unreadNotifications()->where('id', $notification_id)->first();

        if (!$unreadNotifications) {
            return response()->json([
                'message' => 'Notification not found.'
            ], 404);
        }

        $unreadNotifications->markAsRead();

        $notificationsCount = Notification::where('notifiable_id', $guest->id)
            ->where('notifiable_type', 'App\Models\Guest')
            ->where('read_at', null)
            ->count();
        $notifications = Notification::where('notifiable_id', $guest->id)
            ->where('notifiable_type', 'App\Models\Guest')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => [
                'read' => $unreadNotifications,
                'data'=>$notifications,
                'user' => $guest,
                'notificationsCount'=>$notificationsCount
            ],
            'message'=>'OK'
        ], 200);
    }


    public function adminreadNotification($admin_ID, $notification_id)
    {
        $admin = Admin::where('numero_ID', $admin_ID)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Guest not found.'
            ], 404);
        }


        $unreadNotifications = $admin->unreadNotifications()->where('id', $notification_id)->first();

        if (!$unreadNotifications) {
            return response()->json([
                'message' => 'Notification not found.'
            ], 404);
        }

        $unreadNotifications->markAsRead();

        $notificationsCount = Notification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', 'App\Models\Admin')
            ->where('read_at', null)
            ->count();
        $notifications = Notification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', 'App\Models\Admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => [
                'read' => $unreadNotifications,
                'data'=>$notifications,
                'user' => $admin,
                'notificationsCount'=>$notificationsCount
            ],
            'message'=>'OK'
        ], 200);
    }












    public function destroy(Notification $notification)
    {
        //
    }
}
