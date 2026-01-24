<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Master User";
        $titleHeader = "MASTER USER";
        $users = User::all();
        $roles = Role::all();
        $cabangs = Cabang::all();

        return view('master.user.index', compact('title', 'titleHeader', 'users', 'roles', 'cabangs'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'id_role' => $request->role_id,
            'id_cabang' => $request->cabang_id,
            'jabatan' => $request->jabatan,
            'show_password' => $request->password
        ]);

        return Redirect::route('master.user.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Tambah User Success!");
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($request->password == null) {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'id_role' => $request->role,
                'id_cabang' => $request->cabang,
                'jabatan' => $request->jabatan
            ];
        } else {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'id_role' => $request->role,
                'id_cabang' => $request->cabang,
                'jabatan' => $request->jabatan,
                'show_password' => $request->password
            ];
        }

        User::find($id)->update($data);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return Redirect::route('master.user.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete User Success!");
    }

    public function indexRole()
    {
        $title = "Master Role";
        $titleHeader = 'OTORISASI STANDAR';
        $roles = Role::all();

        return view('master.role.index', compact('title', 'titleHeader', 'roles'));
    }

    public function editRole($id)
    {
        $id = dekrip($id);
        $title = "Edit Role";
        $role = Role::find($id);
        $listRole = explode(',', $role->otorisasi);

        return view('master.role.edit', compact('title', 'role', 'listRole'));
    }

    public function updateRole(Request $request, $id)
    {
        $id = dekrip($id);
        $role = Role::find($id);
        $otoritasValue = implode(',', $request->value);
        $role->update(['otorisasi' => $otoritasValue]);

        return Redirect::route('master.role.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Otorisasi Success!");
    }

    public function cekPasswordOwner(Request $request)
    {
        $password = $request->input('password'); // WAJIB

        if (!$password) {
            return response()->json([
                'message' => 'Password kosong'
            ], 422);
        }

        $ownerPassword = User::where('JABATAN', 'OWNER')
            ->pluck('show_password')
            ->first();

        if (strtoupper($password) === strtoupper($ownerPassword)) {
            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Password salah'
        ], 401);
    }
}
