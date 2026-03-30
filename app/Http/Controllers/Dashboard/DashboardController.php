<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Services\SalesAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected SalesAnalyticsService $analyticsService;

    public function __construct(SalesAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(Request $request): View
    {
        $filters = [
            'start_date' => $request->get('start_date', now()->startOfMonth()->toDateString()),
            'end_date' => $request->get('end_date', now()->endOfMonth()->toDateString()),
            'category_id' => $request->get('category_id'),
            'product_id' => $request->get('product_id'),
            'group_by' => $request->get('group_by', 'day'),
        ];

        $overview = $this->analyticsService->getSalesOverview($filters);
        $trends = $this->analyticsService->getTrendIndicators($filters);

        return view('dashboard.index', compact('overview', 'trends', 'filters'));
    }

    public function charts(Request $request): JsonResponse
    {
        $filters = [
            'start_date' => $request->get('start_date', now()->startOfMonth()->toDateString()),
            'end_date' => $request->get('end_date', now()->endOfMonth()->toDateString()),
            'category_id' => $request->get('category_id'),
            'product_id' => $request->get('product_id'),
            'group_by' => $request->get('group_by', 'day'),
        ];

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

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,excel,pdf',
            'type' => 'required|in:orders,products,customers,analytics',
        ]);

        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $exportService = app(ExportService::class);

        $filename = match ($request->type) {
            'orders' => $exportService->exportOrdersToExcel($filters),
            'products' => $exportService->exportProductsToExcel($filters),
            'customers' => $exportService->exportCustomersToExcel($filters),
            'analytics' => $exportService->exportAnalyticsReport($filters),
        };

        return response()->download(storage_path("app/public/{$filename}"));
    }
}
