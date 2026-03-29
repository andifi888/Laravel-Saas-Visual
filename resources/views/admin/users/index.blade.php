@extends('layouts.app')
@section('title', 'Admin Users')

@section('page-title', 'Users')
@section('page-subtitle', 'System Users Management')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="input-custom flex-1">
            <select name="tenant_id" class="input-custom">
                <option value="">All Tenants</option>
                @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Search</button>
        </form>
        <a href="{{ route('admin.users.create') }}" class="btn-primary whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $user->tenant->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @foreach($user->roles as $role)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 mr-1">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-toggle-{{ $user->is_active ? 'on text-green-600' : 'off text-red-600' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $users->links() }}
    </div>
</div>
@endsection
