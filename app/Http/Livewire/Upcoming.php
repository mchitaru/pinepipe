<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Upcoming extends Component
{
    public $title;
    public $tasks;
    public $events;    

    public function render()
    {
        return view('livewire.upcoming');
    }
}
