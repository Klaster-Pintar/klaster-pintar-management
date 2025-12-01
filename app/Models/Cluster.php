<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cluster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patrol_type_id',
        'name',
        'description',
        'phone',
        'email',
        'picture',
        'logo',
        'radius_checkin',
        'radius_patrol',
        'active_flag',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'active_flag' => 'boolean',
        'radius_checkin' => 'integer',
        'radius_patrol' => 'integer',
    ];

    public function getTable()
    {
        // Support dynamic table prefix from .env
        $this->table = env('TABLE_PREFIX', 'ihm_') . 'm_clusters';
        return $this->table;
    }

    // Relationships
    public function offices()
    {
        return $this->hasMany(ClusterOffice::class, 'ihm_m_clusters_id');
    }

    public function patrols()
    {
        return $this->hasMany(ClusterPatrol::class, 'ihm_m_clusters_id');
    }

    public function employees()
    {
        return $this->hasMany(ClusterEmployee::class, 'ihm_m_clusters_id');
    }

    public function securities()
    {
        return $this->hasMany(ClusterSecurity::class, 'ihm_m_clusters_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(ClusterBankAccount::class, 'ihm_m_clusters_id');
    }

    public function residents()
    {
        return $this->hasMany(ClusterResident::class, 'ihm_m_clusters_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ClusterSubscription::class, 'cluster_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(ClusterSubscription::class, 'cluster_id')
            ->where('active', true)
            ->where('status', 'ACTIVE')
            ->where('expired_at', '>=', now())
            ->orderBy('expired_at', 'desc');
    }

    public function expiredSubscription()
    {
        return $this->hasOne(ClusterSubscription::class, 'cluster_id')
            ->where('expired_at', '<', now())
            ->orderBy('expired_at', 'desc');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }
}
