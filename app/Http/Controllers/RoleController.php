<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.view')->only('index');
        $this->middleware('permission:role.create')->only(['create', 'store']);
        $this->middleware('permission:role.edit')->only(['edit', 'update']);
        $this->middleware('permission:role.delete')->only('destroy');
    }

    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        $totalPermissions = Permission::count();
        $totalUsers = \App\Models\User::count();

        return view('pengaturan.sistem.role.index', compact('roles', 'permissions', 'totalPermissions', 'totalUsers'));
    }

    public function create()
    {
        // Dibutuhkan modal-create yang di-include di index
        $permissions = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);

        return $permissions;
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('pengaturan.sistem.role.index', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Permission berhasil diupdate!');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('success', 'Role dihapus!');
    }
}
