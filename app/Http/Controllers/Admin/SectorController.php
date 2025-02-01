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

        Sector::create($request->all());

        return response()->json(['success' => true, 'message' => 'Sektör eklendi.']);
    }

    public function update(Request $request, Sector $sector) {
        $request->validate([
            'name' => 'required',
            'text' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $sector->update($request->all());

        return response()->json(['success' => true, 'message' => 'Sektör güncellendi.']);
    }

    public function destroy(Sector $sector) {
        $sector->delete();

        return response()->json(['success' => true, 'message' => 'Sektör silindi.']);
    }
}

