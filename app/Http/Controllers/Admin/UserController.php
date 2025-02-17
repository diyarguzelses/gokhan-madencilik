<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

//    public function getData(Request $request)
//    {
//        if ($request->ajax()) {
//            $users = User::query();
//            return DataTables::of($users)
//                ->addColumn('role', function ($user) {
//                    return $user->is_Admin ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-secondary">Kullanıcı</span>';
//                })
//                ->addColumn('actions', function ($user) {
//                    return '<button class="btn btn-sm btn-warning edit-user"
//                                data-id="' . $user->id . '"
//                                data-username="' . $user->username . '"
//                                data-email="' . $user->email . '"
//                                data-role="' . $user->is_Admin . '">
//                                <i class="bi bi-pencil"></i> Düzenle</button>
//                            <button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">
//                                <i class="bi bi-trash"></i> Sil</button>';
//                })
//                ->rawColumns(['role', 'actions'])
//                ->make(true);
//        }
//    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();

            if (!empty($request->input('search')['value'])) {
                $searchValue = $request->input('search')['value'];
                $query->where('username', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhereRaw("IF(is_Admin = 1, 'Admin', 'Kullanıcı') LIKE ?", ["%{$searchValue}%"]);
            }

            return DataTables::of($query)
                ->addColumn('role', function ($user) {
                    return $user->is_Admin ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-secondary">Kullanıcı</span>';
                })
                ->addColumn('actions', function ($user) {
                    return '<button class="btn btn-sm btn-warning edit-user"
                            data-id="' . $user->id . '"
                            data-username="' . $user->username . '"
                            data-email="' . $user->email . '"
                            data-role="' . $user->is_Admin . '">
                            <i class="bi bi-pencil"></i> Düzenle</button>
                        <button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">
                            <i class="bi bi-trash"></i> Sil</button>';
                })
                ->rawColumns(['role', 'actions'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'is_Admin' => 'required|boolean',
        ], [
            'username.required' => 'Kullanıcı adı alanı zorunludur.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'is_Admin.required' => 'Rol seçimi zorunludur.',
            'is_Admin.boolean' => 'Rol bilgisi geçerli değil.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'is_Admin' => $request->is_Admin,
        ]);

        return response()->json(['message' => 'Kullanıcı başarıyla eklendi.']);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_Admin' => 'required|boolean',
        ], [
            'username.required' => 'Kullanıcı adı alanı zorunludur.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'is_Admin.required' => 'Rol seçimi zorunludur.',
            'is_Admin.boolean' => 'Rol bilgisi geçerli değil.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'is_Admin' => $request->is_Admin,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        return response()->json(['message' => 'Kullanıcı başarıyla güncellendi.']);
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Kullanıcı başarıyla silindi.']);
    }
}
