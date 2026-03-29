@extends('layouts.app')
@section('title', 'Create Product')

@section('page-title', 'Products')
@section('page-subtitle', 'Create new product')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="input-custom w-full @error('name') border-red-500 @enderror"
                           placeholder="Enter product name">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                    <select name="category_id" id="category_id" required class="input-custom w-full">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                           class="input-custom w-full @error('sku') border-red-500 @enderror"
                           placeholder="Auto-generated if empty">
                    @error('sku')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price *</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required
                           class="input-custom w-full @error('price') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cost</label>
                    <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', 0) }}"
                           class="input-custom w-full" placeholder="0.00">
                </div>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}"
                           class="input-custom w-full" placeholder="0">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                    </label>
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="input-custom w-full" placeholder="Product description">{{ old('description') }}</textarea>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('products.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="btn-primary px-6 py-2">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
