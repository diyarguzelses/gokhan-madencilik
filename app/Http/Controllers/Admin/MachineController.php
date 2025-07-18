<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MachineController extends Controller
{
    public function index()
    {
        return view('admin.machines.index');
    }

    public function getData()
    {
        $machines = Machine::select(['id', 'name', 'quantity', 'image', 'order']);

        return DataTables::of($machines)
            ->addColumn('image', function ($machine) {
                return $machine->image ? '<img src="/' . $machine->image . '" class="img-thumbnail" width="50">' : 'Yok';
            })
            ->addColumn('actions', function ($machine) {
                return '
                    <button class="btn btn-primary btn-sm edit-machine" data-id="' . $machine->id . '">Düzenle</button>
                    <button class="btn btn-danger btn-sm delete-machine" data-id="' . $machine->id . '">Sil</button>
                ';
            })
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'quantity');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('machines'), $imageName);
            $data['image'] = 'machines/' . $imageName;
        }

        $maxOrder = Machine::max('order');
        $data['order'] = $maxOrder ? $maxOrder + 1 : 1;

        Machine::create($data);

        return response()->json(['success' => true, 'message' => 'Makine eklendi.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $machine = Machine::findOrFail($id);
        $data = $request->only('name', 'quantity');

        if ($request->hasFile('image')) {
            if ($machine->image && file_exists(public_path($machine->image))) {
                unlink(public_path($machine->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('machines'), $imageName);
            $data['image'] = 'machines/' . $imageName;
        }

        $machine->update($data);

        return response()->json(['success' => true, 'message' => 'Makine güncellendi.']);
    }

    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
        $deletedOrder = $machine->order;

        if ($machine->image && file_exists(public_path($machine->image))) {
            unlink(public_path($machine->image));
        }

        $machine->delete();

        Machine::where('order', '>', $deletedOrder)->decrement('order');

        return response()->json(['success' => true, 'message' => 'Makine silindi.']);
    }


    public function edit($id)
    {
        $machine = Machine::findOrFail($id);
        return response()->json($machine);
    }

    public function deleteImage($id)
    {
        $machine = Machine::findOrFail($id);

        if ($machine->image && file_exists(public_path($machine->image))) {
            unlink(public_path($machine->image));
            $machine->image = null;
            $machine->save();

            return response()->json(['success' => true, 'message' => 'Görsel silindi.']);
        }

        return response()->json(['success' => false, 'message' => 'Silinecek görsel bulunamadı.'], 404);
    }

    public function updateOrder(Request $request)
    {
        $orders = $request->orders; // Örneğin: [ { id: 3, order: 1 }, { id: 5, order: 2 }, ... ]

        if (!is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Sıralama verisi bulunamadı.'], 400);
        }

        foreach ($orders as $orderData) {
            if (isset($orderData['id']) && isset($orderData['order'])) {
                $machine = Machine::find($orderData['id']);
                if ($machine) {
                    $machine->order = $orderData['order'];
                    $machine->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Makine sıralaması başarıyla güncellendi.']);
    }
}
