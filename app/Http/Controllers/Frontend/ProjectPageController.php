<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ProjectPageController extends Controller
{
    public function index(){
        return view('front.projects.index');
    }
}
