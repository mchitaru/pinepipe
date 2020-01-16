<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Expense;
use App\ExpensesCategory;
use App\Invoice;
use App\InvoicePayment;
use App\InvoiceProduct;
use App\Milestone;
use App\Payment;
use App\Products;
use App\Task;
use App\Tax;
use App\User;
use Auth;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage invoice') || 
           \Auth::user()->can('manage expense') || 
           \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'client')
            {
                $invoices = Invoice::select(['invoices.*'])->join('projects', 'projects.id', '=', 'invoices.project_id')->where('projects.client', '=', \Auth::user()->id)->where('invoices.created_by', '=', \Auth::user()->creatorId())->get();
                $expenses = Expense::select('expenses.*','projects.name')->join('projects','projects.id','=','expenses.project')->where('projects.client','=',\Auth::user()->id)->where('expenses.created_by', '=', \Auth::user()->creatorId())->get();
            }
            else {
                
                if(\Auth::user()->can('manage invoice'))
                {
                    $invoices = Invoice::where('created_by', '=', \Auth::user()->creatorId())->get();
                }
                
                if(\Auth::user()->can('manage expense'))
                {
                    $expenses = Expense::where('created_by', '=', \Auth::user()->creatorId())->get();
                }
            }

            return view('invoices.index', compact('invoices', 'expenses'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
