<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->tenant = Tenant::create(['name' => 'Test Tenant']);
        $this->user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->user->assignRole('Admin');
    }

    public function test_user_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_dashboard_displays_sales_overview(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
        ]);

        Order::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'user_id' => $this->user->id,
            'total' => 1000.00,
            'profit' => 200.00,
            'order_date' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_charts_api(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard.charts'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'sales_over_time',
                'revenue_by_category',
                'revenue_by_product',
                'sales_distribution',
                'daily_heatmap',
                'top_customers',
            ],
        ]);
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
