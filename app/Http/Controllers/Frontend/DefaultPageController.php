<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class DefaultPageController extends Controller
{
    public function index(){
        return view('front.defaultPage.index');
    }
}
