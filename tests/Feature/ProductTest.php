<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
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

    public function test_user_can_view_products(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'sku' => 'TEST001',
            'price' => 99.99,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_user_can_create_product(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'category_id' => $category->id,
            'name' => 'New Product',
            'price' => 49.99,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_user_can_update_product(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Original Name',
            'sku' => 'TEST001',
            'price' => 99.99,
        ]);

        $response = $this->actingAs($this->user)->put(route('products.update', $product->id), [
            'category_id' => $category->id,
            'name' => 'Updated Name',
            'price' => 149.99,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }

    public function test_user_can_delete_product(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'To Delete',
            'sku' => 'TEST001',
            'price' => 99.99,
        ]);

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
