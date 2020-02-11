<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Expense;
use App\ExpenseCategory;
use App\Invoice;
use App\InvoicePayment;
use App\InvoiceProduct;
use App\Milestone;
use App\PaymentType;
use App\Products;
use App\Task;
use App\Tax;
use App\User;
use App\Product;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InvoicesController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage invoice') || 
           \Auth::user()->type == 'client')
        {
            clock()->startEvent('InvoicesController', "Load invoices");

            if(\Auth::user()->type == 'client')
            {
                $invoices = Invoice::with('project')
                            ->whereHas('project', function ($query)
                            {
                                $query->whereHas('client', function ($query) 
                                {
                                    $query->where('id', \Auth::user()->id);                
                                });
                            })
                            ->where('created_by', '=', \Auth::user()->creatorId())
                            ->paginate(25, ['*'], 'invoice-page');
            }
            else 
            {
                
                if(\Auth::user()->can('manage invoice'))
                {
                    $invoices = Invoice::with('project')
                                ->where('created_by', '=', \Auth::user()->creatorId())
                                ->paginate(25, ['*'], 'invoice-page');
                }                
            }

            clock()->endEvent('InvoicesController');

            return view('invoices.page', compact('invoices'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create($project_id)
    {

        if(\Auth::user()->can('create invoice'))
        {
            $taxes    = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

            return view('invoices.create', compact('projects', 'project_id', 'taxes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create invoice'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'project_id' => 'required',
                                   'issue_date' => 'required',
                                   'due_date' => 'required'
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return Redirect::to(URL::previous() . "#invoices")->with('error', $messages->first());
            }

            $last_invoice = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();

            $invoice             = new Invoice();
            $invoice->invoice_id = $last_invoice?($last_invoice->id + 1):1;
            $invoice->project_id = $request->project_id;
            $invoice->status     = 0;
            $invoice->issue_date = $request->issue_date;
            $invoice->due_date   = $request->due_date;
            $invoice->discount   = 0;
            $invoice->tax_id     = $request->tax_id;
            $invoice->terms      = $request->terms;
            $invoice->created_by = \Auth::user()->creatorId();
            $invoice->save();

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $request->project_id,
                    'log_type' => 'Create Invoice',
                    'remark' => sprintf(__('%s Create new invoice "%s"'), \Auth::user()->name, \Auth::user()->invoiceNumberFormat($invoice->invoice_id)),
                ]
            );

            return redirect()->route('invoices.show', $invoice->id)->with('success', __('Invoice successfully created.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
        }
    }

    public function show(Invoice $invoice)
    {

        if(\Auth::user()->can('show invoice') || \Auth::user()->type == 'client')
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $settings = \Auth::user()->settings();
                $client   = $invoice->project->client;

                return view('invoices.show', compact('invoice', 'settings', 'client'));
            }
            else
            {
                return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
        }
    }

    public function edit(Invoice $invoice)
    {
        if(\Auth::user()->can('edit invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $taxes    = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

                return view('invoices.edit', compact('invoice', 'projects', 'taxes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {
        if(\Auth::user()->can('edit invoice'))
        {

            if($invoice->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'project_id' => 'required',
                                       'issue_date' => 'required',
                                       'due_date' => 'required',
                                       'discount' => 'required|min:0',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return Redirect::to(URL::previous() . "#invoices")->with('error', $messages->first());
                }

                $invoice->project_id = $request->project_id;
                $invoice->issue_date = $request->issue_date;
                $invoice->due_date   = $request->due_date;
                $invoice->tax_id     = $request->tax_id;
                $invoice->terms      = $request->terms;
                $invoice->discount   = $request->discount;
                $invoice->save();

                return Redirect::to(URL::previous() . "#invoices")->with('success', __('Invoice successfully updated.'));
            }
            else
            {
                return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $invoice->delete();
                InvoicePayment::where('invoice_id', '=', $invoice->id)->delete();
                InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();

                return Redirect::to(URL::previous() . "#invoices")->with('success', __('Invoice successfully deleted.'));
            }
            else
            {
                return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous() . "#invoices")->with('error', __('Permission denied.'));
        }
    }


    public function milestoneTask(Request $request)
    {
        if(!empty($request->milestone_id))
        {
            $tasks = Task::where('milestone_id', $request->milestone_id)->get();

            return $tasks;
        }
        else
        {
            $tasks = Task::where('project_id', $request->project_id)->get();

            return $tasks;
        }

    }
}
