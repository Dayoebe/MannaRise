<?php

namespace App\Http\Middleware;

use App\Support\LanguagePages;
use App\Support\LanguagePreference;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguagePreference
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = LanguagePreference::current($request);

        app()->setLocale($locale);

        if ($request->hasSession() && LanguagePages::isSupported($locale)) {
            $request->session()->put(LanguagePreference::SESSION_KEY, $locale);
        }

        return $next($request);
    }
}
