<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IoTDevice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ihm_m_iot_devices';

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'cluster_id',
        'hardware_status',
        'network_status',
        'last_connected_at',
        'ip_address',
        'signal_strength',
        'firmware_version',
        'location',
        'active_flag',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'active_flag' => 'boolean',
        'last_connected_at' => 'datetime',
        'signal_strength' => 'integer',
    ];

    // Relationships
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
        return $query->where('active_flag', true);
    }

    public function scopeHardwareActive($query)
    {
        return $query->where('hardware_status', 'Active');
    }

    public function scopeHardwareInactive($query)
    {
        return $query->where('hardware_status', 'Inactive');
    }

    public function scopeHardwareRusak($query)
    {
        return $query->where('hardware_status', 'Rusak');
    }

    public function scopeConnected($query)
    {
        return $query->where('network_status', 'Connected');
    }

    public function scopeNotConnected($query)
    {
        return $query->where('network_status', 'Not Connected');
    }

    // Helper Methods
    public function isConnected(): bool
    {
        return $this->network_status === 'Connected';
    }

    public function isHardwareActive(): bool
    {
        return $this->hardware_status === 'Active';
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->hardware_status) {
            'Active' => 'bg-green-100 text-green-700',
            'Inactive' => 'bg-yellow-100 text-yellow-700',
            'Rusak' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getNetworkBadgeClass(): string
    {
        return match ($this->network_status) {
            'Connected' => 'bg-green-100 text-green-700',
            'Not Connected' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getSignalStrengthClass(): string
    {
        if (!$this->signal_strength) {
            return 'text-gray-400';
        }

        return match (true) {
            $this->signal_strength >= 80 => 'text-green-600',
            $this->signal_strength >= 50 => 'text-yellow-600',
            $this->signal_strength >= 30 => 'text-orange-600',
            default => 'text-red-600',
        };
    }

    public function getLastConnectedHuman(): string
    {
        if (!$this->last_connected_at) {
            return 'Never';
        }

        return $this->last_connected_at->diffForHumans();
    }
}
