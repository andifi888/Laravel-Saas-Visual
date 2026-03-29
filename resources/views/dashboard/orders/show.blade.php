@extends('layouts.app')
@section('title', 'Order Details')

@section('page-title', 'Order')
@section('page-subtitle', $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Order Items</h3>
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $order->status_badge_class }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-4">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                            </td>
                            <td class="py-4 text-right text-gray-600 dark:text-gray-300">${{ number_format($item->price, 2) }}</td>
                            <td class="py-4 text-right text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                            <td class="py-4 text-right font-medium text-gray-900 dark:text-white">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span>-${{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-4 border-t">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Order Summary</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Date</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_date->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($order->payment_status) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Customer</h3>
            @if($order->customer)
            <div class="space-y-2">
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer->name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer->email }}</p>
                @if($order->customer->phone)
                <p class="text-sm text-gray-500">{{ $order->customer->phone }}</p>
                @endif
                @if($order->customer->company)
                <p class="text-sm text-gray-500">{{ $order->customer->company }}</p>
                @endif
            </div>
            @else
            <p class="text-gray-500">No customer assigned</p>
            @endif
        </div>
        
        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Update Status</h3>
            <form method="POST" action="{{ route('orders.update-status', $order->id) }}">
                @csrf
                <select name="status" class="input-custom w-full mb-4">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-primary w-full">Update Status</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
