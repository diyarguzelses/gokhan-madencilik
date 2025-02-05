<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller {
    public function index() {
        return view('admin.news.index');
    }

    public function getData() {
        $news = News::select(['id', 'title', 'content', 'image']);

        return DataTables::of($news)
            ->addColumn('image', function ($news) {
                return $news->image ? '<img src="'.asset('uploads/news/'.$news->image).'" width="50" height="50">' : 'Yok';
            })
            ->addColumn('actions', function ($news) {
                return '
                    <button class="btn btn-primary btn-sm edit-news"
                        data-id="'.$news->id.'"
                        data-title="'.$news->title.'"
                        data-content="'.$news->content.'"
                        data-image="'.$news->image.'">
                        Düzenle
                    </button>
                    <button class="btn btn-danger btn-sm delete-news"
                        data-id="'.$news->id.'">
                        Sil
                    </button>
                ';
            })
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/news'), $imageName);
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imageName
        ]);

        return response()->json(['success' => true, 'message' => 'Haber eklendi.']);
    }

    public function update(Request $request, $id) {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
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
            'title' => $request->title,
            'content' => $request->content,
            'image' => $news->image
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

}

