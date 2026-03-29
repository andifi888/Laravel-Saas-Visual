@extends('layouts.app')
@section('title', 'Orders')

@section('page-title', 'Orders')
@section('page-subtitle', 'Manage customer orders')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order number..."
                   class="input-custom">
        </div>
        <div>
            <select name="status" class="input-custom">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-custom">
        </div>
        <div>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-custom">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
        <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Reset</a>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order #</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Items</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <a href="{{ route('orders.show', $order->id) }}" class="font-medium text-blue-600 hover:text-blue-500">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ $order->customer->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ $order->items->count() }} items
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        ${{ number_format($order->total, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $order->status_badge_class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ $order->order_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('orders.show', $order->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                        <p>No orders found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $orders->links() }}
    </div>
</div>
@endsection
