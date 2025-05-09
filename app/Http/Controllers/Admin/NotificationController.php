<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.notifications.index');
    }

    public function markAsRead($id)
    {
        DatabaseNotification::findOrFail($id)->markAsRead();
        return back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function destroy($id)
    {
        DatabaseNotification::findOrFail($id)->delete();
        return back()->with('success', 'Notification supprimée avec succès');
    }    public function destroyAll()
    {
        $user = auth()->user();
        // Supprimer toutes les notifications lues et non lues
        $user->readNotifications()->delete();
        $user->unreadNotifications()->delete();
        return back()->with('success', 'Toutes les notifications ont été supprimées');
    }
}