<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sector;
use App\Models\News;
use App\Models\Machine;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index()
    {
        // Genel istatistikler
        $totalProjects = Project::count();
        $totalNews = News::count();
        $totalMachines = Machine::count();

        // Proje durumları
        $ongoingProjects = Project::where('status', 0)->count();
        $completedProjects = Project::where('status', 1)->count();

        // Son eklenen veriler
        $latestProjects = Project::latest()->limit(5)->get();
        $latestNews = News::latest()->limit(5)->get();

        return view('admin.dashboard.index', compact(
            'totalProjects', 'totalNews', 'totalMachines',
            'ongoingProjects', 'completedProjects',
            'latestProjects', 'latestNews'
        ));
    }
}
