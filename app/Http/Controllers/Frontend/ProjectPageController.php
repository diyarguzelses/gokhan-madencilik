<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;

class ProjectPageController extends Controller
{
    public function completedProjects(){
        $projects = Project::where('status', 1)->get();
        $projects_ids = $projects->pluck('id');

        $projects_img = ProjectImage::whereIn('project_id', $projects_ids)->get();

        return view('front.projects.index', compact('projects', 'projects_img'));
    }
    public function continuingProjects(){
        $projects = Project::all();
        $projects_img = ProjectImage::all();

        return view('front.projects.index', compact('projects', 'projects_img'));
    }
}
