# SalesViz SaaS - Data Visualization Dashboard for Sales Analytics

A complete, production-ready SaaS web application for Data Visualization Dashboard for Sales Analytics built with **Laravel 12**.

## Features

### Core Features
- **Authentication System** - Login, Register, Forgot Password with Email Verification
- **Role & Permission System** - Admin, Manager, Analyst, Viewer roles with granular permissions
- **Multi-Tenant Architecture** - Complete data isolation per tenant
- **Sales Data Module** - Full CRUD for Products, Categories, Customers, Orders, Order Items
- **Data Visualization Dashboard** - Interactive charts using Apache ECharts
- **RESTful API** - Full API layer with Laravel Sanctum authentication
- **Export Functionality** - Export to CSV, Excel, PDF formats
- **Admin Panel** - User management, Role management, Tenant management
- **Dark/Light Mode** - Theme switching with persistent preference

### Charts Included
- Line Chart - Sales over time
- Bar Chart - Revenue by product/category
- Pie Chart - Sales distribution
- Heatmap - Daily sales activity
- Area Chart - Profit trends
- Top Customers visualization

### Security Features
- CSRF Protection
- XSS Prevention
- SQL Injection Protection
- Form Validation on all inputs
- Rate Limiting
- Secure Password Hashing

## Requirements

- PHP 8.2+
- MySQL 8.0+ or SQLite
- Composer 2.x
- Node.js 20+
- Laragon (recommended) or XAMPP/WAMP

## Installation

### 1. Create Laravel Project (if starting fresh)

```bash
composer create-project laravel/laravel saas-visualization
cd saas-visualization

# Copy all project files from this template
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Environment Configuration

Create `.env` file:

```bash
cp .env.example .env
```

Update `.env` with your database credentials:

```env
APP_NAME="SalesViz SaaS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas_visualization
DB_USERNAME=webdev
DB_PASSWORD=12345ui

CACHE_STORE=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
```

### 4. Generate Keys

```bash
php artisan key:generate
```

### 5. Create Database

Create the database in MySQL:

```sql
CREATE DATABASE saas_visualization CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database

```bash
php artisan db:seed
```

Or run specific seeders:

```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=DatabaseSeeder
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Clear Cache

```bash
php artisan optimize:clear
```

## Running the Application

### Using Laragon (Recommended)
1. Start Laragon
2. Add project to Laragon's www folder
3. Access via `http://saas-visualization.test`

### Using PHP Artisan Server

```bash
php artisan serve
```

Access at `http://localhost:8000`

### Queue Worker (for report generation)

```bash
php artisan queue:work
```

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@saleviz.com | password |
| Manager | manager@saleviz.com | password |
| Analyst | analyst@saleviz.com | password |
| Viewer | viewer@saleviz.com | password |

## API Documentation

### Authentication

```bash
# Login
POST /api/login
{
    "email": "admin@saleviz.com",
    "password": "password"
}

# Register
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Endpoints

```
GET  /api/dashboard/overview
GET  /api/dashboard/charts
GET  /api/products
POST /api/products
GET  /api/products/{id}
PUT  /api/products/{id}
DELETE /api/products/{id}
GET  /api/categories
POST /api/categories
GET  /api/customers
POST /api/customers
GET  /api/orders
POST /api/orders
GET  /api/orders/{id}
POST /api/orders/{id}/status
```

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # API Controllers
│   │   │   ├── Auth/          # Authentication Controllers
│   │   │   ├── Admin/         # Admin Controllers
│   │   │   └── Dashboard/      # Dashboard Controllers
│   │   ├── Middleware/         # Custom Middleware
│   │   └── Requests/           # Form Requests
│   ├── Models/                 # Eloquent Models
│   ├── Policies/                # Authorization Policies
│   ├── Services/               # Business Logic Services
│   ├── Jobs/                   # Queue Jobs
│   └── Notifications/          # Notifications
├── database/
│   ├── migrations/             # Database Migrations
│   ├── seeders/                # Database Seeders
│   └── factories/              # Model Factories
├── resources/
│   └── views/                  # Blade Views
│       ├── auth/
│       ├── admin/
│       ├── dashboard/
│       └── layouts/
├── routes/
│   ├── web.php
│   └── api.php
└── config/                     # Configuration Files
```

## Roles & Permissions

### Roles
- **Admin** - Full system access
- **Manager** - Sales management, reports
- **Analyst** - View dashboards, export reports
- **Viewer** - View only access

### Permissions
- `manage_users` - User management
- `manage_roles` - Role & permission management
- `view_dashboard` - View dashboard
- `manage_sales` - Sales operations
- `export_reports` - Export functionality
- `manage_products` - Product management
- `manage_customers` - Customer management
- `manage_orders` - Order management
- `view_reports` - Reports
- `manage_tenants` - Tenant management

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

## Performance Features

- Dashboard caching with configurable TTL
- Database query optimization
- Queue system for heavy operations
- Lazy loading for relationships
- DataTables for large datasets

## Artisan Commands

```bash
# Setup demo data
php artisan setup:demo

# Clear all caches
php artisan clear:all

# Generate report
php artisan make:report orders --format=csv
```

## Troubleshooting

### Common Issues

**Migration errors:**
```bash
php artisan migrate:fresh --seed
```

**Cache issues:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Permission errors (Linux/Mac):**
```bash
chmod -R 775 storage bootstrap/cache
```

## License

MIT License - Feel free to use this project for commercial purposes.

## Support

For issues and questions, please create an issue on the repository.
