<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory, TenantAware;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'product_id',
        'uuid',
        'product_name',
        'product_sku',
        'price',
        'cost',
        'quantity',
        'subtotal',
        'profit',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
            
            if (empty($model->product_name) && $model->product_id) {
                $model->product_name = $model->product->name;
            }
            
            if (empty($model->product_sku) && $model->product_id) {
                $model->product_sku = $model->product->sku;
            }
            
            if (empty($model->cost) && $model->product_id) {
                $model->cost = $model->product->cost;
            }
        });
        
        static::saved(function ($model) {
            $model->calculateTotals();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    public function getTotalCostAttribute(): float
    {
        return $this->cost * $this->quantity;
    }

    public function getItemProfitAttribute(): float
    {
        return ($this->price - $this->cost) * $this->quantity;
    }

    protected function calculateTotals(): void
    {
        $this->subtotal = $this->price * $this->quantity;
        $this->profit = $this->item_profit;
        
        if ($this->order) {
            $this->order->calculateTotals();
        }
    }
}
