<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller {
    public function index() {
        return view('admin.news.index');
    }

    public function getData() {
        $news = News::select(['id', 'title', 'content', 'image', 'order','frontpage'])
            ->orderBy('order', 'asc'); // order sütununa göre sıralama

        return DataTables::of($news)
            ->addColumn('order', function ($news) {
                return $news->order;
            })
            ->addColumn('image', function ($news) {
                return $news->image ? '<img src="'.asset('uploads/news/'.$news->image).'" width="50" height="50">' : 'Yok';
            })
            ->addColumn('actions', function ($news) {
                $toggleButton = '';
                if (!$news->frontpage) {
                    $toggleButton = '<button class="btn btn-success btn-sm toggle-frontpage"
            data-id="'.$news->id.'"
            data-frontpage="'.$news->frontpage.'">
            Anasayfada Göster
        </button>';
                }
                return '
      <div class="d-flex alert-gray justify-content-center gap-2">
        '.$toggleButton.'
        <button class="btn btn-primary btn-sm edit-news"
            data-id="'.$news->id.'"
            data-title="'.$news->title.'"
            data-content="'.$news->content.'"
            data-image="'.$news->image.'"
            data-frontpage="'.$news->frontpage.'">
            Düzenle
        </button>
        <button class="btn btn-danger btn-sm delete-news" data-id="'.$news->id.'">
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
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/news'), $imageName);
        }

        $slug = Str::slug($request->title);
        // Aynı slug ile başlayan kayıtları sayarak benzersiz hale getirme
        $slugCount = News::where('slug', 'LIKE', "{$slug}%")->count();
        if ($slugCount) {
            $slug .= '-' . ($slugCount + 1);
        }

        // Yeni haber eklenirken, mevcut en yüksek order değerine göre yeni sıra: max + 1
        $maxOrder = News::max('order');
        $order = $maxOrder ? $maxOrder + 1 : 1;

        News::create([
            'slug'    => $slug,
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $imageName,
            'order'   => $order,
        ]);

        return response()->json(['success' => true, 'message' => 'Haber eklendi.']);
    }

    public function update(Request $request, $id) {
        $news = News::findOrFail($id);

        $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/news'), $imageName);
            if ($news->image) {
                $oldImagePath = public_path('uploads/news/'.$news->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $news->image = $imageName;
        }

        $news->update([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $news->image,
        ]);

        return response()->json(['success' => true, 'message' => 'Haber güncellendi.']);
    }

    public function destroy($id) {
        $news = News::findOrFail($id);

        if ($news->image) {
            $imagePath = public_path('uploads/news/'.$news->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $news->delete();

        return response()->json(['success' => true, 'message' => 'Haber silindi.']);
    }

    public function deleteImage($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            $imagePath = public_path('uploads/news/' . $news->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $news->image = null;
            $news->save();

            return response()->json(['success' => true, 'message' => 'Resim silindi.']);
        }
        return response()->json(['success' => false, 'message' => 'Silinecek resim bulunamadı.'], 404);
    }

    // Yeni: Haber sıralamasını güncelleme metodu
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


}
