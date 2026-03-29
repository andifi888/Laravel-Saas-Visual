<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tenant::withCount('users', 'orders');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('domain', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $tenants = $query->orderByDesc('created_at')->paginate(20);
        
        return view('admin.tenants.index', compact('tenants'));
    }
    
    public function create(): View
    {
        return view('admin.tenants.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'unique:tenants,domain'],
        ]);
        
        Tenant::create([
            'name' => $request->name,
            'domain' => $request->domain,
            'is_active' => true,
        ]);
        
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant created successfully');
    }
    
    public function show(Tenant $tenant): View
    {
        $tenant->load('users.roles', 'orders');
        
        $stats = [
            'total_users' => $tenant->users()->count(),
            'total_orders' => $tenant->orders()->count(),
            'total_revenue' => $tenant->orders()->sum('total'),
            'total_products' => $tenant->products()->count(),
        ];
        
        return view('admin.tenants.show', compact('tenant', 'stats'));
    }
    
    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', compact('tenant'));
    }
    
    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'unique:tenants,domain,' . $tenant->id],
            'settings' => ['nullable', 'array'],
        ]);
        
        $tenant->update($request->only(['name', 'domain', 'settings']));
        
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully');
    }
    
    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->users()->count() > 0) {
            return back()->with('error', 'Cannot delete tenant with users');
        }
        
        $tenant->delete();
        
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully');
    }
    
    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => !$tenant->is_active]);
        
        return back()->with('success', 'Tenant status updated');
    }
}
