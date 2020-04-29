<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ExpensesController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::user()->can('manage expense') ||
           \Auth::user()->type == 'client')
        {
            clock()->startEvent('ExpensesController', "Load expenses");

            if(\Auth::user()->can('manage expense'))
            {
                $expenses = Expense::expensesByUserType()
                            ->where('created_by', '=', \Auth::user()->creatorId())
                            ->where(function ($query) use ($request) {
                                $query->whereHas('user', function ($query) use($request) {

                                    $query->where('name','like','%'.$request['filter'].'%');
                                })
                                ->orWhereHas('project', function ($query) use($request) {

                                    $query->where('name','like','%'.$request['filter'].'%');
                                });
                            })
                            ->paginate(25, ['*'], 'invoice-page');
            }

            clock()->endEvent('ExpensesController');

            if ($request->ajax())
            {
                return view('expenses.index', ['expenses' => $expenses])->render();
            }

            return view('expenses.page', compact('expenses'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create expense'))
        {
            $project_id = $request['project_id'];

            $categories = Category::whereIn('created_by', [0, \Auth::user()->creatorId()])
                                    ->where('class', Expense::class)
                                    ->get()->pluck('name', 'name');

            $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                            ->where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend('(myself)', \Auth::user()->id);

            return view('expenses.create', compact('categories', 'project_id', 'projects', 'owners'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create expense'))
        {

            $rules = [
                'amount' => 'required',
                'date' => 'required',
                'category_id' => 'integer',
                'project_id' => 'integer',
            ];
            if($request->attachment)
            {
                $rules['attachment'] = 'required|max:2048';
            }

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return Redirect::to(URL::previous())->with('error', $messages->first());
            }

            $expense              = new Expense();
            $expense->description = $request->description;
            $expense->amount      = $request->amount;
            $expense->date        = $request->date;
            $expense->project_id  = $request->project_id;

            if(!empty($request->user_id))
            {
                $expense->user_id     = $request->user_id;
            }
            else{

                $expense->user_id     = \Auth::user()->id;
            }

            $expense->created_by  = \Auth::user()->creatorId();
            $expense->save();

            if($request->attachment)
            {
                $imageName = 'expense_' . $expense->id . "_" . $request->attachment->getClientOriginalName();
                $request->attachment->storeAs('public/attachment', $imageName);
                $expense->attachment = $imageName;
                $expense->save();
            }

            $expense->syncCategory($request['category'], Expense::class);

            return Redirect::to(URL::previous())->with('success', __('Expense successfully created.'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }


    public function edit(Expense $expense)
    {
        if(\Auth::user()->can('edit expense'))
        {
            if($expense->created_by == \Auth::user()->creatorId())
            {
                $categories = Category::whereIn('created_by', [0, \Auth::user()->creatorId()])
                                        ->where('class', Expense::class)
                                        ->get()->pluck('name', 'name');

                $category = !$expense->categories->isEmpty() ? $expense->categories->first()->name : '';

                $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

                $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                                ->where('type', '!=', 'client')
                                ->get()
                                ->pluck('name', 'id')
                                ->prepend('(myself)', \Auth::user()->id);

                return view('expenses.edit', compact('expense', 'categories', 'category', 'projects', 'owners'));
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


    public function update(Request $request, Expense $expense)
    {
        if(\Auth::user()->can('edit expense'))
        {

            if($expense->created_by == \Auth::user()->creatorId())
            {

                $rules = [
                    'amount' => 'required',
                    'date' => 'required',
                    'category_id' => 'integer',
                    'project_id' => 'integer',
                ];
                if($request->attachment)
                {
                    $rules['attachment'] = 'required|max:2048';
                }

                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return Redirect::to(URL::previous())->with('error', $messages->first());
                }
                $expense->description = $request->description;
                $expense->amount      = $request->amount;
                $expense->date        = $request->date;
                $expense->project_id  = $request->project_id;

                if(!empty($request->user_id))
                {
                    $expense->user_id     = $request->user_id;
                }
                else{

                    $expense->user_id     = \Auth::user()->id;
                }

                $expense->save();

                if($request->attachment)
                {
                    if($expense->attachment)
                    {
                        \File::delete(storage_path('app/public/attachment/' . $expense->attachment));
                    }
                    $imageName = 'expense_' . $expense->id . "_" . $request->attachment->getClientOriginalName();
                    $request->attachment->storeAs('attachment', $imageName);
                    $expense->attachment = $imageName;
                    $expense->save();
                }

                $expense->syncCategory($request['category'], Expense::class);

                return Redirect::to(URL::previous())->with('success', __('Expense successfully updated.'));
            }
            else
            {
                return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Request $request, Expense $expense)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete expense'))
        {
            if($expense->created_by == \Auth::user()->creatorId())
            {
                $expense->delete();

                return Redirect::to(URL::previous())->with('success', __('Expense successfully deleted.'));
            }
            else
            {
                return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }
}
