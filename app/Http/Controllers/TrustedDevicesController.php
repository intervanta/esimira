<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrustedDevicesController extends Controller
{
     public function index()
    {
        return view('account.trusted-devices');
    }

}
