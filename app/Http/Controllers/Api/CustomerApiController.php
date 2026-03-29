<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Customer::query();
        
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $customers = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }
    
    public function show(Customer $customer): JsonResponse
    {
        $customer->load('orders');
        
        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
        ]);
        
        $customer = Customer::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer,
        ], 201);
    }
    
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:customers,email,' . $customer->id],
            'phone' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
        ]);
        
        $customer->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }
    
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
        ]);
    }
}
