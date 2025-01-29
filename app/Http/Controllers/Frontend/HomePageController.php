<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index(){
        return view('front.index');
    }
}
