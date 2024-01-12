<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportOwnerController extends Controller
{
    public function index()
    {
        return view('reportOwner');
    }
}
