<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Pnl extends Component
{
    public      $icon;
    public      $income;
    public      $expenses;
    protected   $chart;

    public function render()
    {
        return view('livewire.pnl');
    }
}
