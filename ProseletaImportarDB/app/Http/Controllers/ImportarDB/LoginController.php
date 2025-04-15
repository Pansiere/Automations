<?php

namespace App\Http\Controllers\ImportarDB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function get()
    {
        return view('login');
    }

    public function post(Request $request)
    {
        dd($request->all());

        return view('login');
    }
}
