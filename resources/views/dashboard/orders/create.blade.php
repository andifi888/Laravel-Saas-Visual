@extends('layouts.app')
@section('title', 'Create Order')

@section('page-title', 'Orders')
@section('page-subtitle', 'Create new order')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('orders.store') }}" id="order-form" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer *</label>
                    <select name="customer_id" id="customer_id" required class="input-custom w-full">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Date</label>
                    <input type="datetime-local" name="order_date" id="order_date" value="{{ old('order_date', now()->format('Y-m-d\TH:i')) }}"
                           class="input-custom w-full">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Order Items *</label>
                <div id="order-items" class="space-y-4">
                    <div class="flex gap-4 items-end order-item">
                        <div class="flex-1">
                            <label class="block text-xs text-gray-500 mb-1">Product</label>
                            <select name="items[0][product_id]" required class="input-custom w-full product-select">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                    {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs text-gray-500 mb-1">Quantity</label>
                            <input type="number" name="items[0][quantity]" min="1" value="1" required
                                   class="input-custom w-full quantity-input">
                        </div>
                        <div class="w-32">
                            <label class="block text-xs text-gray-500 mb-1">Price</label>
                            <input type="number" step="0.01" name="items[0][price]" 
                                   class="input-custom w-full price-input" readonly>
                        </div>
                        <button type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg remove-item" style="display:none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" id="add-item" class="mt-4 px-4 py-2 border border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-500">
                    <i class="fas fa-plus mr-2"></i>Add Item
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="tax" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tax (%)</label>
                    <input type="number" step="0.01" name="tax" id="tax" value="{{ old('tax', 8) }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="shipping_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Shipping Cost</label>
                    <input type="number" step="0.01" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', 0) }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Discount</label>
                    <input type="number" step="0.01" name="discount" id="discount" value="{{ old('discount', 0) }}"
                           class="input-custom w-full">
                </div>
            </div>
            
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="input-custom w-full">{{ old('notes') }}</textarea>
            </div>
            
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('orders.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg">Cancel</a>
                <button type="submit" class="btn-primary px-6 py-2">Create Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

$('#add-item').click(function() {
    const newItem = `
        <div class="flex gap-4 items-end order-item">
            <div class="flex-1">
                <select name="items[${itemIndex}][product_id]" required class="input-custom w-full product-select">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required class="input-custom w-full quantity-input">
            </div>
            <div class="w-32">
                <input type="number" step="0.01" name="items[${itemIndex}][price]" class="input-custom w-full price-input" readonly>
            </div>
            <button type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg remove-item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    $('#order-items').append(newItem);
    itemIndex++;
    updateRemoveButtons();
});

$(document).on('change', '.product-select', function() {
    const price = $(this).find(':selected').data('price') || 0;
    $(this).closest('.order-item').find('.price-input').val(price.toFixed(2));
});

$(document).on('click', '.remove-item', function() {
    $(this).closest('.order-item').remove();
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const count = $('.order-item').length;
    $('.remove-item').toggle(count > 1);
}

updateRemoveButtons();
</script>
@endpush
