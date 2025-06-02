<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Devis;
use App\Models\FormationInscription;
use App\Models\Order;
use App\Models\DemandeService;
use App\Models\User;

class AdminNotificationCounter extends Component
{
    public $count = 0;
    public $type;

    protected $listeners = ['refreshNotificationCounts' => 'updateCounts'];

    public function mount($count = 0)
    {
        $this->count = $count;
    }

    public function updateCounts()
    {
        // La logique de mise à jour sera spécifique au type
        if ($this->count > 0) {
            $this->count = $this->getCountByType();
        }
    }

    private function getCountByType()
    {
        return match ($this->type) {
            'devis' => Devis::where('statut', 'en_attente')->count(),
            'formations' => FormationInscription::where('statut', 'en_attente')->count(),
            'boutique' => Order::where('status', 'pending')->count(),
            'services' => DemandeService::where('statut', 'en_attente')->count(),
            'users' => User::where('email_verified_at', null)->count(),
            default => 0,
        };
    }

    public function render()
    {
        return view('livewire.admin-notification-counter');
    }
}
