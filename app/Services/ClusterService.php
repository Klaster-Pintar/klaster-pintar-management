<?php

namespace App\Services;

use App\Models\Cluster;
use App\Models\ClusterOffice;
use App\Models\ClusterPatrol;
use App\Models\ClusterEmployee;
use App\Models\ClusterSecurity;
use App\Models\ClusterBankAccount;
use App\Models\ClusterResident;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClusterService
{
    /**
     * Store a new cluster with all related data.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function storeCluster(array $data): array
    {
        DB::beginTransaction();

        try {
            // Get authenticated user ID
            $userId = auth()->id() ?? 1; // Fallback to ID 1 if not authenticated

            // Step 1: Create Cluster (REQUIRED)
            $cluster = $this->createCluster($data, $userId);

            // Step 2: Create Offices (REQUIRED - minimum 1)
            if (empty($data['offices'])) {
                throw new \Exception('Minimal 1 kantor harus ditambahkan');
            }
            $officesCount = $this->createOffices($cluster->id, $data['offices'], $userId);

            // Step 3: Create Patrols (OPTIONAL)
            $patrolsCount = 0;
            if (!empty($data['patrols'])) {
                $patrolsCount = $this->createPatrols($cluster->id, $data['patrols'], $userId);
            }

            // Step 4: Create Employees (OPTIONAL)
            $employeesCount = 0;
            if (!empty($data['employees'])) {
                $employeesCount = $this->createEmployees($cluster->id, $data['employees'], $userId);
            }

            // Step 5: Create Securities (OPTIONAL)
            $securitiesCount = 0;
            if (!empty($data['securities'])) {
                $securitiesCount = $this->createSecurities($cluster->id, $data['securities'], $userId);
            }

            // Step 6: Create Bank Accounts (OPTIONAL)
            $bankAccountsCount = 0;
            if (!empty($data['bank_accounts'])) {
                $bankAccountsCount = $this->createBankAccounts($cluster->id, $data['bank_accounts'], $userId);
            }

            // Step 7: Create Residents (OPTIONAL - usually via CSV upload)
            $residentsCount = 0;
            if (!empty($data['residents'])) {
                $residentsCount = $this->createResidents($cluster->id, $data['residents'], $userId);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cluster berhasil dibuat!',
                'data' => [
                    'cluster_id' => $cluster->id,
                    'cluster_name' => $cluster->name,
                    'summary' => [
                        'offices' => $officesCount,
                        'patrols' => $patrolsCount,
                        'employees' => $employeesCount,
                        'securities' => $securitiesCount,
                        'bank_accounts' => $bankAccountsCount,
                        'residents' => $residentsCount,
                    ]
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded files if transaction failed
            if (isset($data['logo_path'])) {
                Storage::disk('public')->delete($data['logo_path']);
            }
            if (isset($data['picture_path'])) {
                Storage::disk('public')->delete($data['picture_path']);
            }

            throw $e;
        }
    }

    /**
     * Create cluster main data
     */
    private function createCluster(array $data, int $userId): Cluster
    {
        $clusterData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'phone' => $data['phone'],
            'email' => $data['email'],
            'radius_checkin' => $data['radius_checkin'] ?? 5,
            'radius_patrol' => $data['radius_patrol'] ?? 5,
            'logo' => $data['logo_path'] ?? null,
            'picture' => $data['picture_path'] ?? null,
            'patrol_type_id' => $data['patrol_type_id'] ?? null,
            'active_flag' => true,
            'created_id' => $userId,
        ];

        return Cluster::create($clusterData);
    }

    /**
     * Create cluster offices
     */
    private function createOffices(int $clusterId, array $offices, int $userId): int
    {
        $count = 0;

        foreach ($offices as $office) {
            // Convert lat/lng to POINT geometry for MySQL
            $latitude = $office['latitude'];
            $longitude = $office['longitude'];

            ClusterOffice::create([
                'ihm_m_clusters_id' => $clusterId,
                'name' => $office['name'],
                'type_id' => $office['type_id'],
                'location_point' => DB::raw("POINT($longitude, $latitude)"),
                'active_flag' => true,
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Create cluster patrols
     */
    private function createPatrols(int $clusterId, array $patrols, int $userId): int
    {
        $count = 0;

        foreach ($patrols as $patrol) {
            // Convert pinpoints array to JSON
            $pinpoints = isset($patrol['pinpoints']) ? json_encode($patrol['pinpoints']) : null;

            ClusterPatrol::create([
                'ihm_m_clusters_id' => $clusterId,
                'day_type_id' => $patrol['day_type_id'],
                'pinpoints' => $pinpoints,
                'active_flag' => true,
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Create cluster employees and their user accounts
     */
    private function createEmployees(int $clusterId, array $employees, int $userId): int
    {
        $count = 0;

        foreach ($employees as $employee) {
            // Create user account first
            $user = $this->createUserAccount($employee, 'EMPLOYEE', $userId);

            // Create employee record
            ClusterEmployee::create([
                'ihm_m_clusters_id' => $clusterId,
                'employee_id' => $user->id,
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Create cluster securities and their user accounts
     */
    private function createSecurities(int $clusterId, array $securities, int $userId): int
    {
        $count = 0;

        foreach ($securities as $security) {
            // Create user account with SECURITY role
            $user = $this->createUserAccount($security, 'SECURITY', $userId);

            // Create security record
            ClusterSecurity::create([
                'ihm_m_clusters_id' => $clusterId,
                'security_id' => $user->id,
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Create user account (for employees and securities)
     */
    private function createUserAccount(array $userData, string $defaultRole, int $createdById): User
    {
        // Determine role (for employees, use provided role; for securities, always SECURITY)
        $role = $defaultRole === 'SECURITY' ? 'SECURITY' : ($userData['role'] ?? $defaultRole);
        
        // Roles is JSON array
        $roles = json_encode([$role]);

        $user = User::create([
            'name' => $userData['name'],
            'username' => $userData['username'],
            'email' => $userData['email'] ?? null,
            'phone' => $userData['phone'] ?? null,
            'gender' => $userData['gender'] ?? null,
            'date_birth' => $userData['date_birth'] ?? null,
            'address' => $userData['address'] ?? null,
            'role' => $role,
            'roles' => $roles,
            'password' => Hash::make('password123'), // Default password
            'active_flag' => true,
            'status' => 'ACTIVE',
            'created_id' => $createdById,
        ]);

        return $user;
    }

    /**
     * Create cluster bank accounts
     */
    private function createBankAccounts(int $clusterId, array $bankAccounts, int $userId): int
    {
        $count = 0;

        foreach ($bankAccounts as $account) {
            ClusterBankAccount::create([
                'ihm_m_clusters_id' => $clusterId,
                'account_number' => $account['account_number'],
                'account_holder' => $account['account_holder'],
                'bank_type' => $account['bank_type'],
                'bank_code_id' => $account['bank_code_id'],
                'is_verified' => false,
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Create cluster residents
     */
    private function createResidents(int $clusterId, array $residents, int $userId): int
    {
        $count = 0;

        foreach ($residents as $resident) {
            ClusterResident::create([
                'ihm_m_clusters_id' => $clusterId,
                'resident_id' => $resident['resident_id'],
                'created_id' => $userId,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Handle file upload
     */
    public function handleFileUpload($file, string $folder = 'clusters'): ?string
    {
        if (!$file) {
            return null;
        }

        $filename = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }
}
