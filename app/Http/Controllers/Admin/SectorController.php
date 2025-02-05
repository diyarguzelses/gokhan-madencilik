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
        $sectors = Sector::select(['id', 'name', 'text', 'image']);

        return DataTables::of($sectors)
            ->addColumn('actions', function ($sector) {
                return '
                    <button class="btn btn-primary btn-sm edit-sector" data-id="'.$sector->id.'" data-name="'.$sector->name.'" data-text="'.$sector->text.'">Düzenle</button>
                    <button class="btn btn-danger btn-sm delete-sector" data-id="'.$sector->id.'">Sil</button>
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

        Sector::create([
            'name' => $request->name,
            'text' => $request->text,
            'image' => $imageName
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

        if ($sector->image) {
            $imagePath = public_path('uploads/sectors/'.$sector->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sector->delete();

        return response()->json(['success' => true, 'message' => 'Sektör silindi.']);
    }

}

