<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tenant;
use App\Services\SalesAnalyticsService;
use App\Services\ExportService;
use App\Services\OrderService;
use App\Services\ReportService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('tenant', function () {
            return auth()->check() ? auth()->user()->tenant : null;
        });

        $this->app->singleton(SalesAnalyticsService::class, function () {
            return new SalesAnalyticsService();
        });

        $this->app->singleton(ExportService::class, function () {
            return new ExportService();
        });

        $this->app->singleton(OrderService::class, function () {
            return new OrderService();
        });

        $this->app->singleton(ReportService::class, function () {
            return new ReportService();
        });
    }

    public function boot(): void
    {
        //
    }
}
