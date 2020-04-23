<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Todo extends Component
{
    public $type;
    public $icon;
    public $text;
    public $items;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $icon, $text, $items)
    {
        $this->type = $type;
        $this->icon = $icon;
        $this->text = $text;
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        switch($this->type) {
            case 'tasks':
                return view('components.todo.tasks');
            case 'projects':
                return view('components.todo.projects');
            case 'invoices':
                return view('components.todo.invoices');
            case 'leads':
                return view('components.todo.leads');
        }
    }
}
