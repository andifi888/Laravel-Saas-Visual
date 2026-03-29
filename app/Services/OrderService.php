<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::findOrFail($data['customer_id']);
            
            $order = Order::create([
                'tenant_id' => $data['tenant_id'],
                'customer_id' => $data['customer_id'],
                'user_id' => $data['user_id'] ?? auth()->id(),
                'status' => $data['status'] ?? 'pending',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_status' => $data['payment_status'] ?? 'pending',
                'tax' => $data['tax'] ?? 0,
                'shipping_cost' => $data['shipping_cost'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'order_date' => $data['order_date'] ?? now(),
            ]);
            
            $subtotal = 0;
            $profit = 0;
            
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                $orderItem = OrderItem::create([
                    'tenant_id' => $data['tenant_id'],
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => $item['price'] ?? $product->price,
                    'cost' => $product->cost,
                    'quantity' => $item['quantity'],
                    'subtotal' => ($item['price'] ?? $product->price) * $item['quantity'],
                    'profit' => (($item['price'] ?? $product->price) - $product->cost) * $item['quantity'],
                ]);
                
                $subtotal += $orderItem->subtotal;
                $profit += $orderItem->profit;
                
                $product->decrement('stock', $item['quantity']);
            }
            
            $order->update([
                'subtotal' => $subtotal,
                'profit' => $profit,
                'total' => $subtotal + $order->tax + $order->shipping_cost - $order->discount,
            ]);
            
            $customer->updateStats();
            
            return $order->load('items.product', 'customer');
        });
    }

    public function updateOrder(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            $order->update([
                'status' => $data['status'] ?? $order->status,
                'payment_method' => $data['payment_method'] ?? $order->payment_method,
                'payment_status' => $data['payment_status'] ?? $order->payment_status,
                'tax' => $data['tax'] ?? $order->tax,
                'shipping_cost' => $data['shipping_cost'] ?? $order->shipping_cost,
                'discount' => $data['discount'] ?? $order->discount,
                'notes' => $data['notes'] ?? $order->notes,
            ]);
            
            $order->update([
                'total' => $order->subtotal + $order->tax + $order->shipping_cost - $order->discount,
            ]);
            
            if ($order->customer) {
                $order->customer->updateStats();
            }
            
            return $order->fresh('items.product', 'customer');
        });
    }

    public function cancelOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            
            $order->update(['status' => 'cancelled']);
            
            if ($order->customer) {
                $order->customer->updateStats();
            }
            
            return $order;
        });
    }

    public function deleteOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            
            $order->items()->delete();
            
            if ($order->customer) {
                $order->customer->updateStats();
            }
            
            return $order->delete();
        });
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        
        if ($status === 'delivered') {
            foreach ($order->items as $item) {
                ActivityLog::log("Order {$order->order_number} delivered", [
                    'order_id' => $order->id,
                    'status' => $status,
                ]);
            }
        }
        
        return $order;
    }
}
