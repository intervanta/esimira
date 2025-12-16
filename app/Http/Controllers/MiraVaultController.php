<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiraVaultController extends Controller
{
    public function index()
    {
        return view('account.miravault');
    }
}
