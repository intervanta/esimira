<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    private $supportedLocales = ['en', 'es', 'ar', 'cs', 'de', 'fr', 'lt', 'ja', 'ko', 'it', 'pl', 'nl', 'pt', 'zh', 'zh-tw'];

    /**
     * POST method for AJAX language switching
     */
    public function switchLang(Request $request)
    {
        Log::info('Language switch called', ['request' => $request->all()]);
        
        $request->validate([
            'lang' => 'required|in:' . implode(',', $this->supportedLocales)
        ]);

        $newLocale = $request->lang;

        // Prevent duplicate processing
        if ($newLocale === $this->getCurrentLocale()) {
            Log::info('Language already set to:', ['locale' => $newLocale]);
            return response()->json([
                'success' => true,
                'message' => 'Language already set',
                'new_locale' => $newLocale,
                'cached' => true
            ]);
        }

        Log::info('Setting language to:', ['locale' => $newLocale]);

        // Store in multiple persistence layers
        $this->setLocalePersistence($newLocale);

        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'new_locale' => $newLocale,
            'cached' => false
        ]);
    }

    /**
     * GET method for direct language switching
     */
    public function switchLangGet($lang)
    {
        if (!in_array($lang, $this->supportedLocales)) {
            return redirect()->back()->with('error', 'Unsupported language');
        }

        // Prevent duplicate processing
        if ($lang === $this->getCurrentLocale()) {
            return redirect()->back()->with('info', 'Language already set to ' . $lang);
        }

        Log::info('GET Language switch to:', ['locale' => $lang]);

        // Store in multiple persistence layers
        $this->setLocalePersistence($lang);

        return redirect()->back()->with('success', 'Language changed to ' . $lang);
    }

    /**
     * Set locale in all persistence layers
     */
    private function setLocalePersistence($locale)
    {
        // 1. Session (immediate)
        Session::put('locale', $locale);
        Session::put('user_locale', $locale);

        // 2. Cache (30 days)
        $cacheKey = $this->getUserCacheKey();
        Cache::put($cacheKey, $locale, 60 * 24 * 30); // 30 days

        // 3. Database (if user logged in)
        if (Auth::check()) {
            $user = Auth::user();
            $user->locale = $locale;
            $user->save();
            
            Log::info('Language saved to user profile', [
                'user_id' => $user->id,
                'locale' => $locale
            ]);
        }

        // 4. Set application locale
        App::setLocale($locale);
    }

    /**
     * Get current locale with fallbacks
     */
    private function getCurrentLocale()
    {
        return App::getLocale();
    }

    /**
     * Get cache key for user language
     */
    private function getUserCacheKey()
    {
        $userId = Auth::check() ? Auth::id() : 'guest';
        $ip = request()->ip();
        return "user_language_{$userId}_{$ip}";
    }

    /**
     * Get supported locales for dropdown
     */
    public function getSupportedLocales()
    {
        $locales = [
            'en' => ['name' => 'English', 'native' => 'English'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية'],
            // Add other locales as needed
        ];

        return response()->json([
            'current_locale' => $this->getCurrentLocale(),
            'locales' => $locales
        ]);
    }

    /**
     * Get user's preferred language from all sources
     */
    public function getUserPreferredLanguage()
    {
        $preferredLocale = $this->resolvePreferredLocale();
        
        return response()->json([
            'preferred_locale' => $preferredLocale,
            'sources_checked' => $this->getLocaleSources()
        ]);
    }

    /**
     * Resolve preferred locale from all available sources
     */
    private function resolvePreferredLocale()
    {
        // 1. Check cache first (fastest)
        $cacheKey = $this->getUserCacheKey();
        $cachedLocale = Cache::get($cacheKey);
        if ($cachedLocale) {
            return $cachedLocale;
        }

        // 2. Check user database preference
        if (Auth::check()) {
            $userLocale = Auth::user()->locale;
            if ($userLocale) {
                return $userLocale;
            }
        }

        // 3. Check session
        if (Session::has('user_locale')) {
            return Session::get('user_locale');
        }

        // 4. Browser language
        $browserLocale = substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if (in_array($browserLocale, $this->supportedLocales)) {
            return $browserLocale;
        }

        // 5. Default
        return config('app.locale', 'en');
    }

    /**
     * Get all locale sources for debugging
     */
    private function getLocaleSources()
    {
        return [
            'cache' => Cache::get($this->getUserCacheKey()),
            'database' => Auth::check() ? Auth::user()->locale : null,
            'session' => Session::get('user_locale'),
            'browser' => substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 2),
            'default' => config('app.locale', 'en')
        ];
    }
}