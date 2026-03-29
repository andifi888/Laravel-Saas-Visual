@extends('layouts.app')
@section('title', 'Create Category')

@section('page-title', 'Categories')
@section('page-subtitle', 'Create new category')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="input-custom w-full @error('name') border-red-500 @enderror"
                       placeholder="Enter category name">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="input-custom w-full" placeholder="Category description">{{ old('description') }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <label class="flex items-center mt-2">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                </label>
            </div>
            
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('categories.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="btn-primary px-6 py-2">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
