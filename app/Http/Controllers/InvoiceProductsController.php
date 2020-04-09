<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceProduct;
use App\Product;
use App\Task;
use App\Timesheet;
use App\Http\Requests\InvoiceProductStoreRequest;
use App\Http\Requests\InvoiceProductDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InvoiceProductsController extends Controller
{
    public function create(Request $request, Invoice $invoice)
    {
        if($invoice->created_by == \Auth::user()->creatorId())
        {
            $products   = Product::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $tasks      = Task::doesntHave('products')
                                    ->where('project_id', $invoice->project_id)
                                    ->get()
                                    ->pluck('title', 'id');
            $timesheets = Timesheet::doesntHave('products')
                                        ->where('project_id', $invoice->project_id)
                                        ->get()
                                        ->pluck('date', 'id');

            $price = null;
            if(isSet($request->timesheet_id))
            {
                $timesheet = Timesheet::find($request->timesheet_id);
                $price = ($timesheet->rate * $timesheet->computeTime())/3600.0;
            }

            return view('invoices.product', compact('invoice', 'tasks', 'timesheets', 'price'));
        }
    }

    public function store(InvoiceProductStoreRequest $request, Invoice $invoice)
    {
        $post = $request->validated();
        $post['type'] = $request->type;

        InvoiceProduct::createProduct($post, $invoice);

        $request->session()->flash('success', __('Product successfully created.'));

        return "<script>window.location.reload()</script>";
    }

    public function delete(InvoiceProductDestroyRequest $request, Invoice $invoice, InvoiceProduct $product)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $product->detachProduct($invoice);

        $product->delete();

        return Redirect::to(URL::previous())->with('success', __('Product successfully deleted'));
    }

    public function refresh(Request $request, Invoice $invoice)
    {
        if($request->type == 'timesheet') {

            $request->task_id = null;
            $request->title = null;
            $request->flashOnly(['timesheet_id']);

        }else if($request->type == 'task') {

            $request->timesheet_id = null;
            $request->title = null;
            $request->flashOnly(['task_id']);

        }else {

            $request->task_id = null;
            $request->timesheet_id = null;
            $request->flashOnly(['title']);
        }

        $request->session()->flash('type', $request->type);

        return $this->create($request, $invoice);
    }
}
