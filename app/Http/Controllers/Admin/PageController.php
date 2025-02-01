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

    public function store(Request $request) {
        Page::create($request->all());
        return response()->json(['message' => 'Sayfa başarıyla eklendi.']);
    }

    public function edit($id) {
        return response()->json(Page::findOrFail($id));
    }

    public function update(Request $request, $id) {
        Page::findOrFail($id)->update($request->all());
        return response()->json(['message' => 'Sayfa başarıyla güncellendi.']);
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

