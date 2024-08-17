<?php
namespace AntonioPrimera\Site\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Nav extends Component
{

    public function __construct(public iterable $items)
    {
    }

    public function render(): View
    {
        return view('site::nav');
    }
}
