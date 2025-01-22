<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'is_active' => 'required|boolean',
        ]);

        Menu::create($request->all());
        return response()->json(['message' => 'Menu successfully created!']);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'is_active' => 'required|boolean',
        ]);

        $menu->update($request->all());
        return response()->json(['message' => 'Menu successfully updated!']);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu successfully deleted!']);
    }
    public function getData(Request $request)
    {
        $menus = Menu::query();

        return DataTables::of($menus)
            ->addColumn('status', function ($menu) {
                return $menu->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Pasif</span>';
            })
            ->addColumn('actions', function ($menu) {
                return '
                    <button class="btn btn-primary btn-sm editMenuButton" data-id="' . $menu->id . '" data-name="' . $menu->name . '" data-url="' . $menu->url . '" data-active="' . $menu->is_active . '">
                        <i class="fas fa-edit"></i> Düzenle
                    </button>
                    <button class="btn btn-danger btn-sm deleteMenuButton" data-id="' . $menu->id . '">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                ';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }
}
