<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Expense;
use App\Category;
use App\Invoice;
use App\Payment;
use App\InvoiceItem;
use App\Milestone;
use App\Products;
use App\Project;
use App\Task;
use App\Tax;
use App\Timesheet;
use App\User;
use App\Currency;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Http\Requests\InvoiceDestroyRequest;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', 'App\Invoice');

        if (!$request->ajax())
        {
            return view('invoices.page');
        }

        clock()->startEvent('InvoicesController', "Load invoices");

        if(empty($request['tag']) || $request['tag'] == 'unpaid'){
            $status = array(array_search('pending', Invoice::$status),
                            array_search('outstanding', Invoice::$status),
                            array_search('partial payment', Invoice::$status));
        }elseif($request['tag'] == 'paid'){
            $status = array(array_search($request['tag'], Invoice::$status));
        }else{
            $status = array_keys(Invoice::$status);
        }

        if(\Auth::user()->can('viewAny', 'App\Invoice'))
        {
            $invoices = \Auth::user()->companyInvoices()
                        ->with('client', 'project')
                        ->whereIn('status', $status)
                        ->where(function ($query) use ($request) {

                            $query->where('number', 'like', '%'.$request['filter'].'%')
                            ->orWhereHas('project', function ($query) use($request) {
                                $query->where('name','like','%'.$request['filter'].'%');
                            })
                            ->orWhereHas('client', function ($query) use($request) {
                                $query->where('name','like','%'.$request['filter'].'%');
                            });
                        })
                        ->orderBy($request['sort']?$request['sort']:'due_date', $request['dir']?$request['dir']:'asc')
                        ->paginate(25, ['*'], 'invoice-page');
        }

        clock()->endEvent('InvoicesController');

        return view('invoices.index', ['invoices' => $invoices])->render();
    }

    public function create(Request $request)
    {
        Gate::authorize('create', 'App\Invoice');

        $client_id = $request['client_id'];

        $currency = $request->old('currency') ? $request->old('currency') :
                                                (isset($request['currency']) ? $request['currency'] : \Auth::user()->getCurrency());
        $rate = (isset($request['rate']) && !empty($request['rate'])) ? $request['rate'] : 1.0;

        $issue_date = $request->issue_date?$request->issue_date:date('Y-m-d');
        $due_date = $request->due_date?$request->due_date:date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d"))));

        $project_id = $request['project_id'];

        $taxes    = Tax::get()
                            ->pluck('name', 'id');

        $taxPayer = \Auth::user()->isTaxPayer();
        $tax_id = $taxPayer ? 1 : null;

        $clients = \Auth::user()->companyClients()
                                ->where('archived', 0)
                                ->orderBy('name', 'asc')
                                ->get()
                                ->pluck('name', 'id');

        if($client_id){

            $projects  = Project::where('client_id', '=', $client_id)
                                    ->get()
                                    ->pluck('name', 'id');
        }else{

            $projects = \Auth::user()->companyUserProjects()
                            ->where('archived', '0')
                            ->pluck('projects.name', 'projects.id');
        }

        $locales = ['en' => 'English', 'ro' => 'Română'];
        $locale = isset($request['locale'])?$request['locale']:\Auth::user()->locale;

        $currencies = Currency::get()->pluck('code', 'code');

        if($request->increment){

            $increment = $request->increment;
        }else{
            $lastInvoice = Invoice::latest()->first();
            $increment = $lastInvoice ? $lastInvoice->increment + 1 : 1;
        }

        return view('invoices.create', compact('clients', 'client_id', 'projects', 'project_id', 'taxes', 'tax_id', 'locales', 'locale', 'currencies', 'currency', 'rate', 'issue_date', 'due_date', 'increment'));
    }

    public function store(InvoiceStoreRequest $request)
    {
        Gate::authorize('create', 'App\Invoice');

        $post = $request->validated();

        $invoice = Invoice::createInvoice($post);

        $request->session()->flash('success', __('Invoice successfully created.'));

        $url = redirect()->route('invoices.show', $invoice->id)->getTargetUrl();
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);

        $companySettings = \Auth::user()->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        $client = $invoice->client;

        return view('invoices.show', compact('invoice', 'companySettings', 'companyName', 'companyLogo', 'client'));
    }

    public function edit(Request $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);

        $currency = $request->old('currency') ? $request->old('currency') :
                                                (isset($request['currency']) ? $request['currency'] : $invoice->getCurrency());

        $rate = (isset($request['rate']) && !empty($request['rate'])) ? $request['rate'] : ($invoice->rate ? $invoice->rate : 1.0);

        $issue_date = $request->issue_date?$request->issue_date:$invoice->issue_date;
        $due_date = $request->due_date?$request->due_date:$invoice->due_date;

        $taxes    = Tax::get()
                            ->pluck('name', 'id');

        $locales = ['en' => 'English', 'ro' => 'Română'];
        $locale = isset($request['locale'])?$request['locale']:$invoice->getLocale();

        $currencies = Currency::get()->pluck('code', 'code');

        return view('invoices.edit', compact('invoice', 'taxes', 'locales', 'locale', 'currencies', 'currency', 'rate', 'issue_date', 'due_date'));
    }

    public function update(InvoiceUpdateRequest $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);

        $post = $request->validated();

        $invoice->updateInvoice($post);

        $request->session()->flash('success', __('Invoice successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function destroy(InvoiceDestroyRequest $request, Invoice $invoice)
    {
        Gate::authorize('delete', $invoice);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $invoice->delete();

        if(URL::previous() == route('invoices.show', $invoice)){

            return Redirect::to(route('invoices.index'))->with('success', __('Invoice successfully deleted.'));
        }

        return Redirect::to(URL::previous())->with('success', __('Invoice successfully deleted.'));
    }

    public function pdf(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);

        $companySettings = \Auth::user()->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        $client   = $invoice->client;

        $pdf = \PDF::loadView('invoices.pdf', compact('invoice', 'companySettings', 'companyName', 'companyLogo', 'client'));
        return $pdf->download($invoice->number ? $invoice->number.'.pdf' : Auth::user()->invoiceNumberFormat($invoice->increment).'.pdf');
    }

    public function refresh(Request $request, $invoice_id)
    {
        $userCurrency = \Auth::user()->getCurrency();
        $invoiceCurrency = $request['currency'];

        $userRate = Currency::where('code', $userCurrency)->first()->rate;
        $invoiceRate = Currency::where('code', $invoiceCurrency)->first()->rate;

        $request['rate'] = \Helpers::ceil((float)$userRate/(float)$invoiceRate, 4);

        if($invoice_id){

            $invoice = Invoice::find($invoice_id);

            return $this->edit($request, $invoice);
        }

        return $this->create($request);
    }
}
