<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClusterSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cluster_id',
        'package_id',
        'package_name',
        'package',
        'price',
        'total',
        'invoice_code',
        'months',
        'expired_at',
        'code',
        'active',
        'status',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'months' => 'integer',
        'expired_at' => 'date',
        'active' => 'boolean',
    ];

    public function getTable()
    {
        $this->table = env('TABLE_PREFIX', 'ihm_') . 't_subscriptions';
        return $this->table;
    }

    /**
     * Relationship to Cluster
     */
    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    /**
     * Relationship to creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    /**
     * Relationship to updater
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)->where('status', 'ACTIVE');
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<', now());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired()
    {
        return $this->expired_at < now();
    }

    /**
     * Get days remaining
     */
    public function daysRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->expired_at);
    }
}
