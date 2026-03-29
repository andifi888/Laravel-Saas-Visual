<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderApiController extends Controller
{
    protected OrderService $orderService;
    
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    public function index(Request $request): JsonResponse
    {
        $query = Order::with('customer');
        
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
        
        $orders = $query->orderByDesc('order_date')
            ->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
    
    public function show(Order $order): JsonResponse
    {
        $order->load('customer', 'items.product');
        
        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['sometimes', 'in:pending,processing,shipped,delivered,cancelled,refunded'],
            'payment_method' => ['nullable', 'string'],
            'payment_status' => ['nullable', 'string'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'order_date' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);
        
        try {
            $validated['tenant_id'] = app('tenant')->id;
            $validated['user_id'] = auth()->id();
            
            $order = $this->orderService->createOrder($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled,refunded'],
        ]);
        
        $order = $this->orderService->updateOrderStatus($order, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order,
        ]);
    }
    
    public function cancel(Order $order): JsonResponse
    {
        $order = $this->orderService->cancelOrder($order);
        
        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order,
        ]);
    }
}
