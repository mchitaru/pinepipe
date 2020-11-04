<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Upcoming extends Component
{
    public $type;
    public $ready = false;

    public function load()
    {
        $this->ready = true;
    }
        
    public function render()
    {
        $title = '';
        $tasks = [];
        $events = [];    
    
        switch($this->type){
            case 'today':
                $title = __('Today');
            break;
            case 'this week':
                $title = __('This week');
            break;
            case 'next week':
                $title = __('Next week');
            break;
        }

        // if($this->ready){

            switch($this->type){
                case 'today':
                    $tasks = \Auth::user()->getTodayTasks();
                    $events = \Auth::user()->getTodayEvents();
                break;
                case 'this week':
                    $tasks = \Auth::user()->getThisWeekTasks();
                    $events = \Auth::user()->getThisWeekEvents();
                break;
                case 'next week':
                    $tasks = \Auth::user()->getNextWeekTasks();
                    $events = \Auth::user()->getNextWeekEvents();
                break;
            }    
        // }

        return view('livewire.upcoming', compact('title', 'tasks', 'events'));
    }
}
