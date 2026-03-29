<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory, SoftDeletes, TenantAware;

    protected $fillable = [
        'tenant_id',
        'uuid',
        'name',
        'email',
        'phone',
        'company',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'total_spent',
        'total_orders',
        'last_order_at',
        'is_active',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'total_orders' => 'integer',
        'last_order_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getAverageOrderValueAttribute(): float
    {
        return $this->total_orders > 0 
            ? $this->total_spent / $this->total_orders 
            : 0;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);
        
        return implode(', ', $parts);
    }

    public function updateStats(): void
    {
        $this->total_orders = $this->orders()->count();
        $this->total_spent = $this->orders()->sum('total');
        $this->last_order_at = $this->orders()->latest()->first()?->order_date;
        $this->save();
    }
}
