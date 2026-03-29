@extends('layouts.app')
@section('title', 'Tenants')

@section('page-title', 'Tenants')
@section('page-subtitle', 'Manage tenants')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex justify-between items-center">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tenants..."
                   class="input-custom">
            <button type="submit" class="btn-primary">Search</button>
        </form>
        <a href="{{ route('admin.tenants.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Tenant
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($tenants as $tenant)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</p>
                    <p class="text-sm text-gray-500">{{ $tenant->domain ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $tenant->users_count }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $tenant->orders_count }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $tenant->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tenants.toggle-status', $tenant->id) }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-toggle-{{ $tenant->is_active ? 'on text-green-600' : 'off text-red-600' }}"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No tenants found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $tenants->links() }}</div>
@endsection
