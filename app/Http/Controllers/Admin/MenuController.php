<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Page;
use Yajra\DataTables\Facades\DataTables;


class MenuController extends Controller {
    public function index() {
        $menus = Menu::orderBy('order')->get();
        $pages = Page::all();
        return view('admin.menus.index', compact('menus', 'pages'));
    }

    public function getData() {
        $menus = Menu::with('parent', 'page')->orderBy('order');

        return DataTables::of($menus)
            ->addColumn('parent_name', fn ($menu) => $menu->parent ? $menu->parent->name : '-')
            ->addColumn('page_title', fn ($menu) => $menu->page ? $menu->page->title : '-')
            ->addColumn('is_active', fn ($menu) => $menu->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Pasif</span>')
            ->addColumn('actions', fn ($menu) => '
                <button class="btn btn-sm btn-warning edit-menu" data-id="' . $menu->id . '">Düzenle</button>
                <button class="btn btn-sm btn-danger delete-menu" data-id="' . $menu->id . '">Sil</button>
            ')
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function store(Request $request) {
        dd($request->all());
        Menu::create($request->all());
        return response()->json(['message' => 'Menü başarıyla eklendi.']);
    }

    public function edit($id) {
        return response()->json(Menu::findOrFail($id));
    }

    public function update(Request $request, $id) {
        Menu::findOrFail($id)->update($request->all());
        return response()->json(['message' => 'Menü başarıyla güncellendi.']);
    }

    public function destroy($id) {
        Menu::findOrFail($id)->delete();
        return response()->json(['message' => 'Menü başarıyla silindi.']);
    }
}

