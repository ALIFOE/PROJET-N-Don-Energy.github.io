<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Label extends Component
{
    public function __construct(public string $value = '')
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.label');
    }
}
