@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 py-12 px-4">
    <div class="max-w-md w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Create Account</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Start your 14-day free trial</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="input-custom w-full @error('name') border-red-500 @enderror"
                           placeholder="John Doe">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="input-custom w-full @error('email') border-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                           class="input-custom w-full @error('company_name') border-red-500 @enderror"
                           placeholder="Your Company Inc.">
                    @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="input-custom w-full @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="input-custom w-full"
                           placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn-primary w-full py-3">
                    Create Account
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 dark:text-gray-400">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Sign in</a>
                </p>
            </div>
            
            <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                By signing up, you agree to our Terms of Service and Privacy Policy
            </p>
        </div>
    </div>
</div>
@endsection
