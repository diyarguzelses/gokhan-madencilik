<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class MachineParkController extends Controller
{
    public function index(){
        return view('front.machinePark.index');
    }
}
