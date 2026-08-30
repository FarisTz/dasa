<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::where('id', $id)->where('user_id', Auth::id())->first();
        if ($notification) {
            if (Schema::hasColumn('notifications', 'read')) {
                $notification->read = 1;
                $notification->save();
            }
        }

        return back();
    }

    public function markAllRead()
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'read')) {
            Notification::where('user_id', Auth::id())->update(['read' => 1]);
        }
        return back();
    }
}
