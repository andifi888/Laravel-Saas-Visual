<?php

namespace App\Models;

use App\Traits\TenantAware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory, TenantAware;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'uuid',
        'name',
        'type',
        'format',
        'file_path',
        'status',
        'filters',
        'completed_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'completed_at' => 'datetime',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsProcessing(): void
    {
        $this->status = 'processing';
        $this->save();
    }

    public function markAsCompleted(string $filePath): void
    {
        $this->status = 'completed';
        $this->file_path = $filePath;
        $this->completed_at = now();
        $this->save();
    }

    public function markAsFailed(): void
    {
        $this->status = 'failed';
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
