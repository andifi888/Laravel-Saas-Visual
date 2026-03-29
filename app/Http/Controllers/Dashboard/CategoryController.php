<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::query();
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $categories = $query->withCount('products')->orderByDesc('created_at')->paginate(20);
        
        return view('dashboard.categories.index', compact('categories'));
    }
    
    public function create(): View
    {
        return view('dashboard.categories.create');
    }
    
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        
        Category::create($data);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }
    
    public function edit(Category $category): View
    {
        return view('dashboard.categories.edit', compact('category'));
    }
    
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        
        $category->update($data);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }
    
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with associated products');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
