<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceProduct;
use App\Product;
use App\Task;
use App\Milestone;

class InvoiceProductsController extends Controller
{
    public function create(Invoice $invoice)
    {
        if(\Auth::user()->can('create invoice product'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $products   = Product::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $milestones = Milestone::where('project_id', $invoice->project_id)->get();
                $tasks      = Task::where('project_id', $invoice->project_id)->get();

                return view('invoices.product', compact('invoice', 'milestones', 'tasks'));
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
        if(\Auth::user()->can('create invoice product'))
        {
            if($invoice->getTotal() == 0.0)
            {
                Invoice::change_status($invoice->id, 1);
            }

            if($invoice->created_by == \Auth::user()->creatorId())
            {
                if($request->type == 'milestone')
                {
                    $validator = \Validator::make(
                        $request->all(), [
                            'task_id' => 'required_without:milestone_id',
                            'milestone_id' => 'required_without:task_id',
                                       ]
                    );
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('invoices.show', $invoice->id)->with('error', __('Please select milestone or task.'));

                    }

                    $task      = Task::find($request->task_id);
                    $milestone = Milestone::find($request->milestone_id);
                    $item      = (!empty($task->title)?$task->title:'') . '-' . (!empty($milestone->title)?$milestone->title:'');
                    $price     = $request->price;
                }
                else
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                           'title' => 'required',
                                           'price' => 'required',
                                       ]
                    );
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('invoices.show', $invoice->id)->with('error', __('title and price filed are required'));

                    }

                    $item      = $request->title;
                    $price     = $request->price;
                }


                InvoiceProduct::create(
                    [
                        'invoice_id' => $invoice->id,
                        'item' => $item,
                        'price' => $price,
                        'type' => $request->type,
                    ]
                );

                if($invoice->getTotal() > 0.0 || $invoice->getDue() < 0.0)
                {
                    Invoice::change_status($invoice->id, 2);
                }

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully added.'));
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

    public function edit(Invoice $invoice, InvoiceProduct $product)
    {
        if(\Auth::user()->can('edit invoice product'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $products = Product::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('invoices.product', compact('invoice', 'products', 'product'));
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

    public function update(Invoice $invoice, InvoiceProduct $product, Request $request)
    {
        if(\Auth::user()->can('edit invoice product'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'product_id' => 'required',
                                       'quantity' => 'required|numeric|min:1',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.show', $invoice->id)->with('error', $messages->first());
                }
                $invoiceProduct              = InvoiceProduct::find($product->id);
                $invoiceProduct->product_id  = $product->id;
                $invoiceProduct->price       = $product->price;
                $invoiceProduct->quantity    = $request->quantity;
                $invoiceProduct->description = $request->description;
                $invoiceProduct->save();

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully updated.'));
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

    public function delete(Request $request, Invoice $invoice, InvoiceProduct $product)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete invoice product'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $product->delete();
                if($invoice->getDue() <= 0.0)
                {
                    Invoice::change_status($invoice->id, 3);
                }

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully deleted.'));
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
