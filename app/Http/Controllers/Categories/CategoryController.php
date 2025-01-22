<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function data()
    {
        $categories = Category::select(['id', 'name']);

        return datatables()
            ->eloquent($categories)
            ->addIndexColumn()
            ->addColumn('actions', function ($category) {
                return '
                <button class="btn btn-sm btn-primary edit-category" data-id="'.$category->id.'" data-name="'.$category->name.'">Düzenle</button>
                <button class="btn btn-sm btn-danger delete-category" data-id="'.$category->id.'">Sil</button>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true]);
    }
}
