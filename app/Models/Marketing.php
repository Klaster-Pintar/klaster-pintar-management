<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ihm_m_marketings';

    protected $fillable = [
        'name',
        'phone',
        'cluster_affiliate_name',
        'referral_code',
        'email',
        'address',
        'id_card_number',
        'join_date',
        'status',
        'active_flag',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'active_flag' => 'boolean',
        'join_date' => 'date',
    ];

    // Relationships
    public function clusters()
    {
        return $this->belongsToMany(Cluster::class, 'ihm_t_marketing_clusters', 'marketing_id', 'cluster_id')
            ->withPivot('join_date', 'commission_percentage', 'commission_amount', 'status', 'notes')
            ->withTimestamps();
    }

    public function marketingClusters()
    {
        return $this->hasMany(MarketingCluster::class, 'marketing_id');
    }

    public function commissionSettings()
    {
        return $this->hasMany(CommissionSetting::class, 'marketing_id');
    }

    public function revenues()
    {
        return $this->hasMany(MarketingRevenue::class, 'marketing_id');
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
        return $query->where('active_flag', true)->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'Suspended');
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === 'Active' && $this->active_flag;
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'Active' => 'bg-green-100 text-green-700',
            'Inactive' => 'bg-gray-100 text-gray-700',
            'Suspended' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getTotalClusters(): int
    {
        return $this->marketingClusters()->where('status', 'Active')->count();
    }

    public function getTotalRevenue(): float
    {
        return $this->revenues()->sum('revenue_amount');
    }

    public function getTotalCommission(): float
    {
        return $this->revenues()->sum('commission_amount');
    }

    public function getPendingCommission(): float
    {
        return $this->revenues()->where('payment_status', 'Pending')->sum('commission_amount');
    }

    public function getPaidCommission(): float
    {
        return $this->revenues()->where('payment_status', 'Paid')->sum('commission_amount');
    }

    // Static method to generate unique referral code
    public static function generateReferralCode(): string
    {
        do {
            $code = 'REF-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}
