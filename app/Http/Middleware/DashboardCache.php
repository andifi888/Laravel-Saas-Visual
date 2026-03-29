<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class DashboardCache
{
    public function handle(Request $request, Closure $next, int $minutes = 30): Response
    {
        if ($request->isMethod('GET') && !$request->ajax()) {
            $cacheKey = $this->getCacheKey($request);
            
            if (Cache::has($cacheKey)) {
                return response(Cache::get($cacheKey));
            }
            
            $response = $next($request);
            
            if ($response->isSuccessful()) {
                Cache::put($cacheKey, $response->getContent(), $minutes * 60);
            }
            
            return $response;
        }
        
        return $next($request);
    }
    
    protected function getCacheKey(Request $request): string
    {
        $tenant = app('tenant');
        $tenantId = $tenant ? $tenant->id : 'guest';
        return "dashboard_{$tenantId}_{$request->path()}_" . md5(json_encode($request->all()));
    }
}
