<aside class="sidebar bg-white dark:bg-gray-800 shadow-lg">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-800 dark:text-white">SalesViz</span>
            </a>
            <button class="sidebar-toggle lg:hidden text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <nav class="p-4 space-y-2">
        <a href="{{ route('dashboard') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5"></i>
            <span>Dashboard</span>
        </a>
        
        <div class="pt-4 pb-2">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales</span>
        </div>
        
        <a href="{{ route('orders.index') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart w-5"></i>
            <span>Orders</span>
        </a>
        
        <a href="{{ route('customers.index') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="fas fa-users w-5"></i>
            <span>Customers</span>
        </a>
        
        <a href="{{ route('products.index') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-box w-5"></i>
            <span>Products</span>
        </a>
        
        <a href="{{ route('categories.index') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags w-5"></i>
            <span>Categories</span>
        </a>
        
        @can('manage_sales')
        <div class="pt-4 pb-2">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</span>
        </div>
        @endcan
        
        @role('Admin|Manager')
        <div class="pt-4 pb-2">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</span>
        </div>
        
        <a href="{{ route('admin.dashboard') }}" 
           class="nav-link-custom flex items-center space-x-3 {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            <i class="fas fa-cog w-5"></i>
            <span>Admin Panel</span>
        </a>
        @endrole
    </nav>
    
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="text-sm">
                    <p class="font-medium text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">{{ auth()->user()->roles->first()?->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-blue-500 transition" title="Profile">
                    <i class="fas fa-user"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-500 transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
