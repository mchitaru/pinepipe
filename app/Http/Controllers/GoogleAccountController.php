<?php

namespace App\Http\Controllers;

use App\GoogleAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Google;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class GoogleAccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Google $google)
    {
        if (!$request->has('code')) {
            // Send the user to the OAuth consent screen.
            return redirect($google->createAuthUrl());
        }
    
        // Use the given code to authenticate the user.
        $google->authenticate($request->get('code'));

        // Make a call to the Google+ API to get more information on the account.
        $account = $google->service('People')->people->get('people/me?personFields=emailAddresses');

        auth()->user()->googleAccounts()->updateOrCreate(
            [
                // Map the account's id to the `google_id`.
                'google_id' => $account->resourceName,
            ],
            [
                // Use the first email address as the Google account's name.
                'name' => head($account->emailAddresses)->value,
                
                // Last but not least, save the access token for later use.
                'token' => $google->getAccessToken(),
                'created_by' => \Auth::user()->created_by,
            ]
        );
        
        // Return to the account page.
        return redirect(route('profile.edit'))->with('success', __('Google account successfully linked.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoogleAccount  $googleAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GoogleAccount $googleAccount, Google $google)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $googleAccount->delete();

        // Event though it has been deleted from our database,
        // we still have access to $googleAccount as an object in memory.
        $google->revokeToken($googleAccount->token);

        return Redirect::to(URL::previous())->with('success', __('Google account access was revoked.'));
    }
}
