<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && $user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);
            
            if ($tenant && !$tenant->is_active) {
                return response()->json(['error' => 'Your tenant account is inactive'], 403);
            }
            
            app()->instance('tenant', $tenant);
        }
        
        return $next($request);
    }
}
