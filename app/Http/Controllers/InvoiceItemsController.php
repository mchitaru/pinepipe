<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\Task;
use App\Expense;
use App\Timesheet;
use App\Http\Requests\InvoiceItemStoreRequest;
use App\Http\Requests\InvoiceItemUpdateRequest;
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

            $expenses      = Expense::with('category')
                                    ->doesntHave('invoiceables')
                                    ->where('project_id', $invoice->project_id)
                                    ->get()
                                    ->pluck('category.name', 'id');


            foreach($expenses as $key => $value)
            {
                if(empty($value)){

                    $expenses[$key] = __('Uncategorized Expense');
                }
            }

            $price = null;
            if(isSet($request->timesheet_id))
            {
                $timesheet = Timesheet::find($request->timesheet_id);

                if($timesheet) {

                    $price = ($timesheet->rate * $timesheet->computeTime())/3600.0;
                }

            }else if(isSet($request->expense_id))
            {
                $expense = Expense::find($request->expense_id);

                if($expense) {

                    $price = $expense->amount;
                }
            }

            return view('invoices.items.create', compact('invoice', 'tasks', 'timesheets', 'expenses', 'price'));
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

    public function edit(Request $request, Invoice $invoice, InvoiceItem $item)
    {
        if($invoice->created_by == \Auth::user()->creatorId())
        {

            return view('invoices.items.edit', compact('invoice', 'item'));
        }
    }

    public function update(InvoiceItemUpdateRequest $request, Invoice $invoice, InvoiceItem $item)
    {
        $post = $request->validated();

        $item->update($post);

        $request->session()->flash('success', __('Item successfully updated.'));

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

            if($request->timesheet_id) {

                $timesheet = Timesheet::find($request->timesheet_id);

                $request['text'] = (!empty($timesheet->task)?
                                        $timesheet->task->title:
                                        __('Project timesheet (').\Auth::user()->dateFormat($timesheet->date).'|'.$timesheet->formatTime().')');

            }else{

                $request['text'] = null;
            }

            $request->task_id = null;
            $request->expense_id = null;
            $request->flashOnly(['timesheet_id', 'text']);

        }else if($request->type == 'task') {

            if($request->task_id){

                $task = Task::find($request->task_id);

                $request['text'] = $task->title;
            }else{

                $request['text'] = null;
            }

            $request->timesheet_id = null;
            $request->expense_id = null;

            $request->flashOnly(['task_id', 'text']);

        }else if($request->type == 'expense') {

            if($request->expense_id){

                $expense = Expense::find($request->expense_id);

                $request['text'] = $expense->category?$expense->category->name:__('Uncategorized Expense');                
            }else{

                $request['text'] = null;
            }

            $request->task_id = null;
            $request->timesheet_id = null;
            $request->flashOnly(['expense_id', 'text']);

        }else {

            $request->task_id = null;
            $request->timesheet_id = null;
            $request->expense_id = null;
            $request->flashOnly(['text']);
        }

        $request->session()->flash('type', $request->type);

        return $this->create($request, $invoice);
    }
}
