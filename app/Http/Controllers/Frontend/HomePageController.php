<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Machine;
use App\Models\News;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index(){
        $news = News::latest()->get();
        $firstThreeSectors = Sector::take(3)->get();
        $nextFourSectors = Sector::skip(3)->take(4)->get();
        $lastnew = News::latest()->first();
        $projectCount = Project::count();
        $sectorCount = Sector::count();
        $machineCount = Machine::sum('quantity');
        $projeCategoryCount = Category::count();


        return view('front.index', compact('news','firstThreeSectors','nextFourSectors','lastnew','projectCount','sectorCount','machineCount','projeCategoryCount'));
    }
}
