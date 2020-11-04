<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Tasks extends Component
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

            $items = \Auth::user()->tasks()
                                    ->whereHas('stage', function ($query)
                                    {
                                        $query->where('open', 1);
                                    })
                                    ->where(function ($query){
                                        $query->where('priority', 'high')
                                                ->orWhereDate('due_date', '=', Carbon::now());
                                    })
                                    ->orderBy('priority', 'ASC')
                                    ->orderBy('due_date', 'ASC')
                                    ->get();
        }

        return view('livewire.tasks', compact('items'));
    }
}
