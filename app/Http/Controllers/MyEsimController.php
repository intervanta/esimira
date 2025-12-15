<?php

namespace App\Http\Controllers;

use App\Models\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MyEsimController extends Controller {
    public function index() {
        $activations = Activation::with( [ 'bundle', 'refill', 'order' ] )
        ->where( 'customer_id', auth()->id() )
        ->latest()
        ->paginate( 6 );

        return view( 'pages.my-esim', compact( 'activations' ) );
    }

    public function showDetails(Activation $activation)
    {
        // Check if the activation belongs to the authenticated user
        if ($activation->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Load related data
        $activation->load(['bundle', 'refill', 'customer']);

        return view('pages.my-esim-details', compact('activation'));
    }
}