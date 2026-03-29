@extends('layouts.app')
@section('title', 'Roles')

@section('page-title', 'Roles & Permissions')
@section('page-subtitle', 'Manage access control')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.roles.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Create Role
        </a>
    </div>
    
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($roles as $role)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-900 dark:text-white">{{ $role->name }}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(5) as $permission)
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $permission->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 5)
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">+{{ $role->permissions->count() - 5 }} more</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $role->users->count() }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(!in_array($role->name, ['Admin', 'Super Admin']))
                        <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">No roles found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
