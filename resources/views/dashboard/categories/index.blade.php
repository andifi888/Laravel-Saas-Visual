@extends('layouts.app')
@section('title', 'Categories')

@section('page-title', 'Categories')
@section('page-subtitle', 'Manage product categories')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                   class="input-custom flex-1">
            <button type="submit" class="btn-primary">Search</button>
        </form>
        <a href="{{ route('categories.create') }}" class="btn-primary whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>Add Category
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categories as $category)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 card-hover">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-tag text-white"></i>
            </div>
            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $category->name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $category->products_count }} products</p>
        @if($category->description)
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $category->description }}</p>
        @endif
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <span class="text-xs text-gray-500">Created {{ $category->created_at->diffForHumans() }}</span>
            <div class="flex items-center space-x-2">
                <a href="{{ route('categories.edit', $category->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
        <i class="fas fa-tags text-4xl mb-4"></i>
        <p>No categories found</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection
