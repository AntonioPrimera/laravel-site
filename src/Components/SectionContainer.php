<?php
namespace AntonioPrimera\Site\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SectionContainer extends Component
{
    public function render(): View
    {
        return view('site::section-container');
    }
}
