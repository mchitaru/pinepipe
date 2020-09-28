<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Invoice;
use App\Category;

use App\Http\Requests\InvoicePaymentStoreRequest;
use App\Http\Requests\InvoicePaymentUpdateRequest;
use App\Http\Requests\InvoicePaymentDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InvoicePaymentsController extends Controller
{
    public function create(Invoice $invoice)
    {
        $categories = Category::where('class', Payment::class)
                                ->get()->pluck('name', 'id');

        return view('invoices.payments.create', compact('invoice', 'categories'));
    }

    public function store(InvoicePaymentStoreRequest $request, Invoice $invoice)
    {
        $post = $request->validated();

        Payment::createPayment($post, $invoice, $post['receipt']);

        $request->session()->flash('success', __('Payment successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function edit(Request $request, Invoice $invoice, Payment $payment)
    {
        $categories = Category::where('class', Payment::class)
                                ->get()->pluck('name', 'id');

        return view('invoices.payments.edit', compact('invoice', 'payment', 'categories'));
    }

    public function update(InvoicePaymentUpdateRequest $request, Invoice $invoice, Payment $payment)
    {
        $post = $request->validated();

        $payment->updatePayment($post);

        $request->session()->flash('success', __('Payment successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function delete(InvoicePaymentDestroyRequest $request, Invoice $invoice, Payment $payment)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $payment->delete();

        $invoice->updateStatus();

        return Redirect::to(URL::previous())->with('success', __('Payment successfully deleted'));
    }
}
