@extends('layouts.app')
@section('title', 'Edit Profile')

@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your account settings')

@push('styles')
<style>
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Profile Information</h2>
            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                {{ $user->roles->first()?->name ?? 'User' }}
            </span>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="flex items-center space-x-6 mb-6">
                <div class="shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-preview">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-medium">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <label for="avatar" class="btn-secondary text-sm px-4 py-2 cursor-pointer">
                        <i class="fas fa-upload mr-2"></i>Change Avatar
                    </label>
                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="this.form.submit()">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">JPG, PNG or GIF. Max 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="input-custom w-full @error('name') border-red-500 @enderror"
                           required autofocus>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="input-custom w-full @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="input-custom w-full @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Timezone
                    </label>
                    <select id="timezone" name="timezone" class="input-custom w-full">
                        <option value="UTC" {{ old('timezone', $user->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ old('timezone', $user->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time (US)</option>
                        <option value="America/Chicago" {{ old('timezone', $user->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time (US)</option>
                        <option value="America/Denver" {{ old('timezone', $user->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time (US)</option>
                        <option value="America/Los_Angeles" {{ old('timezone', $user->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US)</option>
                        <option value="Europe/London" {{ old('timezone', $user->timezone) == 'Europe/London' ? 'selected' : '' }}>London</option>
                        <option value="Europe/Paris" {{ old('timezone', $user->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                        <option value="Asia/Tokyo" {{ old('timezone', $user->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                        <option value="Asia/Shanghai" {{ old('timezone', $user->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai</option>
                        <option value="Asia/Kolkata" {{ old('timezone', $user->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>Mumbai</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>Member since: {{ $user->created_at->format('M d, Y') }}</p>
                        @if($user->email_verified_at)
                            <p class="text-green-600 dark:text-green-400 mt-1">
                                <i class="fas fa-check-circle mr-1"></i> Email verified
                            </p>
                        @else
                            <p class="text-yellow-600 dark:text-yellow-400 mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i> Email not verified
                            </p>
                        @endif
                    </div>
                    <button type="submit" class="btn-primary px-6 py-2">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Security</h3>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 dark:text-gray-300 font-medium">Update Password</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ensure your account is using a long, random password</p>
            </div>
            <a href="{{ route('password.update') }}" class="btn-secondary px-4 py-2">
                <i class="fas fa-lock mr-2"></i>Change Password
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tenant Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tenant</p>
                <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $user->tenant->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Role</p>
                <p class="text-gray-800 dark:text-gray-200 font-medium">
                    @foreach($user->roles as $role)
                        <span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs rounded mr-1">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
