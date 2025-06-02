<?php

namespace App\Traits;

use Livewire\Component;

trait NotificationMarkable
{
    protected function markNotificationsAsRead($type)
    {
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications()
                ->where('type', $type)
                ->get()
                ->markAsRead();
                  // Émission de l'événement pour rafraîchir les compteurs
            if (class_exists('\Livewire\Livewire')) {
                \Livewire\Livewire::dispatch('refreshNotificationCounts');
            }
        }
    }
}
