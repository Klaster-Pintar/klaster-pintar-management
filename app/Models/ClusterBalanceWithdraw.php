<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClusterBalanceWithdraw extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ihm_m_clusters_id',
        'transaction_type',
        'amount',
        'description',
        'is_valid',
        'parent_id',
        'transaction_code',
        'status',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_valid' => 'boolean',
    ];

    public function getTable()
    {
        $this->table = env('TABLE_PREFIX', 'ihm_') . 't_cluster_balance_withdraws';
        return $this->table;
    }

    /**
     * Relationship to Cluster
     */
    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'ihm_m_clusters_id');
    }

    /**
     * Relationship to parent transaction (PENARIKAN)
     */
    public function parent()
    {
        return $this->belongsTo(ClusterBalanceWithdraw::class, 'parent_id');
    }

    /**
     * Relationship to child transactions (SETORAN)
     */
    public function children()
    {
        return $this->hasMany(ClusterBalanceWithdraw::class, 'parent_id');
    }

    /**
     * Relationship to creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    /**
     * Scope for PENARIKAN transactions
     */
    public function scopePenarikan($query)
    {
        return $query->where('transaction_type', 'PENARIKAN');
    }

    /**
     * Scope for SETORAN transactions
     */
    public function scopeSetoran($query)
    {
        return $query->where('transaction_type', 'SETORAN');
    }

    /**
     * Scope for pending approvals (is_valid = 0)
     */
    public function scopePending($query)
    {
        return $query->where('is_valid', 0);
    }

    /**
     * Scope for approved (is_valid = 1)
     */
    public function scopeApproved($query)
    {
        return $query->where('is_valid', 1);
    }
}
