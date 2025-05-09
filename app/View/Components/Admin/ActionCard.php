<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class ActionCard extends Component
{
    public function __construct(
        public string $title,
        public string $link,
        public string $icon,
        public string $color,
        public string $description
    ) {}

    public function render()
    {
        return view('components.admin.action-card');
    }
}
