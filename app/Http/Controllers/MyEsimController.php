<?php

namespace App\Http\Controllers;

use App\Models\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\KeepGoLineService;
use Illuminate\Support\Facades\Log;

class MyEsimController extends Controller {

    protected KeepGoLineService $keepGoLineService;
    public function __construct(KeepGoLineService $keepGoLineService) {
        $this->keepGoLineService = $keepGoLineService;
    }

    public function index() {
        $activations = Activation::with( [ 'bundle', 'refill', 'order' ] )
        ->where( 'customer_id', auth()->id() )
        ->latest()
        ->paginate( 6 );

        return view( 'pages.my-esim', compact( 'activations' ) );
    }

    public function showDetails(Activation $activation)
    {

        Log::info('Showing eSIM details for activation ID: ' . $activation->id);    
        // Check if the activation belongs to the authenticated user
        if ($activation->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
            Log::warning('Unauthorized access attempt to activation ID: ' . $activation->id);
        }

        Log::channel('MyEsimLog')->info('Fetching details for activation ICCID: ' . $activation->iccid);

        // Get line details from KeepGo
        
         $lineDetailsResponse = $this->keepGoLineService->getLineDetails($activation->iccid);

         Log::channel('MyEsimLog')->info('Line details retrieved: ', $lineDetailsResponse);

        if (isset($lineDetailsResponse['sim_card'])) {
            Log::channel('MyEsimLog')->info('Processing line details for activation ID: ' . $activation->id);
            Log::channel('MyEsimLog')->info('--------------------------------');
            $allowedUsageKB = $lineDetailsResponse['sim_card']['allowed_usage_kb'] ?? 0;
            Log::channel('MyEsimLog')->info('Allowed Usage (KB): ' . $allowedUsageKB);
            $remainingUsageKB = $lineDetailsResponse['sim_card']['remaining_usage_kb'] ?? 0;
            Log::channel('MyEsimLog')->info('Remaining Usage (KB): ' . $remainingUsageKB);
            $remainingDays = $lineDetailsResponse['sim_card']['remaining_days'] ?? 0;
            Log::channel('MyEsimLog')->info('Remaining Days: ' . $remainingDays);
            Log::channel('MyEsimLog')->info('--------------------------------');

            // Convert allowed usage from KB to GB (1 GB = 1024 * 1024 KB)
            $allowedUsageGB = $allowedUsageKB > 0
                ? round($allowedUsageKB / (1024 * 1024), 2)
                : 0;
            
            $remainingUsageKB = $remainingUsageKB > 0
                ? round($remainingUsageKB / (1024 * 1024), 2)
                : 0;

            // Attach GB value to the sim_card details so the view can show it directly
            $lineDetails['allowed_usage_gb'] = $allowedUsageGB;
            $lineDetails['remaining_usage_gb'] = $remainingUsageKB ;
            $lineDetails['remaining_days'] = $remainingDays;


            $activation->line_details = $lineDetails;
        } else {
            $activation->line_details = null;
        }
        Log::channel('MyEsimLog')->info('--------------------------------');
        Log::channel('MyEsimLog')->info('Final line details assigned to activation: ', ['line_details' => $activation->line_details]);
        Log::channel('MyEsimLog')->info('--------------------------------');
        Log::channel('MyEsimLog')->info('Prepared activation details for view.', ['activation' => $activation]);
        Log::channel('MyEsimLog')->info('--------------------------------');

        // Load related data
        $activation->load(['bundle', 'refill', 'customer']);

        return view('pages.my-esim-details', compact('activation'));
    }
}