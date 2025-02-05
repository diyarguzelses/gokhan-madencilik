<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;

class ProjectPageController extends Controller
{
    public function completedProjects(){
        $projects = Project::where('status', 0)->get();
        $status=0;

        return view('front.projects.index', compact('projects', 'status'));
    }
    public function continuingProjects(){
        $projects = Project::where('status', 1)->get();
        $status=1;

        return view('front.projects.index', compact('projects', 'status'));
    }
}
