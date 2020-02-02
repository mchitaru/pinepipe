<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SharepointController extends Controller
{
    public function index()
    {
        return view('sharepoint.index');
    }
}
