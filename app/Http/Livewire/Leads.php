<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Leads extends Component
{
    public $ready = false;

    public function load()
    {
        $this->ready = true;
    }

    public function render()
    {
        $items = [];

        if($this->ready){

            $items = \Auth::user()->leads()
                                    ->whereHas('stage', function ($query)
                                    {
                                        $query->where('open', 1);
                                    })
                                    ->whereDate('updated_at', '<', Carbon::now()->subDays(7))
                                    ->orderBy('order', 'ASC')
                                    ->get();
        }

        return view('livewire.leads', compact('items'));
    }
}
