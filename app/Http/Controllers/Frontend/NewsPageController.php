<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsPageController extends Controller
{
    public function index(){
        $news = News::orderBy('order', 'asc')->get();
        return view('front.news.index',compact('news'));
    }
    public function detail($slug){

        $news = News::where('slug',$slug)->firstOrFail();



        return view('front.news.detail',compact('news'));
    }
}
