<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Projects extends Component
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

            $items = \Auth::user()->companyUserProjects()                                    
                                        ->where('archived', '0')
                                        ->orderBy('due_date', 'ASC')
                                        ->get();
        }

        return view('livewire.projects', compact('items'));
    }
}
