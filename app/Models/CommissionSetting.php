<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    use HasFactory;

    protected $table = 'ihm_m_commission_settings';

    protected $fillable = [
        'marketing_id',
        'cluster_id',
        'commission_percentage',
        'fixed_amount',
        'commission_type',
        'valid_from',
        'valid_until',
        'is_active',
        'description',
        'created_id',
        'updated_id',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function marketing()
    {
        return $this->belongsTo(Marketing::class, 'marketing_id');
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('marketing_id')->whereNull('cluster_id');
    }

    public function scopeForMarketing($query, $marketingId)
    {
        return $query->where('marketing_id', $marketingId);
    }

    public function scopeForCluster($query, $clusterId)
    {
        return $query->where('cluster_id', $clusterId);
    }

    // Helper Methods
    public function isGlobal(): bool
    {
        return is_null($this->marketing_id) && is_null($this->cluster_id);
    }

    public function isValid(): bool
    {
        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return $this->is_active;
    }

    public function getTypeBadgeClass(): string
    {
        return match ($this->commission_type) {
            'Percentage' => 'bg-blue-100 text-blue-700',
            'Fixed' => 'bg-green-100 text-green-700',
            'Both' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
