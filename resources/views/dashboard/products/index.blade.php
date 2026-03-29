@extends('layouts.app')
@section('title', 'Products')

@section('page-title', 'Products')
@section('page-subtitle', 'Manage your product catalog')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                   class="input-custom flex-1 min-w-[200px]">
            <select name="category_id" class="input-custom">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </form>
        @can('manage_sales')
        <a href="{{ route('products.create') }}" class="btn-primary whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>Add Product
        </a>
        @endcan
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-box text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($product->total_sales, 2) }} sold</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $product->stock }} units
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $product->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('products.show', $product->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('manage_sales')
                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p>No products found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $products->links() }}
    </div>
</div>
@endsection
