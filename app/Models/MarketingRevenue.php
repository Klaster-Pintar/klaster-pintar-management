<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingRevenue extends Model
{
    use HasFactory;

    protected $table = 'ihm_t_marketing_revenues';

    protected $fillable = [
        'marketing_id',
        'cluster_id',
        'revenue_date',
        'revenue_amount',
        'commission_percentage',
        'commission_amount',
        'payment_status',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'revenue_date' => 'date',
        'payment_date' => 'date',
        'revenue_amount' => 'decimal:2',
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
    public function scopePending($query)
    {
        return $query->where('payment_status', 'Pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'Paid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('payment_status', 'Cancelled');
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('revenue_date', $month)
            ->whereYear('revenue_date', $year);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('revenue_date', $year);
    }

    // Helper Methods
    public function getStatusBadgeClass(): string
    {
        return match ($this->payment_status) {
            'Pending' => 'bg-yellow-100 text-yellow-700',
            'Paid' => 'bg-green-100 text-green-700',
            'Cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'Pending';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'Paid';
    }
}
