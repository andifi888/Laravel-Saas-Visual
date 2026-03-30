<?php

namespace App\Providers;

use App\Services\ExportService;
use App\Services\OrderService;
use App\Services\ReportService;
use App\Services\SalesAnalyticsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('tenant', function () {
            return null;
        });

        $this->app->singleton(SalesAnalyticsService::class, function () {
            return new SalesAnalyticsService;
        });

        $this->app->singleton(ExportService::class, function () {
            return new ExportService;
        });

        $this->app->singleton(OrderService::class, function () {
            return new OrderService;
        });

        $this->app->singleton(ReportService::class, function () {
            return new ReportService;
        });
    }

    public function boot(): void
    {
        //
    }
}
