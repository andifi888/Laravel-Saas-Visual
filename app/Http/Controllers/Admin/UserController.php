<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('tenant', 'roles');
        
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $users = $query->orderByDesc('created_at')->paginate(20);
        $tenants = Tenant::where('is_active', true)->get();
        
        return view('admin.users.index', compact('users', 'tenants'));
    }
    
    public function create(): View
    {
        $tenants = Tenant::where('is_active', true)->get();
        return view('admin.users.create', compact('tenants'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->tenant_id,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }
    
    public function show(User $user): View
    {
        $user->load('tenant', 'roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user): View
    {
        $tenants = Tenant::where('is_active', true)->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'tenants'));
    }
    
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        
        $data = $request->only(['name', 'email', 'tenant_id', 'is_active']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }
    
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
    
    public function assignRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);
        
        $user->syncRoles([$request->role]);
        
        return back()->with('success', 'Role assigned successfully');
    }
    
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);
        
        return back()->with('success', 'User status updated');
    }
}
