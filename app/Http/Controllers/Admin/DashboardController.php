<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_tenants' => Tenant::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total'),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
        ];
        
        $recentOrders = Order::with('customer')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        $recentUsers = User::with('tenant')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }
}
