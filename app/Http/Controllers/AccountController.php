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
}
