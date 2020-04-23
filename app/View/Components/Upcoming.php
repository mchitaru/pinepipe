<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Upcoming extends Component
{
    public $title;
    public $tasks;
    public $events;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $tasks, $events)
    {
        $this->title = $title;
        $this->tasks = $tasks;
        $this->events = $events;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.upcoming');
    }
}
