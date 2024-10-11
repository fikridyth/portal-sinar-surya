<?php

namespace App\Http\Controllers;

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
        $users = User::all();
        $roles = Role::all();

        return view('master.user.index', compact('title', 'users', 'roles'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'id_role' => $request->role_id,
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
            ];
        } else {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'id_role' => $request->role,
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
        $roles = Role::all();

        return view('master.role.index', compact('title', 'roles'));
    }

    public function editRole($id)
    {
        $title = "Edit Role";
        $role = Role::find($id);
        $listRole = explode(',', $role->otorisasi);

        return view('master.role.edit', compact('title', 'role', 'listRole'));
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::find($id);
        $otoritasValue = implode(',', $request->value);
        $role->update(['otorisasi' => $otoritasValue]);

        return Redirect::route('master.role.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Update Otorisasi Success!");
    }
}
