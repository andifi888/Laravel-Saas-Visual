@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Welcome Back</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Sign in to your account</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="input-custom w-full @error('email') border-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
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
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn-primary w-full py-3">
                    Sign In
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">Sign up</a>
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-4">Demo Credentials</p>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <p class="font-medium text-gray-700 dark:text-gray-300">Admin</p>
                        <p class="text-gray-500 dark:text-gray-400">admin@saleviz.com</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <p class="font-medium text-gray-700 dark:text-gray-300">Manager</p>
                        <p class="text-gray-500 dark:text-gray-400">manager@saleviz.com</p>
                    </div>
                </div>
                <p class="text-center text-xs text-gray-400 mt-3">Password: password</p>
            </div>
        </div>
    </div>
</div>
@endsection
