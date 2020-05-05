<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\Task;
use App\Expense;
use App\Timesheet;
use App\Http\Requests\InvoiceItemStoreRequest;
use App\Http\Requests\InvoiceItemDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InvoiceItemsController extends Controller
{
    public function create(Request $request, Invoice $invoice)
    {
        if($invoice->created_by == \Auth::user()->creatorId())
        {
            $tasks      = Task::doesntHave('invoiceables')
                                    ->where('project_id', $invoice->project_id)
                                    ->get()
                                    ->pluck('title', 'id');

            $timesheets = Timesheet::doesntHave('invoiceables')
                                        ->where('project_id', $invoice->project_id)
                                        ->get()
                                        ->pluck('date', 'id');

            $expenses      = Expense::doesntHave('invoiceables')
                                    ->where('project_id', $invoice->project_id)
                                    ->get()
                                    ->pluck('amount', 'id');

            $price = null;
            if(isSet($request->timesheet_id))
            {
                $timesheet = Timesheet::find($request->timesheet_id);
                $price = ($timesheet->rate * $timesheet->computeTime())/3600.0;

            }else if(isSet($request->expense_id))
            {
                $expense = Expense::find($request->expense_id);
                $price = $expense->amount;
            }

            return view('invoices.item', compact('invoice', 'tasks', 'timesheets', 'expenses', 'price'));
        }
    }

    public function store(InvoiceItemStoreRequest $request, Invoice $invoice)
    {
        $post = $request->validated();
        $post['type'] = $request->type;

        InvoiceItem::createItem($post, $invoice);

        $request->session()->flash('success', __('Item successfully created.'));

        return "<script>window.location.reload()</script>";
    }

    public function delete(InvoiceItemDestroyRequest $request, Invoice $invoice, InvoiceItem $item)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $item->delete();

        $invoice->updateStatus();

        return Redirect::to(URL::previous())->with('success', __('Item successfully deleted'));
    }

    public function refresh(Request $request, Invoice $invoice)
    {
        if($request->type == 'timesheet') {

            $request->task_id = null;
            $request->name = null;
            $request->flashOnly(['timesheet_id']);

        }else if($request->type == 'task') {

            $request->timesheet_id = null;
            $request->name = null;
            $request->flashOnly(['task_id']);

        }else if($request->type == 'expense') {

            $request->timesheet_id = null;
            $request->name = null;
            $request->flashOnly(['expense_id']);

        }else {

            $request->task_id = null;
            $request->timesheet_id = null;
            $request->flashOnly(['name']);
        }

        $request->session()->flash('type', $request->type);

        return $this->create($request, $invoice);
    }
}
