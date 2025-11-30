<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class MenteeController extends Controller
{
    public function index()
    {
        // Ganti 'mentee.dashboard' dengan nama view Mentee kamu
        return view('mentee.dashboard.index'); 
    }
}

