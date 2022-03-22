<?php

namespace App\Http\Controllers;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
