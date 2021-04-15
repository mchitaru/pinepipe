<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Pnl extends Component
{
    public function render()
    {
        $income = 0;
        $expenses = 0;

        $payments = \Auth::user()->companyPayments()
                            ->whereMonth('date', Carbon::now()->format('m'))
                            ->get();

        foreach($payments as $p){

            $invoice = $p->invoice()->first();

            if($invoice->rate){

                $income += \Helpers::priceConvert($p->amount, 1.0/$invoice->rate);
            }
        }

        $exp = \Auth::user()->companyExpenses()
                            ->whereMonth ('date', Carbon::now()->format('m'))
                            ->get();

        foreach($exp as $e){

            $expenses += $e->amount;
        }

        return view('livewire.pnl', compact('income', 'expenses'));
    }
}
