<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Menu;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller {
    public function index() {
        return view('admin.pages.index');
    }

    public function getData() {
        $pages = Page::orderBy('created_at', 'desc');

        return DataTables::of($pages)
            ->addColumn('actions', fn ($page) => '
                <button class="btn btn-sm btn-warning edit-page" data-id="' . $page->id . '">Düzenle</button>
                <button class="btn btn-sm btn-danger delete-page" data-id="' . $page->id . '">Sil</button>
            ')
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->only('title', 'content');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Benzersiz isim
                $image->move(public_path('pages'), $imageName);
                $data['image'] = 'pages/' . $imageName;
            }

            Page::create($data);

            return response()->json([
                'message' => 'Sayfa başarıyla eklendi!',
                'data' => $data
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sayfa eklenirken bir hata oluştu!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id) {
        return response()->json(Page::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->only('title', 'content');

            if ($request->hasFile('image')) {
                if (!empty($page->image) && file_exists(public_path($page->image))) {
                    unlink(public_path($page->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Benzersiz isim
                $image->move(public_path('pages'), $imageName);
                $data['image'] = 'pages/' . $imageName;
            }

            $page->update($data);

            return response()->json([
                'message' => 'Sayfa başarıyla güncellendi!',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sayfa güncellenirken bir hata oluştu!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        Page::findOrFail($id)->delete();
        return response()->json(['message' => 'Sayfa başarıyla silindi.']);
    }

    public function show($menu_url) {
        $menu = Menu::where('url', $menu_url)->firstOrFail();
        $page = $menu->page;

        return view($page ? 'pages.show' : 'pages.default_page', compact('menu', 'page'));
    }
}

