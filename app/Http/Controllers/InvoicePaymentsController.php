<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Invoice;
use App\Category;

class InvoicePaymentsController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage invoice payment') || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'client')
            {
                $payments = Payment::select(['payments.*'])->join('invoices', 'payments.invoice_id', '=', 'invoices.id')->join('projects', 'invoices.project_id', '=', 'projects.id')->where('projects.client_id', '=', \Auth::user()->client_id)->where(
                    'invoices.created_by', '=', \Auth::user()->creatorId()
                )->get();
            }
            else
            {
                $payments = Payment::select(['payments.*'])->join('invoices', 'payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('invoices.all-payments', compact('payments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create(Invoice $invoice)
    {
        if(\Auth::user()->can('create invoice payment'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $categories = Category::whereIn('created_by', [0, \Auth::user()->creatorId()])
                                        ->where('class', Payment::class)
                                        ->get()->pluck('name', 'name');

                return view('invoices.payment', compact('invoice', 'categories'));
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

    public function store(Request $request, Invoice $invoice)
    {
        if(\Auth::user()->can('create invoice payment'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'amount' => 'required|numeric|min:1',
                                       'date' => 'required',
                                       'category' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.show', $invoice->id)->with('error', $messages->first());
                }

                $latest_payment = Payment::select('payments.*')->join('invoices', 'payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->latest()->first();

                $payment = Payment::create(
                    [
                        'transaction_id' => $latest_payment?($latest_payment->transaction_id + 1):1,
                        'invoice_id' => $invoice->id,
                        'amount' => $request->amount,
                        'date' => $request->date,
                        'notes' => $request->notes,
                    ]
                );

                $payment->syncCategory($request['category'], Payment::class);

                $invoice->updateStatus();

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Payment successfully added.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
