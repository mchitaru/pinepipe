<?php

namespace App\Http\Controllers;

use App\Media;
use App\Expense;
use App\Category;
use App\Http\Requests\ExpenseDestroyRequest;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ExpensesController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::user()->can('view expense'))
        {
            clock()->startEvent('ExpensesController', "Load expenses");

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

            $categories = Category::where('created_by', \Auth::user()->creatorId())
                                    ->where('class', Expense::class)
                                    ->get()->pluck('name', 'id');

            $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                            ->where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend(__('(myself)'), \Auth::user()->id);

            return view('expenses.create', compact('categories', 'project_id', 'projects', 'owners'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(ExpenseStoreRequest $request)
    {
        $post = $request->validated();

        $expense = Expense::createExpense($post);

        if($request->hasFile('attachment')){

            $expense->clearMediaCollection('attachments');
            $file = $expense->addMedia($request->file('attachment'))->toMediaCollection('attachments', 'local');
        }

        $request->session()->flash('success', __('Expense successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function edit(Expense $expense)
    {
        if(\Auth::user()->can('edit expense'))
        {
            $categories = Category::where('created_by', \Auth::user()->creatorId())
                                    ->where('class', Expense::class)
                                    ->get()->pluck('name', 'id');

            $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                            ->where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend(__('(myself)'), \Auth::user()->id);

            return view('expenses.edit', compact('expense', 'categories', 'projects', 'owners'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }


    public function update(ExpenseUpdateRequest $request, Expense $expense)
    {
        $post = $request->validated();

        $expense->updateExpense($post);

        if($request->hasFile('attachment')){

            $expense->clearMediaCollection('attachments');
            $file = $expense->addMedia($request->file('attachment'))->toMediaCollection('attachments', 'local');
        }

        $request->session()->flash('success', __('Expense successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(ExpenseDestroyRequest $request, Expense $expense)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $expense->delete();

        return Redirect::to(URL::previous())->with('success', __('Expense successfully deleted.'));
    }

    public function attachment(Expense $expense, $media)
    {
        $media = $expense->media('attachments')->first();

        $file_path = $media->getPath();
        $filename  = $media->file_name;
        
        return \Response::download(
            $file_path, $filename, [
                            'Content-Length: ' . filesize($file_path),
                        ]
        );
    }
}
