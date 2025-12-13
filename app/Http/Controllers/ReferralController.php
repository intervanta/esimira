<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferralController extends Controller
{
    public function capture(Request $request)
    {
        // Get code from query parameter instead of route parameter
        $code = $request->query('code');
        
        if (!$code) {
            // If you want to support multiple parameter names
            $code = $request->query('ref') ?? $request->query('referral');
        }

        if ($code) {
            $code = trim($code);
            
            // Store in session and cookie
            $request->session()->put('referral.code', $code);
            $request->session()->put('referral.captured_at', now());
            $request->session()->put('referral.source_url', $request->fullUrl());
            
            cookie()->queue(cookie('referral_code', $code, 60 * 24 * 30));

            Log::info('Referral captured via /ref route', ['code' => $code]);
        }

        // Redirect to home or register page
        return redirect()->route('home');
    }
}