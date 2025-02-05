<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index(){
        $news = News::latest()->get();

        return view('front.index', compact('news'));
    }
}
