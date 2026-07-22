<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);

        return view('users.roles', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.assign-role', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);

        return redirect()->route('user-roles.index')->with('success', 'Role user berhasil diupdate!');
    }
}
