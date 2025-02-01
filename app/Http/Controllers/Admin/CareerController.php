<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller {
    public function edit() {
        $career = Career::first(); // İlk ve tek kaydı getir
        return view('admin.career.edit', compact('career'));
    }

    public function update(Request $request) {
        $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $career = Career::first(); // İlk ve tek kayıt

        if (!$career) {
            $career = new Career();
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/career'), $imageName);
            $career->image = $imageName;
        }

        $career->content = $request->content;
        $career->save();

        return response()->json(['success' => true, 'message' => 'Kariyer sayfası güncellendi.']);
    }
}
