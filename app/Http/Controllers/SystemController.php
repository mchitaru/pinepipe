<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage system settings'))
        {
            $settings = \Auth::user()->settings();
            return view('settings.index', compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('manage system settings'))
        {
            $request->validate(
                [
                    'logo' => 'required|image|mimes:png|max:1024',
                ]
            );
            if($request->logo)
            {
                $logoName = 'logo.png';
                $path     = $request->file('logo')->storeAs('public/logo/', $logoName);

                return redirect()->back()->with('success', 'Logo successfully updated.');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveEmailSettings(Request $request)
    {
        if(\Auth::user()->can('manage system settings'))
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );
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
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function saveCompanySettings(Request $request)
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $user = \Auth::user();
            $request->validate(
                [
                    'company_name' => 'required|string|max:50',
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                ]
            );
            $post = $request->all();
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

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveStripeSettings(Request $request)
    {

        if(\Auth::user()->can('manage stripe settings'))
        {
            $user = \Auth::user();
            $request->validate(
                [
                    'stripe_key' => 'required|string|max:50',
                    'stripe_secret' => 'required|string|max:50',
                ]
            );
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

            return redirect()->back()->with('success', __('Stripe successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveSystemSettings(Request $request)
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $user = \Auth::user();
            $request->validate(
                [
                    'site_currency' => 'required',
                ]
            );
            $post = $request->all();
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

            return redirect()->back()->with('success', __('Setting successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function companyIndex()
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $settings = \Auth::user()->settings();

            return view('settings.company', compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

}
