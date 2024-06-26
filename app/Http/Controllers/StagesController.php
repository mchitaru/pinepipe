<?php

namespace App\Http\Controllers;

use App\Stage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StageStoreRequest;
use App\Http\Requests\StageUpdateRequest;
use App\Http\Requests\StageDestroyRequest;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

class StagesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Gate::authorize('create', 'App\Stage');

        $class = $request->class;
        $order = $request->order;

        return view('stages.create', compact('class', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StageStoreRequest $request)
    {
        Gate::authorize('create', 'App\Stage');

        $post = $request->validated();

        Stage::create($post);

        $request->session()->flash('success', __('Stage successfully added.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Stage $stage)
    {
        Gate::authorize('update', $stage);

        $class = $request->class;

        return view('stages.edit', compact('stage', 'class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function update(StageUpdateRequest $request, Stage $stage)
    {
        Gate::authorize('update', $stage);

        $post = $request->validated();

        $stage->update($post);

        $request->session()->flash('success', __('Stage successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(StageDestroyRequest $request, Stage $stage)
    {
        Gate::authorize('delete', $stage);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $stage->delete();

        return Redirect::to(URL::previous())->with('success', __('Stage successfully deleted.'));
    }

    public function order(StageUpdateRequest $request)
    {
        $post  = $request->validated();

        $updated = false;

        foreach($post['order'] as $key => $item)
        {
            $stage = Stage::find($item);

            if($stage && \Auth::user()->can('update', $stage)){

                $updated = $stage->updateOrder($key) || $updated;
            }
        }

        $return               = [];
        $return['is_success'] = $updated;

        return response()->json($return);
    }
}
