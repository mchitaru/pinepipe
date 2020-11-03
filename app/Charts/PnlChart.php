<?php

declare(strict_types = 1);

namespace App\Charts;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Payment;
use App\Expense;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class PnlChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $payments = \Auth::user()->companyPayments()
                                    ->where('date', '>', Carbon::now()->subMonths(6))
                                    ->orderBy('date', 'asc')
                                    ->get();

        $dbIncome = collect();                                    
        foreach($payments as $p){

            $month = Carbon::parse($p->date)->format('m-Y');
            $invoice = $p->invoice()->first();
            $dbIncome->put($month,  $dbIncome->get($month) + \Helpers::priceConvert($p->amount, 1.0/$invoice->rate));
        }

        $expenses = \Auth::user()->companyExpenses()
                                    ->where('date', '>', Carbon::now()->subMonths(6))
                                    ->orderBy('date', 'asc')
                                    ->get();

        $dbExpenses = collect();
        foreach($expenses as $e){
            
            $month = Carbon::parse($e->date)->format('m-Y');
            $dbExpenses->put($month,  $dbExpenses->get($month) + $e->amount);
        }
            
        $plot = false;
        $labels = array();
        $incomeData = array();
        $expensesData = array();
        $profitData = array();

        $period = CarbonPeriod::create(Carbon::now()->subMonths(6), '1 month', Carbon::now());
    
        foreach ($period as $dt) {

            $month = $dt->format('m-Y');
            
            $income = $dbIncome->get($month);
            $expense = $dbExpenses->get($month);

            $labels[] = $dt->locale(\Auth::user()->locale)->isoFormat("MMM 'YY");

            $incomeData[] = $income;
            $expensesData[] = $expense;
            $profitData[] = $income - $expense;
        }

        return Chartisan::build()
            ->labels($labels)
            ->dataset(__('Income'), $incomeData)
            ->dataset(__('Expenses'), $expensesData)
            ->dataset(__('Profit'), $profitData);
    }
}