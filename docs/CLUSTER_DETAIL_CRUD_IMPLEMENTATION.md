# Cluster Detail CRUD Implementation Guide

## ✅ Yang Sudah Dibuat

### 1. Backend Controller Methods (ClusterController.php)
Sudah ditambahkan methods berikut:
- `updateBasicInfo()` - Update informasi dasar cluster
- `storeOffice()`, `updateOffice()`, `deleteOffice()` - CRUD Kantor
- `storePatrol()`, `updatePatrol()`, `deletePatrol()` - CRUD Patroli
- `storeBankAccount()`, `updateBankAccount()`, `deleteBankAccount()` - CRUD Bank
- `storeEmployee()`, `updateEmployee()`, `deleteEmployee()` - CRUD Karyawan
- `storeSecurity()`, `updateSecurity()`, `deleteSecurity()` - CRUD Security

### 2. Routes (web.php)
Semua routes sudah terdaftar di `/admin/clusters/{cluster}/...`

### 3. JavaScript CRUD Operations
File: `public/js/cluster-detail-crud.js` (SUDAH DIBUAT)
Berisi semua function untuk handle CRUD operations dengan Google Maps integration.

## 📋 Yang Perlu Dilengkapi

### Modifikasi File `resources/views/admin/clusters/show.blade.php`

Karena file existing sudah 694 lines, saya akan berikan cara paling efisien yaitu **REPLACE SELURUH FILE** dengan versi yang sudah include semua CRUD modal dan table actions.

#### Langkah Manual (Recommended):

1. **Backup file existing**:
   ```powershell
   Copy-Item resources/views/admin/clusters/show.blade.php resources/views/admin/clusters/show.blade.php.backup
   ```

2. **Update bagian header** - Tambahkan button "Edit Info Dasar":
   Cari bagian actions button (sekitar line 90-100), ganti dengan:
   ```blade
   <!-- Actions -->
   <div class="flex gap-2">
       <button onclick="openBasicInfoModal({{ json_encode($cluster) }})" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
           <i class="fa-solid fa-edit mr-1"></i> Edit Info Dasar
       </button>
       <a href="{{ route('admin.clusters.index') }}" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
           <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
       </a>
   </div>
   ```

3. **Update Tab Kantor** - Tambahkan action buttons:
   Di dalam loop `@foreach($cluster->offices as $index => $office)`, tambahkan kolom Action:
   ```blade
   <td class="px-6 py-4 whitespace-nowrap text-center">
       <button onclick="openOfficeModal({{ json_encode($office) }})" 
           class="text-blue-600 hover:text-blue-800 mr-2">
           <i class="fa-solid fa-edit"></i>
       </button>
       <button onclick="deleteOffice({{ $cluster->id }}, {{ $office->id }}, '{{ $office->name }}')" 
           class="text-red-600 hover:text-red-800">
           <i class="fa-solid fa-trash"></i>
       </button>
   </td>
   ```

4. **Update button "Tambah Kantor"**:
   ```blade
   <button onclick="openOfficeModal()" 
      class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
       <i class="fa-solid fa-plus mr-1"></i> Tambah Kantor
   </button>
   ```

5. **Ulangi untuk Tab lainnya** (Patroli, Bank, Employee, Security):
   - Tambahkan action column dengan button Edit & Delete
   - Update button "Tambah" dengan onclick handler
   - Panggil function yang sesuai dari `cluster-detail-crud.js`

6. **Tambahkan Modal HTML** - Di akhir file sebelum `@endsection`, tambahkan:

```blade
<!-- Modal Basic Info -->
<div id="modalBasicInfo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fa-solid fa-edit text-blue-600 mr-2"></i>
                    Edit Informasi Dasar Cluster
                </h3>
                <button onclick="closeBasicInfoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Cluster</label>
                    <input type="text" id="basicInfoName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="basicInfoDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                        <input type="text" id="basicInfoPhone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" id="basicInfoEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Check-in (meter)</label>
                        <input type="number" id="basicInfoRadiusCheckin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Radius Patrol (meter)</label>
                        <input type="number" id="basicInfoRadiusPatrol" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" id="basicInfoActiveFlag" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm font-semibold text-gray-700">Status Aktif</span>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
            <button onclick="closeBasicInfoModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                Batal
            </button>
            <button onclick="saveBasicInfo({{ $cluster->id }})" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fa-solid fa-save mr-2"></i>Simpan
            </button>
        </div>
    </div>
</div>

<!-- Similar modals for Office, Patrol, Bank, Employee, Security... -->
<!-- (Copy pattern dari modal Basic Info di atas) -->
```

7. **Include JavaScript di @push('scripts')**:
```blade
@push('scripts')
<script src="{{ asset('js/cluster-detail-crud.js') }}"></script>
<script>
// Residents upload script yang sudah ada
// ...
</script>
@endpush
```

## 🎯 Alternatif: Full File Replacement

Karena modifikasi manual cukup banyak, saya bisa buatkan **COMPLETE FILE** yang sudah include semua:
- All modal HTML
- All table with action buttons  
- Google Maps integration
- JavaScript integration

Apakah Anda ingin saya buatkan **complete file** langsung yang tinggal replace?

## ✨ Fitur yang Akan Tersedia Setelah Implementation

1. **Edit Info Dasar** - Modal edit nama, desc, phone, email, radius, status
2. **Tab Kantor** - Tambah/Edit dengan Google Maps marker, Delete
3. **Tab Patroli** - Tambah/Edit dengan Google Maps polyline, Delete
4. **Tab Rekening Bank** - Full CRUD dengan modal form
5. **Tab Karyawan** - Full CRUD dengan role selection
6. **Tab Security** - Full CRUD
7. **Tab Warga** - Upload CSV/Excel (sudah ada)

Semua menggunakan:
- SweetAlert2 untuk konfirmasi & notifikasi
- Google Maps untuk Kantor & Patroli
- AJAX untuk submit tanpa reload (kecuali success)
- Validation di backend
- Professional UI dengan Tailwind CSS

## 📝 Notes

- File JavaScript sudah di `public/js/cluster-detail-crud.js`
- Semua routes sudah terdaftar
- Backend controller sudah lengkap
- Tinggal update view saja

Silakan pilih: manual update atau full file replacement?
