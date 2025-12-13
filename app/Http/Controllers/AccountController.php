<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function index()
    {
        return view('dashboard');
    }

    public function orders()
    {
        $orders = Order::where('customer_id', auth()->id())->latest()->get();

        
        return view('account.orders', compact('orders'));
    }
}
