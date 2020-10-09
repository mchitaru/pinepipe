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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ExpensesController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', 'App\Expense');

        if (!$request->ajax())
        {
            return view('expenses.page');
        }

        clock()->startEvent('ExpensesController', "Load expenses");

        $expenses = Expense::expensesByUserType()
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

        return view('expenses.index', ['expenses' => $expenses])->render();
    }

    public function create(Request $request)
    {
        Gate::authorize('create', 'App\Expense');

        $project_id = $request['project_id'];

        $categories = Category::where('class', Expense::class)
                                ->get()->pluck('name', 'id');

        $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

        $owners  = User::where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        return view('expenses.create', compact('categories', 'project_id', 'projects', 'owners'));
    }

    public function store(ExpenseStoreRequest $request)
    {
        Gate::authorize('create', 'App\Expense');

        $post = $request->validated();

        $expense = Expense::createExpense($post);

        if($request->hasFile('attachment')){

            $expense->clearMediaCollection('attachments');
            $file = $expense->addMedia($request->file('attachment'))->toMediaCollection('attachments', 's3');
        }

        $request->session()->flash('success', __('Expense successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function edit(Expense $expense)
    {
        Gate::authorize('update', $expense);

        $categories = Category::where('class', Expense::class)
                                ->get()->pluck('name', 'id');

        $projects = \Auth::user()->projectsByUserType()->pluck('projects.name', 'projects.id');

        $owners  = User::where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        return view('expenses.edit', compact('expense', 'categories', 'projects', 'owners'));
    }


    public function update(ExpenseUpdateRequest $request, Expense $expense)
    {
        Gate::authorize('update', $expense);

        $post = $request->validated();

        $expense->updateExpense($post);

        if($request->hasFile('attachment')){

            $expense->clearMediaCollection('attachments');
            $file = $expense->addMedia($request->file('attachment'))->toMediaCollection('attachments', 's3');
        }

        $request->session()->flash('success', __('Expense successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(ExpenseDestroyRequest $request, Expense $expense)
    {
        Gate::authorize('delete', $expense);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $expense->delete();

        return Redirect::to(URL::previous())->with('success', __('Expense successfully deleted.'));
    }

    public function attachment(Expense $expense, $media)
    {
        $file = $expense->media('attachments')->first();

        return Storage::disk('s3')->download($file->getPath());
    }
}
