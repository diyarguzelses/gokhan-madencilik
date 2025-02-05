<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Sector;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index(){
        $news = News::latest()->get();
        $firstThreeSectors = Sector::take(3)->get();
        $nextFourSectors = Sector::skip(3)->take(4)->get();

        return view('front.index', compact('news','firstThreeSectors','nextFourSectors'));
    }
}
