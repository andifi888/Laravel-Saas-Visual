<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Demo Company',
            'is_active' => true,
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin User',
            'email' => 'admin@saleviz.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        $manager = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Manager User',
            'email' => 'manager@saleviz.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $manager->assignRole('Manager');

        $analyst = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Analyst User',
            'email' => 'analyst@saleviz.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $analyst->assignRole('Analyst');

        $viewer = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Viewer User',
            'email' => 'viewer@saleviz.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $viewer->assignRole('Viewer');

        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items'],
            ['name' => 'Home & Garden', 'description' => 'Home improvement and garden supplies'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear'],
            ['name' => 'Books & Media', 'description' => 'Books, music, and entertainment'],
            ['name' => 'Health & Beauty', 'description' => 'Health and beauty products'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'tenant_id' => $tenant->id,
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active' => true,
            ]);
        }

        $products = [
            ['name' => 'Smartphone X Pro', 'category' => 'Electronics', 'price' => 999.99, 'cost' => 450.00, 'stock' => 150],
            ['name' => 'Wireless Earbuds', 'category' => 'Electronics', 'price' => 149.99, 'cost' => 45.00, 'stock' => 500],
            ['name' => 'Smart Watch', 'category' => 'Electronics', 'price' => 299.99, 'cost' => 120.00, 'stock' => 200],
            ['name' => 'Laptop Ultra', 'category' => 'Electronics', 'price' => 1299.99, 'cost' => 700.00, 'stock' => 75],
            ['name' => 'Cotton T-Shirt', 'category' => 'Clothing', 'price' => 29.99, 'cost' => 8.00, 'stock' => 1000],
            ['name' => 'Denim Jeans', 'category' => 'Clothing', 'price' => 79.99, 'cost' => 25.00, 'stock' => 400],
            ['name' => 'Running Shoes', 'category' => 'Clothing', 'price' => 119.99, 'cost' => 40.00, 'stock' => 300],
            ['name' => 'Winter Jacket', 'category' => 'Clothing', 'price' => 199.99, 'cost' => 70.00, 'stock' => 150],
            ['name' => 'Garden Tools Set', 'category' => 'Home & Garden', 'price' => 89.99, 'cost' => 30.00, 'stock' => 200],
            ['name' => 'LED Desk Lamp', 'category' => 'Home & Garden', 'price' => 49.99, 'cost' => 18.00, 'stock' => 350],
            ['name' => 'Yoga Mat Premium', 'category' => 'Sports & Outdoors', 'price' => 39.99, 'cost' => 12.00, 'stock' => 500],
            ['name' => 'Camping Tent', 'category' => 'Sports & Outdoors', 'price' => 249.99, 'cost' => 90.00, 'stock' => 100],
            ['name' => 'Bestseller Novel', 'category' => 'Books & Media', 'price' => 24.99, 'cost' => 6.00, 'stock' => 800],
            ['name' => 'Headphones Studio', 'category' => 'Books & Media', 'price' => 179.99, 'cost' => 60.00, 'stock' => 200],
            ['name' => 'Vitamin Pack', 'category' => 'Health & Beauty', 'price' => 34.99, 'cost' => 10.00, 'stock' => 600],
            ['name' => 'Organic Shampoo', 'category' => 'Health & Beauty', 'price' => 19.99, 'cost' => 5.00, 'stock' => 700],
        ];

        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();
            Product::create([
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
                'name' => $prod['name'],
                'sku' => strtoupper(Str::random(8)),
                'price' => $prod['price'],
                'cost' => $prod['cost'],
                'stock' => $prod['stock'],
                'is_active' => true,
            ]);
        }

        $customers = [
            ['name' => 'John Smith', 'email' => 'john.smith@email.com', 'phone' => '+1-555-0101', 'company' => 'Tech Corp', 'city' => 'New York', 'country' => 'USA'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@email.com', 'phone' => '+1-555-0102', 'company' => 'Design Studio', 'city' => 'Los Angeles', 'country' => 'USA'],
            ['name' => 'Michael Brown', 'email' => 'm.brown@email.com', 'phone' => '+1-555-0103', 'company' => 'Finance LLC', 'city' => 'Chicago', 'country' => 'USA'],
            ['name' => 'Emily Davis', 'email' => 'emily.d@email.com', 'phone' => '+1-555-0104', 'company' => 'Marketing Inc', 'city' => 'Houston', 'country' => 'USA'],
            ['name' => 'David Wilson', 'email' => 'd.wilson@email.com', 'phone' => '+1-555-0105', 'company' => 'Sales Pro', 'city' => 'Phoenix', 'country' => 'USA'],
            ['name' => 'Jennifer Taylor', 'email' => 'j.taylor@email.com', 'phone' => '+1-555-0106', 'company' => 'HR Solutions', 'city' => 'Philadelphia', 'country' => 'USA'],
            ['name' => 'Robert Anderson', 'email' => 'r.anderson@email.com', 'phone' => '+1-555-0107', 'company' => 'IT Services', 'city' => 'San Antonio', 'country' => 'USA'],
            ['name' => 'Lisa Martinez', 'email' => 'l.martinez@email.com', 'phone' => '+1-555-0108', 'company' => 'Legal Aid', 'city' => 'San Diego', 'country' => 'USA'],
            ['name' => 'James Garcia', 'email' => 'j.garcia@email.com', 'phone' => '+1-555-0109', 'company' => 'Real Estate', 'city' => 'Dallas', 'country' => 'USA'],
            ['name' => 'Maria Rodriguez', 'email' => 'm.rodriguez@email.com', 'phone' => '+1-555-0110', 'company' => 'Healthcare Plus', 'city' => 'San Jose', 'country' => 'USA'],
            ['name' => 'William Lee', 'email' => 'w.lee@email.com', 'phone' => '+1-555-0111', 'company' => 'Education First', 'city' => 'Austin', 'country' => 'USA'],
            ['name' => 'Patricia White', 'email' => 'p.white@email.com', 'phone' => '+1-555-0112', 'company' => 'Food Services', 'city' => 'Jacksonville', 'country' => 'USA'],
            ['name' => 'Christopher Harris', 'email' => 'c.harris@email.com', 'phone' => '+1-555-0113', 'company' => 'Construction Co', 'city' => 'Fort Worth', 'country' => 'USA'],
            ['name' => 'Nancy Clark', 'email' => 'n.clark@email.com', 'phone' => '+1-555-0114', 'company' => 'Media Group', 'city' => 'Columbus', 'country' => 'USA'],
            ['name' => 'Daniel Lewis', 'email' => 'd.lewis@email.com', 'phone' => '+1-555-0115', 'company' => 'Retail Chain', 'city' => 'Charlotte', 'country' => 'USA'],
        ];

        foreach ($customers as $cust) {
            Customer::create([
                'tenant_id' => $tenant->id,
                'name' => $cust['name'],
                'email' => $cust['email'],
                'phone' => $cust['phone'],
                'company' => $cust['company'],
                'city' => $cust['city'],
                'country' => $cust['country'],
                'is_active' => true,
            ]);
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
        
        for ($i = 0; $i < 100; $i++) {
            $customer = Customer::inRandomOrder()->first();
            $orderDate = Carbon::now()->subDays(rand(0, 365))->subHours(rand(0, 23));
            $status = $statuses[array_rand($statuses)];
            
            $orderItems = [];
            $numItems = rand(1, 5);
            $subtotal = 0;
            $profit = 0;
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = Product::inRandomOrder()->first();
                $quantity = rand(1, 5);
                $itemSubtotal = $product->price * $quantity;
                $itemProfit = ($product->price - $product->cost) * $quantity;
                
                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'cost' => $product->cost,
                    'subtotal' => $itemSubtotal,
                    'profit' => $itemProfit,
                ];
                
                $subtotal += $itemSubtotal;
                $profit += $itemProfit;
            }
            
            $tax = $subtotal * 0.08;
            $shipping = $subtotal > 100 ? 0 : 9.99;
            $discount = rand(0, 1) ? $subtotal * 0.05 : 0;
            $total = $subtotal + $tax + $shipping - $discount;
            
            $order = Order::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'user_id' => $admin->id,
                'status' => $status,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'profit' => $profit,
                'payment_method' => ['cash', 'card', 'paypal'][rand(0, 2)],
                'payment_status' => 'completed',
                'order_date' => $orderDate,
            ]);
            
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_sku' => $item['product']->sku,
                    'price' => $item['price'],
                    'cost' => $item['cost'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'profit' => $item['profit'],
                ]);
            }
            
            $customer->updateStats();
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@saleviz.com / password');
        $this->command->info('Manager: manager@saleviz.com / password');
        $this->command->info('Analyst: analyst@saleviz.com / password');
        $this->command->info('Viewer: viewer@saleviz.com / password');
    }
}
