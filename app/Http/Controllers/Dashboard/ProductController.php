<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $products = $query->orderByDesc('created_at')->paginate(20);
        $categories = Category::where('is_active', true)->get();
        
        return view('dashboard.products.index', compact('products', 'categories'));
    }
    
    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('dashboard.products.create', compact('categories'));
    }
    
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sku'] = $data['sku'] ?? Str::upper(Str::random(8));
        
        Product::create($data);
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }
    
    public function show(Product $product): View
    {
        $product->load('category', 'orderItems.order');
        return view('dashboard.products.show', compact('product'));
    }
    
    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }
    
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }
    
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
