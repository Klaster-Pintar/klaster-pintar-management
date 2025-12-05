<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingCluster extends Model
{
    use HasFactory;

    protected $table = 'ihm_t_marketing_clusters';

    protected $fillable = [
        'marketing_id',
        'cluster_id',
        'join_date',
        'commission_percentage',
        'commission_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'join_date' => 'date',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'Cancelled');
    }

    // Helper Methods
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'Active' => 'bg-green-100 text-green-700',
            'Completed' => 'bg-blue-100 text-blue-700',
            'Cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
