# Cluster Delete with Cascade & SweetAlert Implementation

**Date:** December 6, 2025  
**Feature:** Cluster Delete with Cascade Delete & SweetAlert Confirmation

---

## Overview

Implementasi fitur delete cluster dengan cascade delete untuk menghapus semua data terkait menggunakan database transaction, disertai dengan SweetAlert untuk konfirmasi yang lebih user-friendly.

---

## Changes Summary

### 1. View Changes - `resources/views/admin/clusters/index.blade.php`

#### A. Removed Edit Button
- **Before:** Terdapat 3 tombol (Detail, Edit, Delete)
- **After:** Hanya 2 tombol (Detail, Delete)
- **Reason:** Edit action dihapus sesuai request

#### B. Updated Delete Button with SweetAlert
- **Before:** Menggunakan `confirm()` JavaScript native
- **After:** Menggunakan SweetAlert2 dengan UI yang lebih informatif

```html
<!-- BEFORE -->
<button onclick="confirm('Hapus cluster?') && document.getElementById('delete-form-{{ $cluster->id }}').submit()">

<!-- AFTER -->
<button onclick="deleteCluster({{ $cluster->id }}, '{{ $cluster->name }}')">
```

#### C. Added Error Alert Display
Ditambahkan alert untuk menampilkan error message jika terjadi kegagalan saat delete:

```html
@if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow flex items-center gap-3">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl"></i>
        <div class="flex-1">
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-red-500 hover:text-red-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>
@endif
```

#### D. Added SweetAlert Function
Fungsi JavaScript baru untuk konfirmasi delete dengan informasi detail:

```javascript
function deleteCluster(clusterId, clusterName) {
    Swal.fire({
        title: 'Hapus Cluster?',
        html: `<div class="text-left">
            <p class="mb-3 font-semibold text-gray-800">Cluster: <span class="text-red-600">${clusterName}</span></p>
            <p class="mb-2 text-sm text-gray-700">Data yang akan dihapus:</p>
            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                <li>Data Cluster</li>
                <li>Kantor/Office</li>
                <li>Patroli</li>
                <li>Karyawan/Employee</li>
                <li>Satpam/Security</li>
                <li>Bank Account</li>
                <li>Resident/Penghuni</li>
                <li>Subscription</li>
                <li>IoT Devices</li>
            </ul>
            <p class="mt-3 text-sm text-red-600 font-semibold">
                <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                Tindakan ini tidak dapat dibatalkan!
            </p>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fa-solid fa-trash mr-2"></i>Ya, Hapus',
        cancelButtonText: '<i class="fa-solid fa-times mr-2"></i>Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const form = document.getElementById(`delete-form-${clusterId}`);
            form.submit();
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}
```

**Features:**
- Menampilkan nama cluster yang akan dihapus
- List lengkap data terkait yang akan dihapus
- Warning bahwa aksi tidak dapat dibatalkan
- Tombol konfirmasi berwarna merah (destructive action)
- Loading state saat submit form

---

### 2. Controller Changes - `app/Http/Controllers/Admin/ClusterController.php`

#### A. Added Use Statements
Menambahkan import model-model yang diperlukan untuk cascade delete:

```php
use App\Models\ClusterSubscription;
use App\Models\ClusterBalanceWithdraw;
use App\Models\IoTDevice;
use App\Models\MarketingCluster;
```

#### B. Updated Destroy Method with Transaction
Menggunakan database transaction untuk memastikan data integrity:

```php
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
        IoTDevice::where('ihm_m_clusters_id', $clusterId)->delete();

        // 2. Delete Cluster Residents
        ClusterResident::where('ihm_m_clusters_id', $clusterId)->delete();

        // 3. Delete Cluster Subscriptions
        ClusterSubscription::where('cluster_id', $clusterId)->delete();

        // 4. Delete Cluster Balance Withdraws
        ClusterBalanceWithdraw::where('cluster_id', $clusterId)->delete();

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
        MarketingCluster::where('ihm_m_clusters_id', $clusterId)->delete();

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
```

**Key Features:**
- ✅ **Transaction Safety**: Menggunakan `DB::beginTransaction()` dan `DB::commit()`
- ✅ **Rollback on Error**: Otomatis rollback jika terjadi error
- ✅ **Audit Trail**: Menyimpan `deleted_id` sebelum delete
- ✅ **Cascade Delete**: Menghapus semua data terkait dalam urutan yang benar
- ✅ **Error Handling**: Menangkap exception dan memberikan pesan error yang jelas

---

## Data Deletion Sequence

Urutan penghapusan data (penting untuk menghindari foreign key constraint errors):

1. **IoT Devices** (`ihm_iot_devices`)
2. **Cluster Residents** (`ihm_m_cluster_residents`)
3. **Cluster Subscriptions** (`ihm_cluster_subscriptions`)
4. **Cluster Balance Withdraws** (`ihm_t_cluster_balance_withdraws`)
5. **Cluster Bank Accounts** (`ihm_m_cluster_bank_accounts`)
6. **Cluster Securities** (`ihm_m_cluster_securities`)
7. **Cluster Employees** (`ihm_m_cluster_employees`)
8. **Cluster Patrols** (`ihm_m_cluster_patrols`)
9. **Cluster Offices** (`ihm_m_cluster_offices`)
10. **Marketing Cluster** (`ihm_m_marketing_clusters`)
11. **Cluster** (main table - soft delete)

---

## Database Relations

### Cluster Model Relations
```php
// One-to-Many Relations
$cluster->offices()       // ClusterOffice
$cluster->patrols()       // ClusterPatrol
$cluster->employees()     // ClusterEmployee
$cluster->securities()    // ClusterSecurity
$cluster->bankAccounts()  // ClusterBankAccount
$cluster->residents()     // ClusterResident
$cluster->subscriptions() // ClusterSubscription
```

---

## Testing Checklist

### Manual Testing Steps:

1. **Login** ke admin panel
2. **Navigate** ke http://localhost:8000/admin/clusters
3. **Click Delete** button pada salah satu cluster
4. **Verify SweetAlert** muncul dengan informasi:
   - Nama cluster
   - List data yang akan dihapus
   - Warning message
5. **Click Batal** - pastikan tidak terjadi apa-apa
6. **Click Delete** lagi dan click **Ya, Hapus**
7. **Verify:**
   - Success message muncul
   - Cluster dihapus dari list
   - Data terkait di database juga terhapus
   - `deleted_id` terisi dengan user ID yang melakukan delete

### Database Verification:
```sql
-- Check soft delete
SELECT id, name, deleted_at, deleted_id 
FROM ihm_m_clusters 
WHERE deleted_at IS NOT NULL;

-- Verify cascade delete - semua harus 0 untuk cluster yang dihapus
SELECT 
    (SELECT COUNT(*) FROM ihm_m_cluster_offices WHERE ihm_m_clusters_id = :cluster_id) as offices,
    (SELECT COUNT(*) FROM ihm_m_cluster_patrols WHERE ihm_m_clusters_id = :cluster_id) as patrols,
    (SELECT COUNT(*) FROM ihm_m_cluster_employees WHERE ihm_m_clusters_id = :cluster_id) as employees,
    (SELECT COUNT(*) FROM ihm_m_cluster_securities WHERE ihm_m_clusters_id = :cluster_id) as securities,
    (SELECT COUNT(*) FROM ihm_m_cluster_bank_accounts WHERE ihm_m_clusters_id = :cluster_id) as bank_accounts,
    (SELECT COUNT(*) FROM ihm_m_cluster_residents WHERE ihm_m_clusters_id = :cluster_id) as residents,
    (SELECT COUNT(*) FROM ihm_cluster_subscriptions WHERE cluster_id = :cluster_id) as subscriptions,
    (SELECT COUNT(*) FROM ihm_iot_devices WHERE ihm_m_clusters_id = :cluster_id) as devices;
```

Expected Result: **Semua COUNT harus 0**

---

## Error Handling

### Possible Errors:

1. **Foreign Key Constraint Error**
   - **Cause:** Urutan delete tidak sesuai dengan foreign key dependencies
   - **Solution:** Transaction akan rollback, tidak ada data yang terhapus

2. **Permission Error**
   - **Cause:** User tidak memiliki permission untuk delete
   - **Solution:** Implementasi authorization check di controller

3. **Database Connection Error**
   - **Cause:** Database tidak tersedia
   - **Solution:** Error message ditampilkan, transaction rollback

### Error Display:
```html
<!-- Red alert akan muncul di top halaman -->
<div class="bg-red-50 border-l-4 border-red-500">
    <p class="text-red-800 font-medium">Gagal menghapus cluster: [error message]</p>
</div>
```

---

## UI/UX Improvements

### Before vs After

**Before:**
- Simple confirm dialog (browser default)
- Tidak ada informasi detail
- Tidak user-friendly

**After:**
- Modern SweetAlert2 dialog
- Informasi lengkap tentang data yang akan dihapus
- Warning yang jelas
- Button dengan icon yang intuitif
- Loading state saat proses delete

---

## Security Considerations

1. **CSRF Protection:** Form menggunakan `@csrf` token
2. **Method Spoofing:** Menggunakan `@method('DELETE')`
3. **Authorization:** Pastikan hanya user dengan permission yang bisa delete
4. **Audit Trail:** `deleted_id` mencatat siapa yang melakukan delete
5. **Soft Delete:** Data tidak benar-benar dihapus, bisa di-restore jika diperlukan

---

## Future Improvements

1. **Restore Feature:** Implementasi restore untuk soft deleted clusters
2. **Bulk Delete:** Menghapus multiple clusters sekaligus
3. **Delete Preview:** Tampilkan jumlah record yang akan dihapus per table
4. **Permission Check:** Implementasi policy/gate untuk authorization
5. **Activity Log:** Logging detail untuk audit purposes

---

## Related Files

### Modified:
- ✅ `resources/views/admin/clusters/index.blade.php`
- ✅ `app/Http/Controllers/Admin/ClusterController.php`

### Dependencies:
- `app/Models/Cluster.php`
- `app/Models/ClusterOffice.php`
- `app/Models/ClusterPatrol.php`
- `app/Models/ClusterEmployee.php`
- `app/Models/ClusterSecurity.php`
- `app/Models/ClusterBankAccount.php`
- `app/Models/ClusterResident.php`
- `app/Models/ClusterSubscription.php`
- `app/Models/ClusterBalanceWithdraw.php`
- `app/Models/IoTDevice.php`
- `app/Models/MarketingCluster.php`

### External Libraries:
- SweetAlert2 (already included in `resources/views/layouts/app.blade.php`)

---

## Conclusion

Implementasi cascade delete dengan transaction memastikan data integrity dan konsistensi database. SweetAlert memberikan user experience yang lebih baik dengan informasi yang jelas tentang aksi yang akan dilakukan. Error handling yang proper menjamin bahwa tidak ada data yang corrupt jika terjadi error di tengah proses delete.

---

**Last Updated:** December 6, 2025  
**Author:** Klaster Pintar Development Team
