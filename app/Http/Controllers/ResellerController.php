<?php

namespace App\Http\Controllers;

use App\Models\ResellerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResellerController extends Controller
{
    public function submitEnquiry(Request $request)
    {

        // return $request->all();
        // Validation
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required',
            'whatsapp'   => 'nullable',
            'has_website' => 'required',
            // 'language'   => 'required',
        ]);
        
        Log::info('Reseller Enquiry Submitted: ', $validated);
        // Optional: Save to database
        ResellerEnquiry::create($validated);

        // Optional: Send Email to Admin
        // Mail::to('admin@esimira.com')->send(new ResellerEnquiryMail($validated));

        return back()->with('success', 'Your enquiry has been submitted successfully!');
    }
}
