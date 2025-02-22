<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller {
    public function index() {
        return view('admin.news.index');
    }

    public function getData() {
        $news = News::select(['id', 'title', 'content', 'image', 'order', 'frontpage'])
            ->orderBy('order', 'asc');

        return DataTables::of($news)
            ->addColumn('order', function ($news) {
                return $news->order;
            })
            ->addColumn('image', function ($news) {
                return $news->image
                    ? '<img src="' . asset('uploads/news/' . $news->image) . '" width="50" height="50">'
                    : 'Yok';
            })
            ->addColumn('actions', function ($news) {
                $toggleButton = '';
                if (!$news->frontpage) {
                    $toggleButton = '<button class="btn btn-success btn-sm toggle-frontpage"
                    data-id="' . $news->id . '"
                    data-frontpage="' . $news->frontpage . '">
                    Anasayfada Göster
                </button>';
                }
                return '
            <div class="d-flex alert-gray justify-content-center gap-2">
                ' . $toggleButton . '
                <button class="btn btn-primary btn-sm edit-news"
                    data-id="' . $news->id . '">
                    Düzenle
                </button>
                <button class="btn btn-danger btn-sm delete-news" data-id="' . $news->id . '">
                    Sil
                </button>
            </div>
            ';
            })
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            'title'       => 'required',
            'content'     => 'required',
            'cover_image' => 'nullable|image|max:2048',
            'images.*'    => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->title);
        $slugCount = News::where('slug', 'LIKE', "{$slug}%")->count();
        if ($slugCount) {
            $slug .= '-' . ($slugCount + 1);
        }

        $maxOrder = News::max('order');
        $order = $maxOrder ? $maxOrder + 1 : 1;

        // Kapak fotoğrafı için dosya kontrolü
        $coverImageName = null;
        if ($request->hasFile('cover_image')) {
            $coverImageName = time().'_'.Str::random(5).'.'.$request->cover_image->extension();
            $request->cover_image->move(public_path('uploads/news'), $coverImageName);
        }

        // News kaydını kapak fotoğrafı bilgisiyle oluşturuyoruz
        $news = News::create([
            'slug'    => $slug,
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $coverImageName, // Kapak fotoğrafı
            'order'   => $order,
        ]);

        // Ek görselleri kaydetme
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imageName = time().'_'.Str::random(5).'.'.$file->extension();
                $file->move(public_path('uploads/news'), $imageName);
                $news->images()->create(['image' => $imageName]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Haber eklendi.']);
    }

    public function update(Request $request, $id) {
        $news = News::findOrFail($id);

        $request->validate([
            'title'       => 'required',
            'content'     => 'required',
            'cover_image' => 'nullable|image|max:2048',
            'images.*'    => 'nullable|image|max:2048',
        ]);

        // Kapak fotoğrafı kontrolü: Yeni dosya varsa eski kapak silinsin
        if ($request->hasFile('cover_image')) {
            $coverImageName = time().'_'.Str::random(5).'.'.$request->cover_image->extension();
            $request->cover_image->move(public_path('uploads/news'), $coverImageName);
            if ($news->image) {
                $oldImagePath = public_path('uploads/news/'.$news->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $news->image = $coverImageName;
        }

        $news->update([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $news->image,
        ]);

        // Yeni ek görsellerin eklenmesi
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imageName = time().'_'.Str::random(5).'.'.$file->extension();
                $file->move(public_path('uploads/news'), $imageName);
                $news->images()->create(['image' => $imageName]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Haber güncellendi.']);
    }

    public function destroy($id) {
        $news = News::findOrFail($id);
        $deletedOrder = $news->order;

        // Kapak fotoğrafı varsa siliniyor
        if ($news->image) {
            $imagePath = public_path('uploads/news/' . $news->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // İlişkili tüm ek resimler siliniyor
        foreach ($news->images as $newsImage) {
            $imagePath = public_path('uploads/news/' . $newsImage->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $newsImage->delete();
        }

        $news->delete();
        \App\Models\News::where('order', '>', $deletedOrder)->decrement('order');

        return response()->json(['success' => true, 'message' => 'Haber silindi.']);
    }

    public function deleteImage($id)
    {
        $newsImage = NewsImage::findOrFail($id);
        $imagePath = public_path('uploads/news/' . $newsImage->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $newsImage->delete();

        return response()->json(['success' => true, 'message' => 'Resim silindi.']);
    }

    public function updateOrder(Request $request)
    {
        $orders = $request->orders; // Örneğin: [ { id: 3, order: 1 }, { id: 5, order: 2 }, ... ]

        if (!is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Sıralama verisi bulunamadı.'], 400);
        }

        foreach ($orders as $orderData) {
            if (isset($orderData['id']) && isset($orderData['order'])) {
                $news = News::find($orderData['id']);
                if ($news) {
                    $news->order = $orderData['order'];
                    $news->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Haber sıralaması başarıyla güncellendi.']);
    }

    public function toggleFrontpage($id, Request $request)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Haber bulunamadı.'
            ]);
        }

        if (!$news->frontpage) {
            News::where('id', '!=', $news->id)->update(['frontpage' => false]);
            $news->frontpage = true;
        } else {
            $news->frontpage = false;
        }

        $news->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Anasayfa durumu güncellendi.',
            'frontpage' => $news->frontpage
        ]);
    }

    public function getContent($id)
    {
        $news = News::findOrFail($id);
        return response()->json([
            'id'        => $news->id,
            'title'     => $news->title,
            'content'   => $news->content,
            'cover'     => $news->image,
            'frontpage' => $news->frontpage,
        ]);
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }
    public function deleteCover($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            $imagePath = public_path('uploads/news/' . $news->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $news->image = null;
            $news->save();

            return response()->json(['success' => true, 'message' => 'Kapak resmi silindi.']);
        }
        return response()->json(['success' => false, 'message' => 'Silinecek kapak resmi bulunamadı.'], 404);
    }

}
