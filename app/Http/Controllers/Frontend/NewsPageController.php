<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class NewsPageController extends Controller
{
    public function index(){
        return view('front.news.index');
    }
}
