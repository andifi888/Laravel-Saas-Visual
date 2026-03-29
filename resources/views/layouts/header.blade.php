<header class="bg-white dark:bg-gray-800 shadow-sm mb-6">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center space-x-4">
            <button class="sidebar-toggle lg:hidden text-gray-500">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@yield('page-subtitle', 'Welcome back!')</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button id="theme-toggle" onclick="App.toggleTheme()" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-moon theme-icon"></i>
                </button>
            </div>
            
            <div class="relative">
                <button id="notification-btn" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition relative">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </button>
            </div>
            
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </div>
    </div>
</header>
