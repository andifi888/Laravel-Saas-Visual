<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }
    
    public function create(): View
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
            'permissions' => ['array'],
        ]);
        
        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }
    
    public function edit(Role $role): View
    {
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,' . $role->id],
            'permissions' => ['array'],
        ]);
        
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }
    
    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, ['Admin', 'Super Admin'])) {
            return back()->with('error', 'Cannot delete protected roles');
        }
        
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users');
        }
        
        $role->delete();
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
