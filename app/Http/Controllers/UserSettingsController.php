<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Http\Helpers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\UserSettingsCompanyRequest;
use App\Http\Requests\UserSettingsEmailRequest;
use App\Http\Requests\UserSettingsStripeRequest;
use App\Http\Requests\UserSettingsSystemRequest;

class UserSettingsController extends Controller
{

    public function updateEmail(UserSettingsEmailRequest $request)
    {
        $post = $request->validated();

        $path = base_path('.env');
        if(file_exists($path))
        {
            file_put_contents(
                $path, str_replace(
                            'MAIL_DRIVER=' . env('MAIL_DRIVER') . '', "MAIL_DRIVER=" . addslashes($request->mail_driver) . "", file_get_contents($path)
                        )
            );

            file_put_contents(
                $path, str_replace(
                            'MAIL_HOST=' . env('MAIL_HOST') . '', "MAIL_HOST=" . addslashes($request->mail_host) . "", file_get_contents($path)
                        )
            );
            file_put_contents(
                $path, str_replace(
                'MAIL_PORT=' . ((env('MAIL_PORT') == NULL) ? 'null' : ("'".env('MAIL_PORT'))."'"), "MAIL_PORT='" . addslashes($request->mail_port) . "'", file_get_contents($path)
                        )
            );

            file_put_contents(
                $path, str_replace(
                'MAIL_USERNAME=' . ((env('MAIL_USERNAME') == NULL) ? 'null' : ("'".env('MAIL_USERNAME'))."'"), "MAIL_USERNAME='" . addslashes($request->mail_username) . "'", file_get_contents($path)
                        )
            );
            file_put_contents(
                $path, str_replace(
                'MAIL_PASSWORD=' . ((env('MAIL_PASSWORD') == NULL) ? 'null' : ("'".env('MAIL_PASSWORD'))."'"), "MAIL_PASSWORD='" . addslashes($request->mail_password) . "'", file_get_contents($path)

                        )
            );
            file_put_contents(
                $path, str_replace(
                'MAIL_ENCRYPTION=' . ((env('MAIL_ENCRYPTION') == NULL) ? 'null' : ("'".env('MAIL_ENCRYPTION'))."'"), "MAIL_ENCRYPTION='" . addslashes($request->mail_encryption) . "'", file_get_contents($path)

                        )
            );
            file_put_contents(
                $path, str_replace(
                'MAIL_FROM_ADDRESS=' . ((env('MAIL_FROM_ADDRESS') == NULL) ? 'null' : ("'".env('MAIL_FROM_ADDRESS'))."'"), "MAIL_FROM_ADDRESS='" . addslashes($request->mail_from_address) . "'", file_get_contents($path)

                        )
            );
            file_put_contents(
                $path, str_replace(
                'MAIL_FROM_NAME=' . ((env('MAIL_FROM_NAME') == NULL) ? 'null' : ("'".env('MAIL_FROM_NAME'))."'"), "MAIL_FROM_NAME='" . addslashes($request->mail_from_name) . "'", file_get_contents($path)

                        )
            );

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
    }

    public function updateCompany(UserSettingsCompanyRequest $request)
    {
            
        $post = $request->validated();

        if($request->hasFile('company_logo'))
        {
            $path = Helpers::storePublicFile($request->file('company_logo'));
            $post['company_logo'] = $path;
        }
    
        unset($post['_token']);

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                $data,
                                                                                                                                                $key,
                                                                                                                                                \Auth::user()->creatorId(),
                                                                                                                                            ]
            );
        }

        return Redirect::to(URL::previous() . "#company")->with('success', __('Settings updated successfully.'));
    }

    public function updateStripe(UserSettingsStripeRequest $request)
    {
        $post = $request->validated();

        $path = base_path('.env');
        if(file_exists($path))
        {
            file_put_contents(
                $path, str_replace(
                            'STRIPE_KEY=' . ((env('STRIPE_KEY') == NULL) ? 'null' : ("'".env('STRIPE_KEY'))."'"), "STRIPE_KEY='" . addslashes($request->stripe_key) . "'", file_get_contents($path)
                        )
            );
            file_put_contents(
                $path, str_replace(
                            'STRIPE_SECRET=' . ((env('STRIPE_SECRET') == NULL) ? 'null' : ("'".env('STRIPE_SECRET'))."'"), "STRIPE_SECRET='" . addslashes($request->stripe_secret) . "'", file_get_contents($path)
                        )
            );

        }

        return Redirect::to(URL::previous() . "#stripe")->with('success', __('Stripe settings updated successfully.'));
    }

    public function updateSystem(UserSettingsSystemRequest $request)
    {
        $post = $request->validated();
        unset($post['_token']);

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                \Auth::user()->creatorId(),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
        }

        return Redirect::to(URL::previous() . "#system")->with('success', __('Settings updated successfully.'));
    }
}
