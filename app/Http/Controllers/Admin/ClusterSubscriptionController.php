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

        // Load latest subscription for each cluster
        $clusters->getCollection()->transform(function ($cluster) {
            $cluster->latestSubscription = ClusterSubscription::where('cluster_id', $cluster->id)
                ->orderBy('expired_at', 'desc')
                ->first();
            return $cluster;
        });

        return view('admin.finance.subscription.index', compact('clusters'));
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
                $code = 'SUB' . now()->format('YmdHis') . rand(100, 999);

                ClusterSubscription::create([
                    'cluster_id' => $clusterId,
                    'package_id' => 1, // Default package
                    'price' => $request->amount,
                    'months' => $months,
                    'expired_at' => $expiredAt,
                    'active' => true,
                    'code' => $code,
                    'status' => 'ACTIVE',
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
