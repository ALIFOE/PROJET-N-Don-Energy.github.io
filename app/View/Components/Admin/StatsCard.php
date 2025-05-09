<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public function __construct(
        public string $title,
        public $count,
        public string $icon,
        public string $color,
        public string $subtext,
        public $subcount = null
    ) {}

    public function render()
    {
        return view('components.admin.stats-card');
    }
}
