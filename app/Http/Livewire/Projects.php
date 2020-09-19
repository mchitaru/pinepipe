<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Projects extends Component
{
    public $icon;
    public $text;
    public $items;

    public function render()
    {
        return view('livewire.projects');
    }
}
