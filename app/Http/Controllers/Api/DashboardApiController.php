<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    protected SalesAnalyticsService $analyticsService;
    
    public function __construct(SalesAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }
    
    public function overview(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $overview = $this->analyticsService->getSalesOverview($filters);
        $trends = $this->analyticsService->getTrendIndicators($filters);
        
        return response()->json([
            'success' => true,
            'data' => [
                'overview' => $overview,
                'trends' => $trends,
            ],
        ]);
    }
    
    public function charts(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        
        return response()->json([
            'success' => true,
            'data' => [
                'sales_over_time' => $this->analyticsService->getSalesOverTime($filters),
                'revenue_by_category' => $this->analyticsService->getRevenueByCategory($filters),
                'revenue_by_product' => $this->analyticsService->getRevenueByProduct($filters, 10),
                'sales_distribution' => $this->analyticsService->getSalesDistribution($filters),
                'daily_heatmap' => $this->analyticsService->getDailySalesHeatmap($filters),
                'top_customers' => $this->analyticsService->getTopCustomers($filters, 10),
            ],
        ]);
    }
    
    protected function getFilters(Request $request): array
    {
        return [
            'start_date' => $request->get('start_date', now()->startOfMonth()->toDateString()),
            'end_date' => $request->get('end_date', now()->endOfMonth()->toDateString()),
            'category_id' => $request->get('category_id'),
            'product_id' => $request->get('product_id'),
            'group_by' => $request->get('group_by', 'day'),
        ];
    }
}
