<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        if ($request->filled('package_id')) {
            $notification = new Notification();
            $notification->title = $request->input('title');
            $notification->body = $request->input('body');
            $notification->save();

            $userIDs = OrderItem::query()
                ->where('package_id', $request->input('package_id'))
                ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                ->pluck('user_id');

            if (count($userIDs) > 0) {
                foreach ($userIDs as $userID) {
                    $userNotification = new UserNotification();
                    $userNotification->notification_id = $notification->id;
                    $userNotification->user_id = $userID;
                    $userNotification->save();
                }
            }

            return back()->with('success', 'Notification successfully send');
        }
    }
}
