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

//    public function getData() {
//        $pages = Page::with('images')->orderBy('created_at', 'desc');
//
//        return DataTables::of($pages)
//            ->addColumn('actions', fn ($page) => '
//            <button class="btn btn-sm btn-warning edit-page" data-id="' . $page->id . '">Düzenle</button>
//            <button class="btn btn-sm btn-danger delete-page" data-id="' . $page->id . '">Sil</button>
//        ')
//            ->editColumn('image', function ($page) {
//                // İlk görseli gösterelim, yoksa "Yok" yazalım
//                return ($page->images->first())
//                    ? '<img src="/' . $page->images->first()->image . '" class="img-thumbnail" width="50">'
//                    : 'Yok';
//            })
//            ->rawColumns(['actions', 'image'])
//            ->make(true);
//    }



    public function getData(Request $request)
    {
        $query = Page::with('images');


        if ($request->has('order')) {
            $order        = $request->input('order')[0];
            $columnIndex  = $order['column'];
            $sortDirection= $order['dir'];
            $columnName   = $request->input('columns')[$columnIndex]['name'];

            if (in_array($columnName, ['id','title','content','created_at'])) {
                $query->orderBy($columnName, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }


        if (!empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            $query->where('title', 'like', "%{$searchValue}%")
                ->orWhere('content', 'like', "%{$searchValue}%");
        }

        return DataTables::of($query)
            ->addColumn('actions', function ($page) {
                return '
                <button class="btn btn-sm btn-warning edit-page" data-id="' . $page->id . '">Düzenle</button>
                <button class="btn btn-sm btn-danger delete-page" data-id="' . $page->id . '">Sil</button>
            ';
            })
            ->editColumn('image', function ($page) {
                return ($page->images->first())
                    ? '<img src="/' . $page->images->first()->image . '" class="img-thumbnail" width="50">'
                    : 'Yok';
            })
            ->rawColumns(['actions', 'image'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            // Görseller isteğe bağlı, her birinin tipini ve boyutunu kontrol edelim
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->only('title', 'content', 'status');
            // Yeni sayfayı oluşturuyoruz
            $page = Page::create($data);

            // Çoklu görsel kontrolü
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('pages'), $imageName);

                    // İlgili sayfaya ait görsel kaydı
                    $page->images()->create([
                        'image' => 'pages/' . $imageName,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Sayfa başarıyla eklendi!',
                'data' => $page->load('images')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sayfa eklenirken bir hata oluştu!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id) {
        $page = Page::with('images')->findOrFail($id);
        return response()->json($page);
    }


    public function update(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);

            $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->only('title', 'content', 'status');
            $page->update($data);

            // Yeni görselleri ekleme
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('pages'), $imageName);

                    $page->images()->create([
                        'image' => 'pages/' . $imageName,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Sayfa başarıyla güncellendi!',
                'data' => $page->load('images')
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
    public function deleteImage($id)
    {
        try {
            $image = \App\Models\PageImage::findOrFail($id);

            if (file_exists(public_path($image->image))) {
                unlink(public_path($image->image));
            }

            $image->delete();

            return response()->json([
                'message' => 'Görsel başarıyla silindi.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Görsel silinirken bir hata oluştu!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}

