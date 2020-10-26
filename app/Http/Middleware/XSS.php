<?php

namespace App\Http\Middleware;

use Closure;
use Stevebauman\Purify\Facades\Purify;
use Carbon\Carbon;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        if(\Auth::check())
        {
            $user = \Auth::user();

            if(($user->last_login_at == null) || $user->last_login_at->diffInDays(Carbon::now()->toDate()) >= 1){

                $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]);
            }
    
            \App::setLocale(\Auth::user()->locale);
        }else{

            $locale = geoip($request->ip());
            \App::setLocale(\Helpers::countryToLocale($locale->iso_code));
        }

        return $next($request);
    }
}

