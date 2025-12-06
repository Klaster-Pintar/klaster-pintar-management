<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\ClusterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClusterSubscriptionController extends Controller
{
    /**
     * Display a listing of cluster subscriptions
     */
    public function index(Request $request)
    {
        $query = Cluster::with(['creator'])
            ->orderBy('name', 'asc');

        // Search by cluster name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $clusters = $query->paginate(10);

        // Calculate summary statistics from ALL records (not just current page)
        $allClusters = Cluster::with(['latestSubscription'])->get();
        
        $stats = [
            'active_paid' => $allClusters->filter(function($c) {
                return $c->latestSubscription && !$c->latestSubscription->isExpired() && $c->latestSubscription->price > 0;
            })->count(),
            
            'expired' => $allClusters->filter(function($c) {
                return $c->latestSubscription && $c->latestSubscription->isExpired();
            })->count(),
            
            'expiring_soon' => $allClusters->filter(function($c) {
                return $c->latestSubscription && $c->latestSubscription->daysRemaining() <= 7 && $c->latestSubscription->daysRemaining() > 0;
            })->count(),
            
            'free_trial' => $allClusters->filter(function($c) {
                return $c->latestSubscription && $c->latestSubscription->price == 0;
            })->count(),
        ];

        // Load latest subscription for each cluster and auto-create Free Trial if needed
        $clusters->getCollection()->transform(function ($cluster) {
            $cluster->latestSubscription = ClusterSubscription::where('cluster_id', $cluster->id)
                ->orderBy('expired_at', 'desc')
                ->first();
            
            // Auto-create Free Trial if no subscription exists
            if (!$cluster->latestSubscription) {
                // Code max 10 chars: FT-{id}-{random}
                // $code = 'FT' . str_pad($cluster->id, 4, '0', STR_PAD_LEFT) . rand(1000, 9999);
                
                $cluster->latestSubscription = ClusterSubscription::create([
                    'cluster_id' => $cluster->id,
                    'package_id' => 1,
                    'price' => 0, // Free Trial = Rp 0
                    'months' => 3, // 3 months free trial
                    'expired_at' => now()->addMonths(3),
                    // 'code' => $code,
                    'status' => 'ACTIVE',
                    'created_id' => Auth::id() ?? 1,
                    'package_name' => 'Free Trial', // Default package name
                    'admin_fee' => 0,
                    'invoice_code' => 'INV' . time() . rand(100, 999),
                    'total' => 0,
                    'package' => json_encode(['id' => 1, 'name' => 'Basic']),
                    // 'code' => $code,
                    // 'active' => true,
                    // 'status' => 'ACTIVE',
                ]);
            }
            
            return $cluster;
        });

        return view('admin.finance.subscription.index', compact('clusters', 'stats'));
    }

    /**
     * Update subscription for a cluster
     */
    public function update(Request $request, $clusterId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'extend_type' => 'required|in:manual,automatic',
            'expired_date' => 'required_if:extend_type,manual|date|after:today',
            'months' => 'required_if:extend_type,automatic|integer|min:1|max:24',
        ]);

        try {
            DB::beginTransaction();

            $cluster = Cluster::findOrFail($clusterId);

            // Calculate expired_at based on extend type
            if ($request->extend_type === 'manual') {
                $expiredAt = Carbon::parse($request->expired_date);
                $months = now()->diffInMonths($expiredAt);
            } else {
                $months = $request->months;
                $expiredAt = now()->addMonths($months);
            }

            // Get or create subscription
            $subscription = ClusterSubscription::where('cluster_id', $clusterId)
                ->orderBy('expired_at', 'desc')
                ->first();

            if ($subscription && $subscription->expired_at > now()) {
                // Extend existing subscription
                $subscription->update([
                    'price' => $request->amount,
                    'months' => $months,
                    'expired_at' => $expiredAt,
                    'active' => true,
                    'status' => 'ACTIVE',
                    'updated_id' => Auth::id(),
                ]);
            } else {
                // Create new subscription
                // Code max 10 chars: SB + 8 digits timestamp
                // $code = 'SB' . substr(now()->timestamp, -8);

                ClusterSubscription::create([
                    'cluster_id' => $clusterId,
                    'package_id' => 1, // Default package
                    'package_name' => 'Basic', // Default package name
                    'price' => $request->amount,
                    'months' => $months,
                    'expired_at' => $expiredAt,
                    'admin_fee' => 0,
                    'invoice_code'=> 'INV' . time() . rand(100,999),
                    'total' => $request->amount,
                    'package' => json_encode(['id' => 1, 'name' => 'Basic']),
                    // 'code' => $code,
                    // 'active' => true,
                    // 'status' => 'ACTIVE',
                    'created_id' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subscription berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal update subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subscription detail
     */
    public function show($clusterId)
    {
        $cluster = Cluster::findOrFail($clusterId);
        $subscription = ClusterSubscription::where('cluster_id', $clusterId)
            ->orderBy('expired_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'cluster' => $cluster,
            'subscription' => $subscription
        ]);
    }
}
