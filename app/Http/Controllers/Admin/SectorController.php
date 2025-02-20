<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectorController extends Controller {
    public function index() {
        return view('admin.sectors.index');
    }

    public function getData() {
        $sectors = Sector::select(['id', 'name', 'text', 'image', 'order']);

        return DataTables::of($sectors)
            ->editColumn('order', function ($sector) {
                return $sector->order;
            })
            ->addColumn('actions', function ($sector) {
                return '
                <button class="btn btn-primary btn-sm edit-sector"
                    data-id="'.$sector->id.'"
                    data-name="'.$sector->name.'"
                    data-text="'.$sector->text.'">
                    Düzenle
                </button>
                <button class="btn btn-danger btn-sm delete-sector" data-id="'.$sector->id.'">
                    Sil
                </button>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'text' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/sectors'), $imageName);
        }

        $maxOrder = Sector::max('order');
        $order = $maxOrder ? $maxOrder + 1 : 1;

        Sector::create([
            'name' => $request->name,
            'text' => $request->text,
            'image' => $imageName,
            'order' => $order,
        ]);

        return response()->json(['success' => true, 'message' => 'Sektör eklendi.']);
    }

    public function update(Request $request, $id) {
        $sector = Sector::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'text' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($sector->image) {
                $oldImagePath = public_path('uploads/sectors/'.$sector->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/sectors'), $imageName);
            $sector->image = $imageName;
        }

        $sector->update([
            'name' => $request->name,
            'text' => $request->text,
            'image' => $sector->image
        ]);

        return response()->json(['success' => true, 'message' => 'Sektör güncellendi.']);
    }

    public function destroy($id) {
        $sector = Sector::findOrFail($id);
        $deletedOrder = $sector->order;

        if ($sector->image) {
            $imagePath = public_path('uploads/sectors/' . $sector->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sector->delete();

        Sector::where('order', '>', $deletedOrder)->decrement('order');

        return response()->json(['success' => true, 'message' => 'Sektör silindi.']);
    }


    public function deleteImage($id)
    {
        $sector = Sector::findOrFail($id);

        if ($sector->image) {
            $imagePath = public_path('uploads/sectors/'.$sector->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $sector->image = null;
            $sector->save();

            return response()->json(['success' => true, 'message' => 'Resim silindi.']);
        }

        return response()->json(['success' => false, 'message' => 'Silinecek resim bulunamadı.'], 404);
    }
    public function updateOrder(Request $request)
    {
        $orders = $request->orders;

        if (!is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Sıralama verisi bulunamadı.'], 400);
        }

        foreach ($orders as $orderData) {
            if (isset($orderData['id']) && isset($orderData['order'])) {
                $sector = Sector::find($orderData['id']);
                if ($sector) {
                    $sector->order = $orderData['order'];
                    $sector->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Sektör sıralaması başarıyla güncellendi.']);
    }

    public function create()
    {
        return view('admin.sectors.create');
    }


    public function edit($id)
    {
        $sector = Sector::findOrFail($id);
        return view('admin.sectors.edit', compact('sector'));
    }

}

