@extends('layouts.app')
@section('title', 'Create Customer')

@section('page-title', 'Customers')
@section('page-subtitle', 'Add new customer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="input-custom w-full @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="input-custom w-full @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                           class="input-custom w-full">
                </div>
                
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country') }}"
                           class="input-custom w-full">
                </div>
                
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                           class="input-custom w-full">
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('customers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg">Cancel</a>
                <button type="submit" class="btn-primary px-6 py-2">Create Customer</button>
            </div>
        </form>
    </div>
</div>
@endsection
