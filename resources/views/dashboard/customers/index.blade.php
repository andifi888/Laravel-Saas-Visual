@extends('layouts.app')
@section('title', 'Customers')

@section('page-title', 'Customers')
@section('page-subtitle', 'Manage your customers')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-4 items-end">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="input-custom flex-1 min-w-[200px]">
        <button type="submit" class="btn-primary"><i class="fas fa-search mr-2"></i>Search</button>
        <a href="{{ route('customers.create') }}" class="btn-primary"><i class="fas fa-plus mr-2"></i>Add Customer</a>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Last Order</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium mr-3">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->company ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        <p>{{ $customer->email }}</p>
                        <p class="text-gray-500">{{ $customer->phone ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $customer->total_orders }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">${{ number_format($customer->total_spent, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ $customer->last_order_at ? $customer->last_order_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('customers.show', $customer->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p>No customers found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $customers->links() }}
    </div>
</div>
@endsection
