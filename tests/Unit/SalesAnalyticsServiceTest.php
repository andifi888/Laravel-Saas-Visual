<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\SalesAnalyticsService;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesAnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected SalesAnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::create(['name' => 'Test Tenant']);
        $this->service = new SalesAnalyticsService();
    }

    public function test_get_sales_overview_returns_correct_structure(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Customer',
            'email' => 'test@test.com',
        ]);

        Order::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'total' => 1000,
            'profit' => 200,
            'order_date' => now(),
        ]);

        $overview = $this->service->getSalesOverview();

        $this->assertArrayHasKey('total_revenue', $overview);
        $this->assertArrayHasKey('total_orders', $overview);
        $this->assertArrayHasKey('total_profit', $overview);
        $this->assertEquals(1000, $overview['total_revenue']);
    }

    public function test_trend_indicators_calculates_percentage_change(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Customer',
            'email' => 'test@test.com',
        ]);

        Order::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'total' => 1000,
            'profit' => 200,
            'order_date' => now(),
        ]);

        $trends = $this->service->getTrendIndicators();

        $this->assertArrayHasKey('revenue', $trends);
        $this->assertArrayHasKey('orders', $trends);
        $this->assertArrayHasKey('profit', $trends);
    }
}
