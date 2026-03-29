<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::withCount('products');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $categories = $query->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
    
    public function show(Category $category): JsonResponse
    {
        $category->load('products');
        
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        $category = Category::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }
    
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        
        if (isset($validated['name'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }
        
        $category->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }
    
    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products',
            ], 400);
        }
        
        $category->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
