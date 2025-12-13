<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getPreferredLocale($request);
        
        App::setLocale($locale);
        
        // Ensure session has the current locale
        if (!Session::has('user_locale') || Session::get('user_locale') !== $locale) {
            Session::put('user_locale', $locale);
        }

        return $next($request);
    }

    private function getPreferredLocale(Request $request): string
    {
        // Priority 1: Explicit request parameter (language switch)
        if ($request->has('lang') && in_array($request->lang, $this->getSupportedLocales())) {
            $this->cacheLocale($request->lang);
            return $request->lang;
        }

        // Priority 2: Cache (fastest persistence)
        $cachedLocale = $this->getCachedLocale();
        if ($cachedLocale) {
            return $cachedLocale;
        }

        // Priority 3: User database preference
        if (Auth::check()) {
            $userLocale = Auth::user()->locale;
            if ($userLocale && in_array($userLocale, $this->getSupportedLocales())) {
                $this->cacheLocale($userLocale);
                return $userLocale;
            }
        }

        // Priority 4: Session
        if (Session::has('user_locale')) {
            $sessionLocale = Session::get('user_locale');
            $this->cacheLocale($sessionLocale);
            return $sessionLocale;
        }

        // Priority 5: Browser language
        $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if (in_array($browserLocale, $this->getSupportedLocales())) {
            $this->cacheLocale($browserLocale);
            return $browserLocale;
        }

        // Priority 6: Default
        $defaultLocale = config('app.locale', 'en');
        $this->cacheLocale($defaultLocale);
        return $defaultLocale;
    }

    private function getCachedLocale()
    {
        $cacheKey = $this->getCacheKey();
        return Cache::get($cacheKey);
    }

    private function cacheLocale($locale)
    {
        $cacheKey = $this->getCacheKey();
        Cache::put($cacheKey, $locale, 60 * 24 * 30); // 30 days
    }

    private function getCacheKey()
    {
        $userId = Auth::check() ? Auth::id() : 'guest';
        $ip = request()->ip();
        return "user_language_{$userId}_{$ip}";
    }

    private function getSupportedLocales(): array
    {
        return ['en', 'es', 'ar', 'cs', 'de', 'fr', 'lt', 'ja', 'ko', 'it', 'pl', 'nl', 'pt', 'zh', 'zh-tw'];
    }
}