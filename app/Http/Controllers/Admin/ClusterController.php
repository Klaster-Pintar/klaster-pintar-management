<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\ClusterOffice;
use App\Models\ClusterPatrol;
use App\Models\ClusterEmployee;
use App\Models\ClusterSecurity;
use App\Models\ClusterBankAccount;
use App\Models\ClusterResident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClusterController extends Controller
{
    /**
     * Display a listing of clusters
     */
    public function index(Request $request)
    {
        $query = Cluster::query()->with(['offices', 'employees', 'securities']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('active_flag', $request->status === 'active');
        }

        $clusters = $query->latest()->paginate(10);

        return view('admin.clusters.index', compact('clusters'));
    }

    /**
     * Show wizard for creating new cluster - Step 1
     */
    public function create()
    {
        return view('admin.clusters.wizard.index');
    }

    /**
     * Store cluster data from wizard
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Step 1: Basic Info
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|max:2048',
            'picture' => 'nullable|image|max:2048',
            'radius_checkin' => 'nullable|integer|min:1|max:100',
            'radius_patrol' => 'nullable|integer|min:1|max:100',

            // Step 2: Offices
            'offices' => 'required|array|min:1',
            'offices.*.name' => 'required|string|max:255',
            'offices.*.type_id' => 'required|integer',
            'offices.*.latitude' => 'required|numeric',
            'offices.*.longitude' => 'required|numeric',

            // Step 3: Patrol Points
            'patrols' => 'nullable|array',
            'patrols.*.day_type_id' => 'required|integer',
            'patrols.*.pinpoints' => 'required|array',

            // Step 4: Employees
            'employees' => 'nullable|array',
            'employees.*.name' => 'required|string|max:255',
            'employees.*.username' => 'required|string|max:255|unique:ihm_m_users,username',
            'employees.*.email' => 'nullable|email|max:255',
            'employees.*.phone' => 'nullable|string|max:20',
            'employees.*.role' => 'required|in:RT,RW,ADMIN',
            'employees.*.password' => 'required|string|min:8',

            // Step 5: Securities
            'securities' => 'nullable|array',
            'securities.*.name' => 'required|string|max:255',
            'securities.*.username' => 'required|string|max:255|unique:ihm_m_users,username',
            'securities.*.email' => 'nullable|email|max:255',
            'securities.*.phone' => 'nullable|string|max:20',
            'securities.*.password' => 'required|string|min:8',

            // Step 6: Bank Accounts
            'bank_accounts' => 'required|array|min:1',
            'bank_accounts.*.account_number' => 'required|string|max:255',
            'bank_accounts.*.account_holder' => 'required|string|max:255',
            'bank_accounts.*.bank_type' => 'required|string|max:15',
            'bank_accounts.*.bank_code_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // Upload logo and picture
            $logoPath = null;
            $picturePath = null;

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('clusters/logos', 'public');
            }

            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('clusters/pictures', 'public');
            }

            // Create Cluster
            $cluster = Cluster::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'logo' => $logoPath,
                'picture' => $picturePath,
                'radius_checkin' => $validated['radius_checkin'] ?? 5,
                'radius_patrol' => $validated['radius_patrol'] ?? 5,
                'active_flag' => true,
                'created_id' => Auth::id(),
            ]);

            // Create Offices
            foreach ($validated['offices'] as $office) {
                ClusterOffice::create([
                    'ihm_m_clusters_id' => $cluster->id,
                    'name' => $office['name'],
                    'type_id' => $office['type_id'],
                    'location_point' => DB::raw("POINT({$office['latitude']}, {$office['longitude']})"),
                    'active_flag' => true,
                    'created_id' => Auth::id(),
                ]);
            }

            // Create Patrol Points
            if (!empty($validated['patrols'])) {
                foreach ($validated['patrols'] as $patrol) {
                    ClusterPatrol::create([
                        'ihm_m_clusters_id' => $cluster->id,
                        'day_type_id' => $patrol['day_type_id'],
                        'pinpoints' => $patrol['pinpoints'],
                        'active_flag' => true,
                        'created_id' => Auth::id(),
                    ]);
                }
            }

            // Create Employees (RT, RW, ADMIN)
            if (!empty($validated['employees'])) {
                foreach ($validated['employees'] as $employee) {
                    // Create user first
                    $user = User::create([
                        'name' => $employee['name'],
                        'username' => $employee['username'],
                        'email' => $employee['email'] ?? null,
                        'phone' => $employee['phone'] ?? null,
                        'password' => Hash::make($employee['password']),
                        'role' => $employee['role'],
                        'status' => 'VERIFIED',
                        'active_flag' => true,
                    ]);

                    // Link to cluster
                    ClusterEmployee::create([
                        'ihm_m_clusters_id' => $cluster->id,
                        'employee_id' => $user->id,
                        'created_id' => Auth::id(),
                    ]);
                }
            }

            // Create Securities
            if (!empty($validated['securities'])) {
                foreach ($validated['securities'] as $security) {
                    // Create user first
                    $user = User::create([
                        'name' => $security['name'],
                        'username' => $security['username'],
                        'email' => $security['email'] ?? null,
                        'phone' => $security['phone'] ?? null,
                        'password' => Hash::make($security['password']),
                        'role' => 'SECURITY',
                        'status' => 'VERIFIED',
                        'active_flag' => true,
                    ]);

                    // Link to cluster
                    ClusterSecurity::create([
                        'ihm_m_clusters_id' => $cluster->id,
                        'security_id' => $user->id,
                        'created_id' => Auth::id(),
                    ]);
                }
            }

            // Create Bank Accounts
            foreach ($validated['bank_accounts'] as $bankAccount) {
                ClusterBankAccount::create([
                    'ihm_m_clusters_id' => $cluster->id,
                    'account_number' => $bankAccount['account_number'],
                    'account_holder' => $bankAccount['account_holder'],
                    'bank_type' => $bankAccount['bank_type'],
                    'bank_code_id' => $bankAccount['bank_code_id'],
                    'is_verified' => false,
                    'created_id' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.clusters.show', $cluster)
                ->with('success', 'Cluster berhasil dibuat! 🎉');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded files if transaction failed
            if ($logoPath)
                Storage::disk('public')->delete($logoPath);
            if ($picturePath)
                Storage::disk('public')->delete($picturePath);

            return back()->withInput()
                ->with('error', 'Gagal membuat cluster: ' . $e->getMessage());
        }
    }

    /**
     * Display cluster details
     */
    public function show(Cluster $cluster)
    {
        $cluster->load([
            'offices',
            'patrols',
            'employees.employee',
            'securities.security',
            'bankAccounts',
            'residents.resident'
        ]);

        return view('admin.clusters.show', compact('cluster'));
    }

    /**
     * Show form for editing cluster
     */
    public function edit(Cluster $cluster)
    {
        $cluster->load([
            'offices',
            'patrols',
            'employees.employee',
            'securities.security',
            'bankAccounts'
        ]);

        return view('admin.clusters.edit', compact('cluster'));
    }

    /**
     * Update cluster
     */
    public function update(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|max:2048',
            'picture' => 'nullable|image|max:2048',
            'radius_checkin' => 'nullable|integer|min:1|max:100',
            'radius_patrol' => 'nullable|integer|min:1|max:100',
            'active_flag' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($cluster->logo) {
                Storage::disk('public')->delete($cluster->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clusters/logos', 'public');
        }

        if ($request->hasFile('picture')) {
            if ($cluster->picture) {
                Storage::disk('public')->delete($cluster->picture);
            }
            $validated['picture'] = $request->file('picture')->store('clusters/pictures', 'public');
        }

        $validated['updated_id'] = Auth::id();

        $cluster->update($validated);

        return redirect()->route('admin.clusters.show', $cluster)
            ->with('success', 'Cluster berhasil diupdate!');
    }

    /**
     * Remove cluster
     */
    public function destroy(Cluster $cluster)
    {
        $cluster->deleted_id = Auth::id();
        $cluster->save();
        $cluster->delete();

        return redirect()->route('admin.clusters.index')
            ->with('success', 'Cluster berhasil dihapus!');
    }
}
