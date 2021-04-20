<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Invoices extends Component
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

            $items = \Auth::user()->companyInvoices()
                                        ->where('status', '<', '3')
                                        ->orderBy('due_date', 'ASC')
                                        ->get()
                                        ->filter(function ($invoice, $key) {
                                            return \Auth::user()->can('view', $invoice);
                                        });
        }

        return view('livewire.invoices', compact('items'));
    }
}
