<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SalesAnalyticsService
{
    protected int $cacheMinutes = 30;

    public function getSalesOverview(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('sales_overview', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $query = Order::query();
            $this->applyFilters($query, $filters);

            return [
                'total_revenue' => $query->sum('total'),
                'total_orders' => $query->count(),
                'total_profit' => $query->sum('profit'),
                'average_order_value' => $query->avg('total') ?? 0,
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
            ];
        });
    }

    public function getSalesOverTime(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('sales_over_time', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $query = Order::query();
            $this->applyFilters($query, $filters);

            $groupBy = $filters['group_by'] ?? 'day';
            $dateFormat = match ($groupBy) {
                'month' => '%Y-%m',
                'week' => '%x-W%v',
                'year' => '%Y',
                default => '%Y-%m-%d',
            };

            $data = $query
                ->selectRaw("DATE_FORMAT(order_date, '{$dateFormat}') as period")
                ->selectRaw('SUM(total) as revenue')
                ->selectRaw('SUM(profit) as profit')
                ->selectRaw('COUNT(*) as orders')
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            return [
                'labels' => $data->pluck('period')->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'profit' => $data->pluck('profit')->toArray(),
                'orders' => $data->pluck('orders')->toArray(),
            ];
        });
    }

    public function getRevenueByCategory(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('revenue_by_category', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $query = OrderItem::query();

            if (! empty($filters['start_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '>=', $filters['start_date']);
                });
            }

            if (! empty($filters['end_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '<=', $filters['end_date']);
                });
            }

            $data = $query
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category')
                ->selectRaw('SUM(order_items.subtotal) as revenue')
                ->selectRaw('SUM(order_items.profit) as profit')
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('revenue')
                ->get();

            return [
                'categories' => $data->pluck('category')->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'profit' => $data->pluck('profit')->toArray(),
            ];
        });
    }

    public function getRevenueByProduct(array $filters = [], int $limit = 10): array
    {
        $cacheKey = $this->getCacheKey('revenue_by_product', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters, $limit) {
            $query = OrderItem::query();

            if (! empty($filters['start_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '>=', $filters['start_date']);
                });
            }

            if (! empty($filters['end_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '<=', $filters['end_date']);
                });
            }

            $data = $query
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('products.name as product')
                ->selectRaw('SUM(order_items.subtotal) as revenue')
                ->selectRaw('SUM(order_items.quantity) as quantity')
                ->selectRaw('SUM(order_items.profit) as profit')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get();

            return [
                'products' => $data->pluck('product')->toArray(),
                'revenue' => $data->pluck('revenue')->toArray(),
                'quantity' => $data->pluck('quantity')->toArray(),
                'profit' => $data->pluck('profit')->toArray(),
            ];
        });
    }

    public function getSalesDistribution(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('sales_distribution', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $query = OrderItem::query();

            if (! empty($filters['start_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '>=', $filters['start_date']);
                });
            }

            if (! empty($filters['end_date'])) {
                $query->whereHas('order', function ($q) use ($filters) {
                    $q->whereDate('order_date', '<=', $filters['end_date']);
                });
            }

            $data = $query
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as name')
                ->selectRaw('SUM(order_items.subtotal) as value')
                ->groupBy('categories.id', 'categories.name')
                ->get();

            return [
                'names' => $data->pluck('name')->toArray(),
                'values' => $data->pluck('value')->toArray(),
            ];
        });
    }

    public function getDailySalesHeatmap(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('daily_sales_heatmap', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $query = Order::query();
            $this->applyFilters($query, $filters);

            $data = $query
                ->selectRaw('DAYNAME(order_date) as day')
                ->selectRaw('HOUR(order_date) as hour')
                ->selectRaw('SUM(total) as value')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('day', 'hour')
                ->get();

            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $hours = range(0, 23);

            $heatmapData = [];
            foreach ($days as $day) {
                foreach ($hours as $hour) {
                    $match = $data->firstWhere(fn ($d) => $d->day === $day && $d->hour == $hour);
                    $heatmapData[] = [
                        $hour,
                        array_search($day, $days),
                        $match ? (float) $match->value : 0,
                    ];
                }
            }

            return [
                'hours' => $hours,
                'days' => $days,
                'data' => $heatmapData,
            ];
        });
    }

    public function getTopCustomers(array $filters = [], int $limit = 10): array
    {
        $cacheKey = $this->getCacheKey('top_customers', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters, $limit) {
            $query = Order::query();
            $this->applyFilters($query, $filters);

            $data = $query
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->selectRaw('customers.name as customer')
                ->selectRaw('customers.email as email')
                ->selectRaw('SUM(orders.total) as total_spent')
                ->selectRaw('COUNT(orders.id) as order_count')
                ->groupBy('customers.id', 'customers.name', 'customers.email')
                ->orderByDesc('total_spent')
                ->limit($limit)
                ->get();

            return [
                'customers' => $data->pluck('customer')->toArray(),
                'emails' => $data->pluck('email')->toArray(),
                'total_spent' => $data->pluck('total_spent')->toArray(),
                'order_count' => $data->pluck('order_count')->toArray(),
            ];
        });
    }

    public function getTrendIndicators(array $filters = []): array
    {
        $cacheKey = $this->getCacheKey('trend_indicators', $filters);

        return Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($filters) {
            $currentPeriod = Order::query();
            $previousPeriod = Order::query();

            $this->applyFilters($currentPeriod, $filters);

            $previousFilters = $filters;
            if (isset($previousFilters['start_date']) && isset($previousFilters['end_date'])) {
                $start = Carbon::parse($previousFilters['start_date']);
                $end = Carbon::parse($previousFilters['end_date']);
                $diff = $start->diffInDays($end);

                $previousFilters['start_date'] = $start->subDays($diff)->toDateString();
                $previousFilters['end_date'] = $end->subDays($diff + 1)->toDateString();
            }

            $this->applyFilters($previousPeriod, $previousFilters);

            $currentRevenue = $currentPeriod->sum('total');
            $previousRevenue = $previousPeriod->sum('total');
            $revenueChange = $previousRevenue > 0
                ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100
                : 0;

            $currentOrders = $currentPeriod->count();
            $previousOrders = $previousPeriod->count();
            $ordersChange = $previousOrders > 0
                ? (($currentOrders - $previousOrders) / $previousOrders) * 100
                : 0;

            $currentProfit = $currentPeriod->sum('profit');
            $previousProfit = $previousPeriod->sum('profit');
            $profitChange = $previousProfit > 0
                ? (($currentProfit - $previousProfit) / $previousProfit) * 100
                : 0;

            return [
                'revenue' => [
                    'value' => $currentRevenue,
                    'change' => round($revenueChange, 1),
                    'trend' => $revenueChange >= 0 ? 'up' : 'down',
                ],
                'orders' => [
                    'value' => $currentOrders,
                    'change' => round($ordersChange, 1),
                    'trend' => $ordersChange >= 0 ? 'up' : 'down',
                ],
                'profit' => [
                    'value' => $currentProfit,
                    'change' => round($profitChange, 1),
                    'trend' => $profitChange >= 0 ? 'up' : 'down',
                ],
            ];
        });
    }

    protected function applyFilters($query, array $filters, ?string $relation = null): void
    {
        $tablePrefix = $relation ? "{$relation}s." : '';

        if (! empty($filters['start_date'])) {
            $query->whereDate("{$tablePrefix}order_date", '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate("{$tablePrefix}order_date", '<=', $filters['end_date']);
        }

        if (! empty($filters['category_id'])) {
            if ($relation === 'order') {
                $query->whereHas('product', function ($q) use ($filters) {
                    $q->where('category_id', $filters['category_id']);
                });
            } else {
                $query->whereHas('items.product', function ($q) use ($filters) {
                    $q->where('category_id', $filters['category_id']);
                });
            }
        }

        if (! empty($filters['product_id'])) {
            if ($relation === 'order') {
                $query->whereHas('items', function ($q) use ($filters) {
                    $q->where('product_id', $filters['product_id']);
                });
            } else {
                $query->whereHas('items', function ($q) use ($filters) {
                    $q->where('product_id', $filters['product_id']);
                });
            }
        }
    }

    protected function getCacheKey(string $type, array $filters): string
    {
        $tenant = app('tenant');
        $tenantId = $tenant ? $tenant->id : 'guest';

        return "analytics_{$tenantId}_{$type}_".md5(json_encode($filters));
    }

    public function clearCache(): void
    {
        Cache::flush();
    }
}
