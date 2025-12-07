<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusterBalanceWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceSettlementController extends Controller
{
    /**
     * Display a listing of settlement transactions (PENARIKAN)
     */
    public function index(Request $request)
    {
        $query = ClusterBalanceWithdraw::with(['cluster']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'pending') {
                // Pending: parent_id NULL dan transaction_type STORAN
                $query->whereNull('parent_id')
                      ->where('transaction_type', 'SETORAN')
                      ->where('is_valid', 0);
            } elseif ($request->status == 'approved') {
                // Approved: PENARIKAN yang sudah di-approve
                $query->penarikan()->approved();
            }
        } else {
            // Default: tampilkan PENARIKAN saja
            $query->penarikan();
        }

        $query->orderBy('created_at', 'desc');

        // Search by cluster name or code
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('cluster', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('transaction_code', 'like', "%{$search}%");
        }

        $settlements = $query->paginate(10);

        return view('admin.finance.settlement.index', compact('settlements'));
    }

    /**
     * Show detail of SETORAN transactions for a PENARIKAN
     */
    public function show($id)
    {
        $withdrawal = ClusterBalanceWithdraw::with(['cluster', 'children'])
            ->findOrFail($id);

        // Get SETORAN transactions with parent_id = $id
        if($withdrawal->is_valid) {
           $setorans = ClusterBalanceWithdraw::where('parent_id', $id)
            ->where('transaction_type', 'SETORAN')
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'withdrawal' => $withdrawal,
                'setorans' => $setorans
            ]);
        }
        $setorans = ClusterBalanceWithdraw::where('is_valid', 0)
            ->where('transaction_type', 'SETORAN')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'withdrawal' => $withdrawal,
            'setorans' => $setorans
        ]);
    }

    /**
     * Approve a settlement (set is_valid = 1)
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $withdrawal = ClusterBalanceWithdraw::findOrFail($id);

            if ($withdrawal->is_valid == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Settlement sudah diapprove sebelumnya'
                ], 400);
            }

            // Update withdrawal to approved
            $withdrawal->update([
                'is_valid' => 1,
                'updated_id' => Auth::id(),
            ]);

            // Update all pending SETORAN: set is_valid=1 and parent_id=$withdrawal->id
            ClusterBalanceWithdraw::where('transaction_type', 'SETORAN')
                ->where('is_valid', 0)
                ->whereNull('parent_id')
                ->update([
                    'is_valid' => 1,
                    'parent_id' => $withdrawal->id,
                    'updated_id' => Auth::id(),
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Settlement berhasil diapprove'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve settlement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a settlement
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = ClusterBalanceWithdraw::findOrFail($id);

            if ($withdrawal->is_valid == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Settlement sudah diapprove, tidak bisa direject'
                ], 400);
            }

            $withdrawal->update([
                'status' => 'REJECTED',
                'description' => ($withdrawal->description ?? '') . ' | Rejected: ' . $request->reason,
                'updated_id' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Settlement berhasil direject'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal reject settlement: ' . $e->getMessage()
            ], 500);
        }
    }
}
