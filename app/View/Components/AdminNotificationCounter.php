<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Devis;
use App\Models\FormationInscription;
use App\Models\Order;
use App\Models\DemandeService;
use App\Models\User;

class AdminNotificationCounter extends Component
{
    public $devisCount;
    public $formationsCount;
    public $boutiqueCount;
    public $servicesCount;
    public $usersCount;

    public function __construct()
    {
        $this->devisCount = Devis::where('statut', 'en_attente')->count();        $this->formationsCount = FormationInscription::where('statut', 'en_attente')->count();
        $this->boutiqueCount = Order::where('status', 'pending')->count();
        $this->servicesCount = DemandeService::where('statut', 'en_attente')->count();
        $this->usersCount = User::where('email_verified_at', null)->count();
    }

    public function render()
    {
        return view('components.admin-notification-counter');
    }
}
