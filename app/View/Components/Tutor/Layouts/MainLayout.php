<?php

namespace App\View\Components\Tutor\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

class MainLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('tutor.layouts.main');
    }
}
