<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $locale = auth()->user()->locale ?? $request->cookie('locale') ?? $request->getPreferredLanguage(['de', 'en']) ?? config('app.locale');
        // app()->setLocale($locale);
        // // if ( $locale = Session::get('locale')) {
        // //     App::setLocale($locale);
        // // } else {
        // //     $locale = $request->getPreferredLanguage(['en', 'de']);
        // //     App::setLocale($locale ?? config('app.locale'));
        // // }
        // return $next($request);


        $locale = config('app.locale');

        if (Auth::check() && Auth::user()?->locale) {
            $locale = Auth::user()->locale;
        } elseif (Cookie::has('locale')) {
            $locale = Cookie::get('locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
