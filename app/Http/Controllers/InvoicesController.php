<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Expense;
use App\ExpenseCategory;
use App\Invoice;
use App\InvoicePayment;
use App\InvoiceItem;
use App\Milestone;
use App\PaymentType;
use App\Products;
use App\Task;
use App\Tax;
use App\Timesheet;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\InvoiceDestroyRequest;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::user()->can('manage invoice') ||
           \Auth::user()->type == 'client')
        {
            clock()->startEvent('InvoicesController', "Load invoices");

            if($request['tag']){
                $status = array(array_search($request['tag'], Invoice::$status));
            }else{
                $status = array(array_search('pending', Invoice::$status));
            }

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
                            ->whereIn('status', $status)
                            ->where(function ($query) use ($request) {
                                $query->where('id', $request['filter'])
                                    ->orWhereHas('project', function ($query) use($request) {
                                        $query->where('name','like','%'.$request['filter'].'%');
                                    });
                            })
                            ->paginate(25, ['*'], 'invoice-page');
            }
            else
            {

                if(\Auth::user()->can('manage invoice'))
                {
                    $invoices = Invoice::with('project')
                                ->where('created_by', '=', \Auth::user()->creatorId())
                                ->whereIn('status', $status)
                                ->where(function ($query) use ($request) {
                                    $query->where('id', $request['filter'])
                                        ->orWhereHas('project', function ($query) use($request) {
                                            $query->where('name','like','%'.$request['filter'].'%');
                                        });
                                })
                                ->paginate(25, ['*'], 'invoice-page');
                }
            }

            clock()->endEvent('InvoicesController');

            if ($request->ajax())
            {
                return view('invoices.index', ['invoices' => $invoices])->render();
            }

            return view('invoices.page', compact('invoices'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create invoice'))
        {
            $project_id = $request['project_id'];

            $taxes    = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

            return view('invoices.create', compact('projects', 'project_id', 'taxes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(InvoiceStoreRequest $request)
    {
        $post = $request->validated();

        $invoice = Invoice::createInvoice($post);

        $request->session()->flash('success', __('Invoice successfully created.'));

        $url = redirect()->route('invoices.show', $invoice->id)->getTargetUrl();
        return "<script>window.location='{$url}'</script>";
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

    public function update(InvoiceUpdateRequest $request, Invoice $invoice)
    {
        $post = $request->validated();

        $invoice->updateInvoice($post);

        $request->session()->flash('success', __('Invoice successfully updated.'));

        return "<script>window.location.reload()</script>";
    }

    public function destroy(InvoiceDestroyRequest $request, Invoice $invoice)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $invoice->detachInvoice();

        $invoice->delete();

        return Redirect::to(URL::previous() . "#invoices")->with('success', __('Invoice successfully deleted.'));
    }

}
