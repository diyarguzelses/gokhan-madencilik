<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Machine;

class MachineParkController extends Controller
{
    public function index(){
        $machinePark = Machine::orderBy('order', 'asc')->get();

        return view('front.machinePark.index',compact('machinePark'));
    }
}
