<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LogoutForm extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.logout-form');
    }
}
