<?php

namespace App\Http\Controllers;

use App\CompanySettings;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CompanySettingsRequest;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Gate;

class CompanySettingsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CompanySettingsRequest $request)
    {
        $settings = CompanySettings::first();

        if($settings) {

            Gate::authorize('update', $settings);
        }else{

            Gate::authorize('create', 'App\CompanySettings');
        }     

        $post = $request->validated();

        $settings = CompanySettings::updateSettings($settings, $post);

        if($request->hasFile('logo')){
            
            $settings->clearMediaCollection('logos');
            $file = $settings->addMedia($request->file('logo'))->toMediaCollection('logos');
        }

        return Redirect::to(URL::previous())->with('success', __('Settings updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanySettings  $companySettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanySettings $companySettings)
    {
        Gate::authorize('delete', $companySettings);
    }
}
