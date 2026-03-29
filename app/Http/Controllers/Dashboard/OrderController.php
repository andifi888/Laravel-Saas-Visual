<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    protected OrderService $orderService;
    
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    public function index(Request $request): View
    {
        $query = Order::with('customer');
        
        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }
        
        $orders = $query->orderByDesc('order_date')->paginate(20);
        
        return view('dashboard.orders.index', compact('orders'));
    }
    
    public function create(): View
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        
        return view('dashboard.orders.create', compact('customers', 'products'));
    }
    
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['tenant_id'] = app('tenant')->id;
            $data['user_id'] = auth()->id();
            
            $order = $this->orderService->createOrder($data);
            
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    
    public function show(Order $order): View
    {
        $order->load('customer', 'items.product', 'user');
        return view('dashboard.orders.show', compact('order'));
    }
    
    public function edit(Order $order): View
    {
        $customers = Customer::where('is_active', true)->get();
        return view('dashboard.orders.edit', compact('order', 'customers'));
    }
    
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        try {
            $this->orderService->updateOrder($order, $request->validated());
            
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);
        
        $this->orderService->updateOrderStatus($order, $request->status);
        
        return back()->with('success', 'Order status updated successfully');
    }
    
    public function destroy(Order $order): RedirectResponse
    {
        if (!in_array($order->status, ['cancelled', 'pending'])) {
            return back()->with('error', 'Only pending or cancelled orders can be deleted');
        }
        
        $this->orderService->deleteOrder($order);
        
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully');
    }
    
    public function cancel(Order $order): RedirectResponse
    {
        $this->orderService->cancelOrder($order);
        
        return back()->with('success', 'Order cancelled successfully');
    }
}
