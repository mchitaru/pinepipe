<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Invoices extends Component
{
    public $icon;
    public $text;
    public $items;

    public function render()
    {
        return view('livewire.invoices');
    }
}
