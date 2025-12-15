<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class AccountOrderController extends Controller
{
    //
    public function index()
    {
        $orders = Order::with('bundle','refill','latestTransaction')->where('customer_id', auth()->id())->latest()->paginate(5);
                
        return view('account.orders', compact('orders'));
    }

    public function receipt(Order $order)
    {

        // Security: only owner can download
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['bundle', 'refill', 'latestTransaction']);

        $pdf = Pdf::loadView('account.sections.receipt-pdf', [
            'order' => $order,
        ]);

        return $pdf->download(
            'receipt-' . $order->order_number . '.pdf'
        );
    }
}
