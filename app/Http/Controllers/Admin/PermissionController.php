<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.permissions.index', compact('permissions'));
    }
    
    public function create(): View
    {
        return view('admin.permissions.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
            'group' => ['required', 'string'],
        ]);
        
        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
        ]);
        
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully');
    }
    
    public function destroy(Permission $permission): RedirectResponse
    {
        if (in_array($permission->name, ['manage_roles', 'manage_users', 'manage_sales'])) {
            return back()->with('error', 'Cannot delete protected permissions');
        }
        
        $permission->delete();
        
        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
