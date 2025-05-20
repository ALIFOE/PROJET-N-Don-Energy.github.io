<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\AdminNotification;

trait NotifiesAdmins
{
    protected function notifyAdmins(string $title, string $message, ?string $actionUrl = null, ?string $actionText = null, array $data = []): void
    {
        User::where('role', 'admin')->each(function ($admin) use ($title, $message, $actionUrl, $actionText, $data) {
            $admin->notify(new AdminNotification($title, $message, $actionUrl, $actionText, $data));
        });
    }
}
