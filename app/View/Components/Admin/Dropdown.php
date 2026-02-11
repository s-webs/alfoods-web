<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(
        public string $align = 'right'
    ) {}

    public function render()
    {
        return view('admin.components.dropdown');
    }
}
