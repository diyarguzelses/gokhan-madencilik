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
        // Menüler, 'order' sütununa göre artan sırada getirilir.
        $menus = Menu::with('parent', 'page')->orderBy('order', 'asc')->get();

        return DataTables::of($menus)
            ->addColumn('order_number', function ($menu) {
                return $menu->order;
            })
            ->addColumn('menu_type', function ($menu) {
                return $menu->parent_id
                    ? '<span class="badge bg-info">Alt Menü</span>'
                    : '<span class="badge bg-primary">Ana Menü</span>';
            })
            ->addColumn('parent_name', function ($menu) {
                return $menu->parent ? $menu->parent->name : '-';
            })
            ->addColumn('linked_content', function ($menu) {
                if ($menu->page) {
                    return '<span class="badge bg-success">Sayfa: ' . $menu->page->title . '</span>';
                } elseif ($menu->url) {
                    return '<a href="' . $menu->url . '" target="_blank" class="text-info">' . $menu->url . '</a>';
                } else {
                    return '<span class="text-muted">Bağlantı yok</span>';
                }
            })
            ->addColumn('is_active', function ($menu) {
                return $menu->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Pasif</span>';
            })
            ->addColumn('actions', function ($menu) {
                return '
                <button class="btn btn-sm btn-warning edit-menu" data-id="' . $menu->id . '">Düzenle</button>
                <button class="btn btn-sm btn-danger delete-menu" data-id="' . $menu->id . '">Sil</button>
            ';
            })
            ->rawColumns(['order_number', 'menu_type', 'linked_content', 'is_active', 'actions'])
            ->make(true);
    }


    public function store(Request $request) {
        $data = $request->all();

        $maxOrder = Menu::max('order');

        $data['order'] = $maxOrder ? $maxOrder + 1 : 1;

        Menu::create($data);

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
        $menu = Menu::findOrFail($id);
        $deletedOrder = $menu->order;
        $menu->delete();

        Menu::where('order', '>', $deletedOrder)->decrement('order');

        return response()->json(['message' => 'Menü başarıyla silindi.']);
    }

    public function updateOrder(Request $request)
    {
        $orders = $request->input('orders');

        if (is_array($orders)) {
            foreach ($orders as $item) {
                if (!isset($item['id'])) {
                    continue;
                }
                Menu::where('id', $item['id'])->update(['order' => $item['order']]);
            }
            return response()->json(['message' => 'Menü sırası başarıyla güncellendi.']);
        }

        return response()->json(['message' => 'Geçersiz veri.'], 422);
    }


}

