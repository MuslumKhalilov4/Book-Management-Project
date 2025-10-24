<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetApiLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    $locale = $request->query('lang');

    if (! $locale && $request->hasHeader('Accept-Language')) {
        $locale = $request->getPreferredLanguage(); 
    }

    $locale = $locale ?? config('app.locale');

    if ($locale) {
        app()->setLocale($locale);
    }

    return $next($request);
}
}
