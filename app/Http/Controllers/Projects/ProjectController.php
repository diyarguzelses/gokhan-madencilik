<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function data()
    {
        $projects = Project::with('category', 'images')->select('projects.*');

        return datatables($projects)
            ->addColumn('category_name', function ($project) {
                return $project->category->name ?? 'Kategori Yok';
            })
            ->addColumn('actions', function ($project) {
                return '
                <a href="' . route('admin.projects.edit', $project->id) . '" class="btn btn-sm btn-primary">Düzenle</a>
                <button class="btn btn-sm btn-danger delete-project" data-id="' . $project->id . '">Sil</button>
            ';
            })
            ->rawColumns(['actions']) // HTML içeren sütunları işaretle
            ->make(true);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Görsel doğrulama
        ]);

        // Proje Oluştur
        $project = Project::create($request->only(['name', 'description', 'category_id','status']));

        // Görselleri Kaydet
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('project_images'), $imageName);

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => 'project_images/' . $imageName
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proje ve görseller başarıyla kaydedildi.');
    }

    public function edit($id)
    {
        $project = Project::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $project->update($request->only(['name', 'description', 'category_id','status']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('project_images'), $imageName);

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => 'project_images/' . $imageName
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proje ve görseller başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $project = Project::with('images')->findOrFail($id);

        foreach ($project->images as $image) {
            $imagePath = public_path($image->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image->delete();
        }

        $project->delete();

        return response()->json(['success' => true]);
    }

    public function destroyImage($id)
    {
        $image = ProjectImage::findOrFail($id);

        $imagePath = public_path($image->image_path);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Görsel başarıyla silindi.']);
    }
}
