<?php

namespace App\Http\Controllers;

use App\Tax;
use Auth;
use Illuminate\Http\Request;

class TaxesController extends Controller
{


    public function index()
    {
        if(\Auth::user()->can('view invoice')) {
            $taxes = Tax::where('created_by','=',\Auth::user()->creatorId())->get();
            return view('taxes.index')->with('taxes', $taxes);
        }else{
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('edit invoice')) {
            return view('taxes.create');
        }else{
            return response()->json(['error'=>__('Permission denied.')],401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('edit invoice')) {

            $validator = \Validator::make($request->all(), [
                'name' => 'required|max:20',
                'rate' => 'required|numeric',
            ]);

            $tax = new Tax();
            $tax->name = $request->name;
            $tax->rate = $request->rate;
            $tax->save();

            return redirect()->route('taxes.index')->with('success',__('Tax rate successfully created.'));
        }else{
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }

    public function show(Tax $tax)
    {
        return redirect()->route('taxes.index');
    }


    public function edit(Tax $tax)
    {
        if(\Auth::user()->can('edit invoice')) {
            if($tax->created_by == \Auth::user()->creatorId()) {
                return view('taxes.edit', compact('tax'));
            }else{
                return response()->json(['error'=>__('Permission denied.')],401);
            }
        }else{
            return response()->json(['error'=>__('Permission denied.')],401);
        }
    }


    public function update(Request $request, Tax $tax)
    {
        if(\Auth::user()->can('edit invoice')) {
            if($tax->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make($request->all(), [
                    'name' => 'required|max:20',
                    'rate' => 'required|numeric',
                ]);

                $tax->name = $request->name;
                $tax->rate = $request->rate;
                $tax->save();
                return redirect()->route('taxes.index')->with('success',__('Tax rate successfully updated.'));
            }else{
                return redirect()->back()->with('error',__('Permission denied.'));
            }
        }else{
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }

    public function destroy(Tax $tax)
    {
        if(\Auth::user()->can('edit invoice')) {
            if($tax->created_by == \Auth::user()->creatorId()) {
                $tax->delete();
                return redirect()->route('taxes.index')->with('success',__('Tax rate successfully deleted.'));
            }else{
                return redirect()->back()->with('error',__('Permission denied.'));
            }
        }else{
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }
}
