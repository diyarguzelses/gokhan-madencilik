<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MachineController extends Controller
{
    public function index() {
        return view('admin.machines.index');
    }

    public function getData() {
        $machines = Machine::select(['id', 'name', 'quantity']);

        return DataTables::of($machines)
            ->addColumn('actions', function ($machine) {
                return '
                    <button class="btn btn-primary btn-sm edit-machine"
                        data-id="'.$machine->id.'"
                        data-name="'.$machine->name.'"
                        data-quantity="'.$machine->quantity.'">
                        Düzenle
                    </button>
                    <button class="btn btn-danger btn-sm delete-machine"
                        data-id="'.$machine->id.'">
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
            'quantity' => 'required|integer|min:1',
        ]);

        Machine::create($request->all());

        return response()->json(['success' => true, 'message' => 'Makine eklendi.']);
    }

    public function update(Request $request, Machine $machine) {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $machine->update($request->all());

        return response()->json(['success' => true, 'message' => 'Makine güncellendi.']);
    }

    public function destroy(Machine $machine) {
        $machine->delete();

        return response()->json(['success' => true, 'message' => 'Makine silindi.']);
    }
}
