<?php

namespace App\Http\Middleware;

use Closure;
use Stevebauman\Purify\Facades\Purify;

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
            \App::setLocale(\Auth::user()->locale);
        }else{

            $locale = geoip($request->ip());
            \App::setLocale(\Helpers::countryToLocale($locale->iso_code));
        }

        if(!empty($input)) {

            array_walk_recursive(
                $input, function (&$input){

                    if(!empty($input)) {

                        // $input = strip_tags($input);
                        $input = Purify::clean($input);
                    }
                }
            );
            $request->merge($input);
        }

        return $next($request);
    }
}

