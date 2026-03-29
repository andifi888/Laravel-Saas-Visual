@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('page-title', 'Admin Panel')
@section('page-subtitle', 'System Management')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_users']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Tenants</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_tenants']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                <i class="fas fa-building text-purple-600 dark:text-purple-400"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_orders']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-green-600 dark:text-green-400"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($stats['total_revenue'], 0) }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-yellow-600 dark:text-yellow-400"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Orders</h3>
        <div class="space-y-4">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-500">{{ $order->customer->name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-medium text-gray-900 dark:text-white">${{ number_format($order->total, 2) }}</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No orders yet</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Users</h3>
        <div class="space-y-4">
            @forelse($recentUsers as $user)
            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium mr-3">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->tenant->name ?? 'No tenant' }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No users yet</p>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center">
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center mr-4">
            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white">Manage Users</p>
            <p class="text-sm text-gray-500">View & edit users</p>
        </div>
    </a>
    
    <a href="{{ route('admin.tenants.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center">
        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center mr-4">
            <i class="fas fa-building text-purple-600 dark:text-purple-400"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white">Manage Tenants</p>
            <p class="text-sm text-gray-500">View & edit tenants</p>
        </div>
    </a>
    
    <a href="{{ route('admin.roles.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center">
        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center mr-4">
            <i class="fas fa-shield-alt text-green-600 dark:text-green-400"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white">Roles & Permissions</p>
            <p class="text-sm text-gray-500">Manage access control</p>
        </div>
    </a>
    
    <a href="{{ route('categories.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center">
        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center mr-4">
            <i class="fas fa-database text-yellow-600 dark:text-yellow-400"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white">Data Management</p>
            <p class="text-sm text-gray-500">Products & Categories</p>
        </div>
    </a>
</div>
@endsection
