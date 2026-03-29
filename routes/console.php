<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('setup:demo', function () {
    $this->call('migrate:fresh');
    $this->call('db:seed', ['--class' => 'RolePermissionSeeder']);
    $this->call('db:seed');
    $this->info('Demo setup complete! You can now login with admin@saleviz.com / password');
})->purpose('Setup demo data');

Artisan::command('clear:all', function () {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->call('optimize:clear');
    $this->info('All caches cleared!');
})->purpose('Clear all caches');

// Schedule tasks
Schedule::command('queue:prune-failed')->daily();
