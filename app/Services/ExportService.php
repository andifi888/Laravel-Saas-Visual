<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class ExportService
{
    public function exportToCsv(array $data, string $filename): string
    {
        $path = "exports/{$filename}.csv";
        $fullPath = storage_path("app/public/{$path}");
        
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $handle = fopen($fullPath, 'w');
        
        if (!empty($data)) {
            fputcsv($handle, array_keys($data[0]));
            
            foreach ($data as $row) {
                fputcsv($handle, array_values($row));
            }
        }
        
        fclose($handle);
        
        return $path;
    }

    public function exportOrdersToExcel(array $filters = []): string
    {
        $query = Order::with(['customer', 'items.product']);
        $this->applyFilters($query, $filters);
        
        $orders = $query->get();
        
        $data = $orders->map(function ($order) {
            return [
                'Order Number' => $order->order_number,
                'Customer' => $order->customer->name,
                'Email' => $order->customer->email,
                'Status' => ucfirst($order->status),
                'Items Count' => $order->items->count(),
                'Subtotal' => number_format($order->subtotal, 2),
                'Tax' => number_format($order->tax, 2),
                'Shipping' => number_format($order->shipping_cost, 2),
                'Discount' => number_format($order->discount, 2),
                'Total' => number_format($order->total, 2),
                'Profit' => number_format($order->profit, 2),
                'Order Date' => $order->order_date->format('Y-m-d H:i:s'),
                'Created At' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
        
        return $this->exportToCsv($data, 'orders_' . now()->format('Y_m_d_His'));
    }

    public function exportProductsToExcel(array $filters = []): string
    {
        $query = Product::with('category');
        
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        
        $products = $query->get();
        
        $data = $products->map(function ($product) {
            return [
                'SKU' => $product->sku,
                'Name' => $product->name,
                'Category' => $product->category->name,
                'Price' => number_format($product->price, 2),
                'Cost' => number_format($product->cost, 2),
                'Stock' => $product->stock,
                'Total Sales' => number_format($product->total_sales, 2),
                'Quantity Sold' => $product->total_quantity_sold,
                'Profit Margin' => number_format($product->profit_margin, 2) . '%',
                'Status' => $product->is_active ? 'Active' : 'Inactive',
            ];
        })->toArray();
        
        return $this->exportToCsv($data, 'products_' . now()->format('Y_m_d_His'));
    }

    public function exportCustomersToExcel(array $filters = []): string
    {
        $query = Customer::with('orders');
        
        $customers = $query->get();
        
        $data = $customers->map(function ($customer) {
            return [
                'Name' => $customer->name,
                'Email' => $customer->email,
                'Phone' => $customer->phone,
                'Company' => $customer->company,
                'City' => $customer->city,
                'Country' => $customer->country,
                'Total Orders' => $customer->total_orders,
                'Total Spent' => number_format($customer->total_spent, 2),
                'Average Order Value' => number_format($customer->average_order_value, 2),
                'Last Order' => $customer->last_order_at ? $customer->last_order_at->format('Y-m-d') : 'N/A',
                'Status' => $customer->is_active ? 'Active' : 'Inactive',
            ];
        })->toArray();
        
        return $this->exportToCsv($data, 'customers_' . now()->format('Y_m_d_His'));
    }

    public function exportAnalyticsReport(array $filters = []): string
    {
        $analyticsService = new SalesAnalyticsService();
        
        $overview = $analyticsService->getSalesOverview($filters);
        $salesOverTime = $analyticsService->getSalesOverTime($filters);
        $revenueByCategory = $analyticsService->getRevenueByCategory($filters);
        $topProducts = $analyticsService->getRevenueByProduct($filters, 10);
        $topCustomers = $analyticsService->getTopCustomers($filters, 10);
        $trends = $analyticsService->getTrendIndicators($filters);
        
        $data = [];
        
        $data[] = ['Sales Analytics Report'];
        $data[] = ['Generated', now()->format('Y-m-d H:i:s')];
        $data[] = [];
        
        $data[] = ['Overview'];
        $data[] = ['Total Revenue', '$' . number_format($overview['total_revenue'], 2)];
        $data[] = ['Total Orders', number_format($overview['total_orders'])];
        $data[] = ['Total Profit', '$' . number_format($overview['total_profit'], 2)];
        $data[] = ['Average Order Value', '$' . number_format($overview['average_order_value'], 2)];
        $data[] = [];
        
        $data[] = ['Trend Indicators'];
        $data[] = ['Revenue Change', ($trends['revenue']['change'] >= 0 ? '+' : '') . $trends['revenue']['change'] . '%'];
        $data[] = ['Orders Change', ($trends['orders']['change'] >= 0 ? '+' : '') . $trends['orders']['change'] . '%'];
        $data[] = ['Profit Change', ($trends['profit']['change'] >= 0 ? '+' : '') . $trends['profit']['change'] . '%'];
        $data[] = [];
        
        $data[] = ['Top Products by Revenue'];
        foreach ($topProducts['products'] as $index => $product) {
            $data[] = [$product, '$' . number_format($topProducts['revenue'][$index], 2)];
        }
        $data[] = [];
        
        $data[] = ['Revenue by Category'];
        foreach ($revenueByCategory['categories'] as $index => $category) {
            $data[] = [$category, '$' . number_format($revenueByCategory['revenue'][$index], 2)];
        }
        
        return $this->exportToCsv($data, 'analytics_report_' . now()->format('Y_m_d_His'));
    }

    protected function applyFilters($query, array $filters): void
    {
        if (!empty($filters['start_date'])) {
            $query->whereDate('order_date', '>=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query->whereDate('order_date', '<=', $filters['end_date']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
