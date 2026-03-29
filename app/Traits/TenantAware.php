<?php

namespace App\Traits;

use App\Models\Tenant;

trait TenantAware
{
    protected static function bootTenantAware()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->tenant_id)) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        static::addGlobalScope('tenant', function ($builder) {
            $tenant = app('tenant');
            if ($tenant) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenant->id);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('tenant_id')) {
                throw new \Exception('Tenant cannot be changed');
            }
        });
    }

    public function scopeWithoutTenantScope($query)
    {
        return $query->withoutGlobalScope('tenant');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
