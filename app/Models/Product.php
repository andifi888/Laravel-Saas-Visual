<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes, TenantAware;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'uuid',
        'name',
        'sku',
        'description',
        'price',
        'cost',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'integer',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->price == 0) return 0;
        return (($this->price - $this->cost) / $this->price) * 100;
    }

    public function getTotalSalesAttribute(): float
    {
        return $this->orderItems()->sum('subtotal');
    }

    public function getTotalQuantitySoldAttribute(): int
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getTotalProfitAttribute(): float
    {
        return $this->orderItems()->sum('profit');
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
