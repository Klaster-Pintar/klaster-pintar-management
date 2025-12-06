<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClusterRequest;
use App\Services\ClusterService;
use App\Models\Cluster;
use App\Models\ClusterOffice;
use App\Models\ClusterPatrol;
use App\Models\ClusterEmployee;
use App\Models\ClusterSecurity;
use App\Models\ClusterBankAccount;
use App\Models\ClusterResident;
use App\Models\ClusterSubscription;
use App\Models\ClusterBalanceWithdraw;
use App\Models\IoTDevice;
use App\Models\MarketingCluster;
use App\Models\Resident;
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
        $query = Cluster::query();

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
     * Store cluster data from wizard with Service Layer
     * Using Database Transaction for data integrity
     */
    public function storeWizard(StoreClusterRequest $request, ClusterService $clusterService)
    {
        try {
            // Handle file uploads
            $data = $request->validated();
            
            if ($request->hasFile('logo')) {
                $data['logo_path'] = $clusterService->handleFileUpload($request->file('logo'), 'clusters/logos');
            }
            
            if ($request->hasFile('picture')) {
                $data['picture_path'] = $clusterService->handleFileUpload($request->file('picture'), 'clusters/pictures');
            }

            // Store cluster with all related data using Service
            $result = $clusterService->storeCluster($data);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'],
                'redirect' => route('admin.clusters.show', $result['data']['cluster_id'])
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Failed to create cluster: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data cluster',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
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

            // Step 7: Residents CSV
            'residents_csv_data' => 'nullable|json',

            // Step 4: Employees CSV
            'employees_csv_data' => 'nullable|json',

            // Step 5: Securities CSV
            'securities_csv_data' => 'nullable|json',
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

            // Create Employees (RT, RW, ADMIN) - Manual Input
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
                        'created_id' => Auth::id(),
                    ]);

                    // Link to cluster
                    ClusterEmployee::create([
                        'ihm_m_clusters_id' => $cluster->id,
                        'employee_id' => $user->id,
                        'created_id' => Auth::id(),
                    ]);
                }
            }

            // Process Employees CSV Import
            $employeesImported = 0;
            $employeesErrors = [];

            if (!empty($validated['employees_csv_data'])) {
                $csvData = json_decode($validated['employees_csv_data'], true);

                foreach ($csvData as $index => $employeeData) {
                    try {
                        $this->processEmployeeImport($cluster->id, $employeeData, Auth::id());
                        $employeesImported++;
                    } catch (\Exception $e) {
                        $employeesErrors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            }

            // Create Securities - Manual Input
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
                        'created_id' => Auth::id(),
                    ]);

                    // Link to cluster
                    ClusterSecurity::create([
                        'ihm_m_clusters_id' => $cluster->id,
                        'security_id' => $user->id,
                        'created_id' => Auth::id(),
                    ]);
                }
            }

            // Process Securities CSV Import
            $securitiesImported = 0;
            $securitiesErrors = [];

            if (!empty($validated['securities_csv_data'])) {
                $csvData = json_decode($validated['securities_csv_data'], true);

                foreach ($csvData as $index => $securityData) {
                    try {
                        $this->processSecurityImport($cluster->id, $securityData, Auth::id());
                        $securitiesImported++;
                    } catch (\Exception $e) {
                        $securitiesErrors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    }
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

            // Process Residents CSV Import
            $residentsImported = 0;
            $residentsErrors = [];

            if (!empty($validated['residents_csv_data'])) {
                $csvData = json_decode($validated['residents_csv_data'], true);

                foreach ($csvData as $index => $residentData) {
                    try {
                        $this->processResidentImport($cluster->id, $residentData, Auth::id());
                        $residentsImported++;
                    } catch (\Exception $e) {
                        $residentsErrors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            }

            DB::commit();

            $message = 'Cluster berhasil dibuat! 🎉';
            if ($residentsImported > 0) {
                $message .= " {$residentsImported} residents berhasil di-import.";
            }
            if (!empty($residentsErrors)) {
                $message .= " Namun ada beberapa error: " . implode('; ', array_slice($residentsErrors, 0, 3));
            }

            return redirect()->route('admin.clusters.show', $cluster)
                ->with('success', $message);

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
     * Remove cluster with cascade delete
     */
    public function destroy(Cluster $cluster)
    {
        try {
            DB::beginTransaction();

            // Set deleted_id before deletion
            $cluster->deleted_id = Auth::id();
            $cluster->save();

            // Get cluster ID for related data deletion
            $clusterId = $cluster->id;
            $clusterName = $cluster->name;

            // Delete related data in sequence (cascade delete)
            // 1. Delete IoT Devices
            IoTDevice::where('cluster_id', $clusterId)->delete();

            // 2. Delete Cluster Residents
            ClusterResident::where('ihm_m_clusters_id', $clusterId)->delete();

            // 3. Delete Cluster Subscriptions
            ClusterSubscription::where('cluster_id', $clusterId)->delete();

            // 4. Delete Cluster Balance Withdraws
            ClusterBalanceWithdraw::where('ihm_m_clusters_id', $clusterId)->delete();

            // 5. Delete Cluster Bank Accounts
            ClusterBankAccount::where('ihm_m_clusters_id', $clusterId)->delete();

            // 6. Delete Cluster Securities
            ClusterSecurity::where('ihm_m_clusters_id', $clusterId)->delete();

            // 7. Delete Cluster Employees
            ClusterEmployee::where('ihm_m_clusters_id', $clusterId)->delete();

            // 8. Delete Cluster Patrols
            ClusterPatrol::where('ihm_m_clusters_id', $clusterId)->delete();

            // 9. Delete Cluster Offices
            ClusterOffice::where('ihm_m_clusters_id', $clusterId)->delete();

            // 10. Delete Marketing Cluster relationships
            MarketingCluster::where('cluster_id', $clusterId)->delete();

            // 11. Finally, delete the cluster itself (soft delete)
            $cluster->delete();

            DB::commit();

            return redirect()->route('admin.clusters.index')
                ->with('success', "Cluster '{$clusterName}' beserta semua data terkait berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.clusters.index')
                ->with('error', 'Gagal menghapus cluster: ' . $e->getMessage());
        }
    }

    /**
     * Process single resident import from CSV
     * 
     * @param int $clusterId
     * @param array $data
     * @param int $createdById
     * @return void
     * @throws \Exception
     */
    private function processResidentImport($clusterId, $data, $createdById)
    {
        $phone = trim($data['phone']);
        $houseBlock = trim($data['house_block']);
        $houseNumber = trim($data['house_number']);
        $name = trim($data['name']);

        // Check if user already exists by phone (username)
        $existingUser = User::where('username', $phone)->first();

        if ($existingUser) {
            // User exists, use existing user_id
            $userId = $existingUser->id;
        } else {
            // Create new user in ihm_m_users with phone as username
            $newUser = User::create([
                'name' => $name,
                'username' => $phone,
                'phone' => $phone,
                'email' => null,
                'password' => Hash::make('password123'), // Default password
                'role' => 'RESIDENT',
                'status' => $data['user_status'] === 'Active' ? 'VERIFIED' : 'PENDING',
                'active_flag' => $data['user_status'] === 'Active',
                'created_id' => $createdById,
            ]);
            $userId = $newUser->id;
        }

        // Create resident in ihm_m_residents
        $resident = Resident::create([
            'name' => $name,
            'username' => $phone,
            'phone' => $phone,
            'email' => null,
            'password' => Hash::make('password123'),
            'house_block' => $houseBlock,
            'house_number' => $houseNumber,
            'role' => 'RESIDENT',
            'status' => $data['user_status'] === 'Active' ? 'VERIFIED' : 'PENDING',
            'active_flag' => $data['user_status'] === 'Active',
            'created_id' => $createdById,
        ]);

        // Check if house already occupied in this cluster
        $existingResident = DB::table(env('TABLE_PREFIX', 'ihm_') . 'm_cluster_d_residents')
            ->where('ihm_m_clusters_id', $clusterId)
            ->where('resident_id', $resident->id)
            ->whereNull('deleted_at')
            ->first();

        if ($existingResident) {
            throw new \Exception("Rumah Blok {$houseBlock} No {$houseNumber} sudah terisi di cluster ini");
        }

        // Create cluster_d_resident link
        ClusterResident::create([
            'ihm_m_clusters_id' => $clusterId,
            'resident_id' => $resident->id,
            'created_id' => $createdById,
        ]);
    }

    /**
     * Process single employee import from CSV
     * 
     * @param int $clusterId
     * @param array $data
     * @param int $createdById
     * @return void
     * @throws \Exception
     */
    private function processEmployeeImport($clusterId, $data, $createdById)
    {
        $username = trim($data['username']);
        $name = trim($data['name']);
        $email = !empty($data['email']) ? trim($data['email']) : null;
        $phone = !empty($data['phone']) ? trim($data['phone']) : null;
        $role = trim($data['role']);
        $password = trim($data['password']);

        // Check if user already exists by username
        $existingUser = User::where('username', $username)->first();

        if ($existingUser) {
            // User exists, use existing user_id
            $userId = $existingUser->id;

            // Check if already assigned to this cluster
            $existingEmployee = ClusterEmployee::where('ihm_m_clusters_id', $clusterId)
                ->where('employee_id', $userId)
                ->whereNull('deleted_at')
                ->first();

            if ($existingEmployee) {
                throw new \Exception("User {$username} sudah terdaftar sebagai karyawan di cluster ini");
            }
        } else {
            // Create new user with specified role
            $newUser = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => $role, // RT, RW, ADMIN
                'status' => 'VERIFIED',
                'active_flag' => true,
                'created_id' => $createdById,
            ]);
            $userId = $newUser->id;
        }

        // Create cluster_d_employee link
        ClusterEmployee::create([
            'ihm_m_clusters_id' => $clusterId,
            'employee_id' => $userId,
            'created_id' => $createdById,
        ]);
    }

    /**
     * Process single security import from CSV
     * 
     * @param int $clusterId
     * @param array $data
     * @param int $createdById
     * @return void
     * @throws \Exception
     */
    private function processSecurityImport($clusterId, $data, $createdById)
    {
        $username = trim($data['username']);
        $name = trim($data['name']);
        $email = !empty($data['email']) ? trim($data['email']) : null;
        $phone = !empty($data['phone']) ? trim($data['phone']) : null;
        $password = trim($data['password']);

        // Check if user already exists by username
        $existingUser = User::where('username', $username)->first();

        if ($existingUser) {
            // User exists, use existing user_id
            $userId = $existingUser->id;

            // Check if already assigned to this cluster
            $existingSecurity = ClusterSecurity::where('ihm_m_clusters_id', $clusterId)
                ->where('security_id', $userId)
                ->whereNull('deleted_at')
                ->first();

            if ($existingSecurity) {
                throw new \Exception("User {$username} sudah terdaftar sebagai security di cluster ini");
            }
        } else {
            // Create new user with SECURITY role
            $newUser = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => 'SECURITY',
                'status' => 'VERIFIED',
                'active_flag' => true,
                'created_id' => $createdById,
            ]);
            $userId = $newUser->id;
        }

        // Create cluster_d_security link
        ClusterSecurity::create([
            'ihm_m_clusters_id' => $clusterId,
            'security_id' => $userId,
            'created_id' => $createdById,
        ]);
    }

    /**
     * Download CSV template for residents
     */
    public function downloadResidentTemplate()
    {
        $filename = 'template_data_warga.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['block_number', 'name', 'email', 'phone'];
        $sampleData = [
            ['A-01', 'John Doe', 'john@example.com', '08123456789'],
            ['A-02', 'Jane Smith', 'jane@example.com', '08123456788'],
            ['B-01', 'Bob Wilson', 'bob@example.com', '08123456787'],
        ];

        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Upload and import residents from CSV/Excel
     */
    public function uploadResidents(Request $request, $clusterId)
    {
        try {
            $request->validate([
                'resident_file' => 'required|file|mimes:csv,xlsx,xls|max:5120', // 5MB
            ]);

            $file = $request->file('resident_file');
            $extension = $file->getClientOriginalExtension();
            
            $residents = [];
            $errors = [];
            $row = 1;

            if ($extension === 'csv') {
                // Parse CSV
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle); // Skip header
                
                while (($data = fgetcsv($handle)) !== false) {
                    $row++;
                    
                    if (count($data) < 4) {
                        $errors[] = "Baris $row: Data tidak lengkap (harus ada 4 kolom)";
                        continue;
                    }

                    $residents[] = [
                        'block_number' => trim($data[0]),
                        'name' => trim($data[1]),
                        'email' => trim($data[2]),
                        'phone' => trim($data[3]),
                    ];
                }
                fclose($handle);
                
            } else {
                // Parse Excel using PhpSpreadsheet
                require_once base_path('vendor/autoload.php');
                
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow();
                    
                    for ($i = 2; $i <= $highestRow; $i++) {
                        $blockNumber = $worksheet->getCell("A$i")->getValue();
                        $name = $worksheet->getCell("B$i")->getValue();
                        $email = $worksheet->getCell("C$i")->getValue();
                        $phone = $worksheet->getCell("D$i")->getValue();
                        
                        if (empty($blockNumber) && empty($name)) {
                            continue; // Skip empty rows
                        }

                        $residents[] = [
                            'block_number' => trim($blockNumber),
                            'name' => trim($name),
                            'email' => trim($email),
                            'phone' => trim($phone),
                        ];
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membaca file Excel: ' . $e->getMessage()
                    ], 422);
                }
            }

            if (empty($residents) && empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File kosong atau tidak ada data valid'
                ], 422);
            }

            // Validate and import
            DB::beginTransaction();
            try {
                $imported = 0;
                $userId = auth()->id() ?? 1;

                foreach ($residents as $idx => $residentData) {
                    // Validation
                    if (empty($residentData['name'])) {
                        $errors[] = "Baris " . ($idx + 2) . ": Nama warga tidak boleh kosong";
                        continue;
                    }

                    // Create or find resident in ihm_m_residents
                    $resident = \App\Models\Resident::firstOrCreate(
                        ['username' => strtolower(str_replace(' ', '.', $residentData['name']))],
                        [
                            'name' => $residentData['name'],
                            'email' => $residentData['email'],
                            'phone' => $residentData['phone'],
                            'house_block' => $residentData['block_number'],
                            'password' => Hash::make('password123'),
                            'role' => 'RESIDENT',
                            'status' => 'PENDING',
                            'active_flag' => true,
                            'created_id' => $userId,
                        ]
                    );

                    // Link to cluster
                    $existing = \App\Models\ClusterResident::where('ihm_m_clusters_id', $clusterId)
                        ->where('resident_id', $resident->id)
                        ->whereNull('deleted_at')
                        ->first();

                    if (!$existing) {
                        \App\Models\ClusterResident::create([
                            'ihm_m_clusters_id' => $clusterId,
                            'resident_id' => $resident->id,
                            'created_id' => $userId,
                        ]);
                        $imported++;
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Data warga berhasil diimport',
                    'data' => [
                        'total_imported' => $imported,
                        'total_errors' => count($errors),
                    ],
                    'errors' => $errors
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to import residents: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // ==================== UPDATE BASIC INFO ====================
    
    /**
     * Update basic cluster information
     */
    public function updateBasicInfo(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'radius_checkin' => 'nullable|integer|min:1|max:1000',
            'radius_patrol' => 'nullable|integer|min:1|max:1000',
            'active_flag' => 'required|boolean',
        ]);

        try {
            $cluster->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Informasi cluster berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate informasi cluster',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== OFFICE CRUD ====================
    
    /**
     * Store new office
     */
    public function storeOffice(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'active_flag' => 'required|boolean',
        ]);

        try {
            // Create POINT geometry using raw SQL
            DB::statement(
                "INSERT INTO ihm_m_cluster_d_offices (ihm_m_clusters_id, name, type_id, location_point, active_flag, created_id, created_at, updated_at) 
                 VALUES (?, ?, ?, POINT(?, ?), ?, ?, NOW(), NOW())",
                [
                    $cluster->id,
                    $validated['name'],
                    $validated['type_id'],
                    $validated['longitude'],
                    $validated['latitude'],
                    $validated['active_flag'],
                    Auth::id()
                ]
            );

            $office = ClusterOffice::where('ihm_m_clusters_id', $cluster->id)
                ->orderBy('id', 'desc')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Kantor berhasil ditambahkan',
                'data' => $office->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kantor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update office
     */
    public function updateOffice(Request $request, Cluster $cluster, ClusterOffice $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'active_flag' => 'required|boolean',
        ]);

        try {
            // Update using raw SQL for POINT geometry
            DB::statement(
                "UPDATE ihm_m_cluster_d_offices 
                 SET name = ?, type_id = ?, location_point = POINT(?, ?), active_flag = ?, updated_id = ?, updated_at = NOW()
                 WHERE id = ?",
                [
                    $validated['name'],
                    $validated['type_id'],
                    $validated['longitude'],
                    $validated['latitude'],
                    $validated['active_flag'],
                    Auth::id(),
                    $office->id
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Kantor berhasil diupdate',
                'data' => $office->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kantor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete office
     */
    public function deleteOffice(Cluster $cluster, ClusterOffice $office)
    {
        try {
            $office->deleted_id = Auth::id();
            $office->save();
            $office->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kantor berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kantor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== PATROL CRUD ====================
    
    /**
     * Store new patrol
     */
    public function storePatrol(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'day_type_id' => 'required|integer',
            'pinpoints' => 'required|array|min:2',
            'pinpoints.*.lat' => 'required|numeric|between:-90,90',
            'pinpoints.*.lng' => 'required|numeric|between:-180,180',
        ]);

        try {
            $patrol = new ClusterPatrol();
            $patrol->ihm_m_clusters_id = $cluster->id;
            $patrol->day_type_id = $validated['day_type_id'];
            $patrol->pinpoints = json_encode($validated['pinpoints']);
            $patrol->created_id = Auth::id();
            $patrol->save();

            return response()->json([
                'success' => true,
                'message' => 'Rute patroli berhasil ditambahkan',
                'data' => $patrol
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan rute patroli',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update patrol
     */
    public function updatePatrol(Request $request, Cluster $cluster, ClusterPatrol $patrol)
    {
        $validated = $request->validate([
            'day_type_id' => 'required|integer',
            'pinpoints' => 'required|array|min:2',
            'pinpoints.*.lat' => 'required|numeric|between:-90,90',
            'pinpoints.*.lng' => 'required|numeric|between:-180,180',
        ]);

        try {
            $patrol->day_type_id = $validated['day_type_id'];
            $patrol->pinpoints = json_encode($validated['pinpoints']);
            $patrol->updated_id = Auth::id();
            $patrol->save();

            return response()->json([
                'success' => true,
                'message' => 'Rute patroli berhasil diupdate',
                'data' => $patrol
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate rute patroli',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete patrol
     */
    public function deletePatrol(Cluster $cluster, ClusterPatrol $patrol)
    {
        try {
            $patrol->deleted_id = Auth::id();
            $patrol->save();
            $patrol->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rute patroli berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus rute patroli',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== BANK ACCOUNT CRUD ====================
    
    /**
     * Store new bank account
     */
    public function storeBankAccount(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'bank_type' => 'required|string|max:100',
            'bank_code_id' => 'required|integer',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        try {
            $bank = new ClusterBankAccount($validated);
            $bank->ihm_m_clusters_id = $cluster->id;
            $bank->created_id = Auth::id();
            $bank->save();

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil ditambahkan',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan rekening bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update bank account
     */
    public function updateBankAccount(Request $request, Cluster $cluster, ClusterBankAccount $bank)
    {
        $validated = $request->validate([
            'bank_type' => 'required|string|max:100',
            'bank_code_id' => 'required|integer',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        try {
            $validated['updated_id'] = Auth::id();
            $bank->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil diupdate',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate rekening bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete bank account
     */
    public function deleteBankAccount(Cluster $cluster, ClusterBankAccount $bank)
    {
        try {
            $bank->deleted_id = Auth::id();
            $bank->save();
            $bank->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus rekening bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== EMPLOYEE CRUD ====================
    
    /**
     * Store new employee
     */
    public function storeEmployee(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:ihm_m_users,username',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:RT,RW,ADMIN',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = new User();
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->password = Hash::make($validated['password']);
            $user->role = $validated['role'];
            $user->status = 'ACTIVE';
            $user->created_id = Auth::id();
            $user->save();

            // Link to cluster
            $clusterEmployee = new ClusterEmployee();
            $clusterEmployee->ihm_m_clusters_id = $cluster->id;
            $clusterEmployee->employee_id = $user->id;
            $clusterEmployee->created_id = Auth::id();
            $clusterEmployee->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil ditambahkan',
                'data' => $clusterEmployee->load('employee')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update employee
     */
    public function updateEmployee(Request $request, Cluster $cluster, ClusterEmployee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:ihm_m_users,username,' . $employee->employee_id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:RT,RW,ADMIN',
        ]);

        try {
            $user = $employee->employee;
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->role = $validated['role'];
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->updated_id = Auth::id();
            $user->save();

            // Update cluster employee record
            $employee->updated_id = Auth::id();
            $employee->save();

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diupdate',
                'data' => $employee->load('employee')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete employee
     */
    public function deleteEmployee(Cluster $cluster, ClusterEmployee $employee)
    {
        DB::beginTransaction();
        try {
            $user = $employee->employee;
            
            // Set audit field before soft delete
            $employee->deleted_id = Auth::id();
            $employee->save();
            
            $employee->delete();
            
            $user->deleted_id = Auth::id();
            $user->save();
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== SECURITY CRUD ====================
    
    /**
     * Store new security
     */
    public function storeSecurity(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:ihm_m_users,username',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = new User();
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->password = Hash::make($validated['password']);
            $user->role = 'SECURITY';
            $user->status = 'ACTIVE';
            $user->created_id = Auth::id();
            $user->save();

            // Link to cluster
            $clusterSecurity = new ClusterSecurity();
            $clusterSecurity->ihm_m_clusters_id = $cluster->id;
            $clusterSecurity->security_id = $user->id;
            $clusterSecurity->created_id = Auth::id();
            $clusterSecurity->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Security berhasil ditambahkan',
                'data' => $clusterSecurity->load('security')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan security',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update security
     */
    public function updateSecurity(Request $request, Cluster $cluster, ClusterSecurity $security)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:ihm_m_users,username,' . $security->security_id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        try {
            $user = $security->security;
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->updated_id = Auth::id();
            $user->save();

            // Update cluster security record
            $security->updated_id = Auth::id();
            $security->save();

            return response()->json([
                'success' => true,
                'message' => 'Data security berhasil diupdate',
                'data' => $security->load('security')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate security',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete security
     */
    public function deleteSecurity(Cluster $cluster, ClusterSecurity $security)
    {
        DB::beginTransaction();
        try {
            $user = $security->security;
            
            // Set audit field before soft delete
            $security->deleted_id = Auth::id();
            $security->save();
            
            $security->delete();
            
            $user->deleted_id = Auth::id();
            $user->save();
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Security berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus security',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync stakeholders count for all clusters
     */
    public function syncStakeholders()
    {
        try {
            $clusters = Cluster::all();
            $updatedCount = 0;

            foreach ($clusters as $cluster) {
                $employeesCount = ClusterEmployee::where('ihm_m_clusters_id', $cluster->id)
                    ->whereNull('deleted_at')
                    ->count();
                
                $securitiesCount = ClusterSecurity::where('ihm_m_clusters_id', $cluster->id)
                    ->whereNull('deleted_at')
                    ->count();
                
                $residentsCount = ClusterResident::where('ihm_m_clusters_id', $cluster->id)
                    ->whereNull('deleted_at')
                    ->count();

                $cluster->update([
                    'total_employees' => $employeesCount,
                    'total_securities' => $securitiesCount,
                    'total_residents' => $residentsCount,
                    'updated_id' => Auth::id(),
                ]);

                $updatedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil sinkronisasi {$updatedCount} cluster",
                'data' => [
                    'updated_count' => $updatedCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
