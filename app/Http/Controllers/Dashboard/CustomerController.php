<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query();
        
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $customers = $query->withCount('orders')->orderByDesc('created_at')->paginate(20);
        
        return view('dashboard.customers.index', compact('customers'));
    }
    
    public function create(): View
    {
        return view('dashboard.customers.create');
    }
    
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }
    
    public function show(Customer $customer): View
    {
        $customer->load('orders.items.product');
        return view('dashboard.customers.show', compact('customer'));
    }
    
    public function edit(Customer $customer): View
    {
        return view('dashboard.customers.edit', compact('customer'));
    }
    
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }
    
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}
