<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvoicePayment;
use App\Invoice;
use App\PaymentType;

class InvoicePaymentsController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage invoice payment') || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'client')
            {
                $payments = InvoicePayment::select(['invoice_payments.*'])->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->join('projects', 'invoices.project_id', '=', 'projects.id')->where('projects.client_id', '=', \Auth::user()->id)->where(
                    'invoices.created_by', '=', \Auth::user()->creatorId()
                )->get();
            }
            else
            {
                $payments = InvoicePayment::select(['invoice_payments.*'])->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->get();
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
                $payment_methods = PaymentType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('invoices.payment', compact('invoice', 'payment_methods'));
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
                                       'payment_id' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.show', $invoice->id)->with('error', $messages->first());
                }

                $latest_payment = InvoicePayment::select('invoice_payments.*')->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->latest()->first();

                InvoicePayment::create(
                    [
                        'transaction_id' => $latest_payment?($latest_payment->transaction_id + 1):1,
                        'invoice_id' => $invoice->id,
                        'amount' => $request->amount,
                        'date' => $request->date,
                        'payment_id' => $request->payment_id,
                        'notes' => $request->notes,
                    ]
                );
                if($invoice->getDue() == 0.0)
                {
                    Invoice::change_status($invoice->id, 3);
                }
                else
                {
                    Invoice::change_status($invoice->id, 2);
                }

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
