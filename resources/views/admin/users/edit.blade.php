@extends('layouts.app')
@section('title', 'Edit User')

@section('page-title', 'Users')
@section('page-subtitle', 'Edit user')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="input-custom w-full">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="input-custom w-full">
            </div>
            
            <div>
                <label for="tenant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tenant *</label>
                <select name="tenant_id" id="tenant_id" required class="input-custom w-full">
                    @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ $user->tenant_id == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                <input type="password" name="password" id="password" class="input-custom w-full"
                       placeholder="Leave blank to keep current">
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="input-custom w-full">
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                </label>
            </div>
            
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg">Cancel</a>
                <button type="submit" class="btn-primary px-6 py-2">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection
