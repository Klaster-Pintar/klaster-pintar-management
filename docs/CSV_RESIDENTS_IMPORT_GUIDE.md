# CSV Residents Import - Panduan Lengkap

## 📋 Overview

Step 7 dalam wizard pendaftaran cluster memungkinkan import data residents (warga/penghuni) secara massal menggunakan file CSV. Fitur ini dirancang untuk efisiensi dan keamanan data dengan validasi lengkap.

---

## 🎯 Fitur Utama

### ✅ Upload & Preview

-   Upload file CSV (maksimal 2MB)
-   Preview data sebelum import
-   Validasi real-time
-   Download template CSV

### ✅ Validasi Otomatis

-   Validasi format kolom
-   Cek duplikasi No HP dalam file
-   Cek duplikasi alamat rumah
-   Validasi status rumah dan user
-   Error reporting yang jelas

### ✅ Smart Import

-   Auto-detect user existing (by phone number)
-   Create user baru jika belum ada
-   Insert ke 3 tabel: `ihm_m_users`, `ihm_m_residents`, `ihm_m_cluster_d_residents`
-   Join relationship otomatis
-   Handling error per row

---

## 📄 Format CSV

### Template Structure

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
John Doe,081234567890,A,01,Milik,Active,500000
Jane Smith,081234567891,A,02,Kontrak,Active,500000
Bob Johnson,081234567892,B,01,Milik,Inactive,500000
```

### Kolom Wajib

| Kolom            | Deskripsi                     | Format                   | Required    |
| ---------------- | ----------------------------- | ------------------------ | ----------- |
| **Nama**         | Nama lengkap resident         | String                   | ✅ Yes      |
| **No HP**        | Nomor HP (akan jadi username) | Numeric string           | ✅ Yes      |
| **Blok**         | Blok rumah                    | String (A, B, C, dll)    | ✅ Yes      |
| **Nomor**        | Nomor rumah                   | String (01, 02, dll)     | ✅ Yes      |
| **Status Rumah** | Status kepemilikan            | "Milik" atau "Kontrak"   | ⚠️ Optional |
| **Status User**  | Status akun user              | "Active" atau "Inactive" | ⚠️ Optional |
| **Nominal IPL**  | Nominal iuran (Rupiah)        | Numeric                  | ⚠️ Optional |

### Aturan Validasi

1. **Nama**

    - Tidak boleh kosong
    - Maksimal 255 karakter

2. **No HP**

    - Wajib diisi
    - Harus berisi angka saja (0-9)
    - Akan digunakan sebagai `username`
    - Akan di-cek duplikasi di database
    - Jika sudah exist → gunakan user existing
    - Jika belum exist → create user baru

3. **Blok & Nomor**

    - Keduanya wajib diisi
    - Kombinasi Blok + Nomor harus unik dalam 1 cluster
    - Warning jika rumah sudah terisi

4. **Status Rumah**

    - Hanya menerima: `Milik` atau `Kontrak`
    - Case sensitive
    - Default: kosong (jika tidak diisi)

5. **Status User**

    - Hanya menerima: `Active` atau `Inactive`
    - Case sensitive
    - `Active` → user dapat login
    - `Inactive` → user tidak dapat login
    - Default: `Active`

6. **Nominal IPL**
    - Harus berupa angka
    - Tanpa titik atau koma ribuan
    - Contoh: `500000` (bukan `500.000` atau `Rp 500.000`)
    - Default: 0

---

## 🔄 Workflow Import

### 1. Persiapan File

```bash
# Download template dari wizard
- Klik "Download Template CSV"
- Buka dengan Excel / Google Sheets
- Isi data sesuai format
- Save as CSV (Comma delimited)
```

### 2. Upload & Preview

```
User Action:
├── Klik "Pilih File CSV"
├── Select file (max 2MB)
└── File ter-upload

System Response:
├── Parse CSV data
├── Validasi setiap row
├── Tampilkan preview tabel
├── Hitung valid count & error count
└── Tampilkan error messages
```

### 3. Validasi Real-time

**Preview Table Display:**

-   ✅ **Valid rows** → Background putih, badge hijau "Valid"
-   ❌ **Error rows** → Background merah muda, badge merah "Error"
-   Status badge warna per kategori
-   Error message di kolom "Keterangan"

**Validation Summary:**

```
Total: 50 residents
Valid: 45
Error: 5

Errors:
- Baris 3: No HP duplikat dalam file CSV
- Baris 7: Status Rumah harus "Milik" atau "Kontrak"
- Baris 12: No HP wajib diisi
...
```

### 4. Submit Data

```
User clicks: "Selesai & Simpan" (Step 7)

Backend Process:
├── Validasi ulang server-side
├── Loop setiap row CSV (yang valid)
│   ├── Cek user by phone (username)
│   │   ├── Jika exist → ambil user_id
│   │   └── Jika tidak → create user baru
│   │       └── Password default: "password123"
│   ├── Create resident di ihm_m_residents
│   ├── Cek duplikasi house (blok + nomor)
│   └── Create link di ihm_m_cluster_d_residents
└── Return success/error summary
```

---

## 🗄️ Database Schema

### Table Relationships

```
ihm_m_users (User)
    ↓ (user_id)
ihm_m_residents (Resident Details)
    ↓ (resident_id = resident.id)
ihm_m_cluster_d_residents (Cluster-Resident Link)
    ↓ (house_id = cluster_d_resident.id)
```

### Insert Logic

**1. Check/Create User in `ihm_m_users`**

```php
// Cek apakah user dengan phone (username) sudah ada
$existingUser = User::where('username', $phone)->first();

if ($existingUser) {
    // Gunakan user existing
    $userId = $existingUser->id;
} else {
    // Create user baru
    $newUser = User::create([
        'name' => $name,
        'username' => $phone, // Username = No HP
        'phone' => $phone,
        'password' => Hash::make('password123'),
        'role' => 'RESIDENT',
        'status' => 'VERIFIED',
        'active_flag' => true,
    ]);
    $userId = $newUser->id;
}
```

**2. Create Resident in `ihm_m_residents`**

```php
$resident = Resident::create([
    'name' => $name,
    'username' => $phone,
    'phone' => $phone,
    'house_block' => $houseBlock,
    'house_number' => $houseNumber,
    'role' => 'RESIDENT',
    'status' => $userStatus === 'Active' ? 'VERIFIED' : 'PENDING',
    'active_flag' => $userStatus === 'Active',
    'created_id' => Auth::id(),
]);
```

**3. Link to Cluster in `ihm_m_cluster_d_residents`**

```php
ClusterResident::create([
    'ihm_m_clusters_id' => $cluster->id,
    'resident_id' => $resident->id,
    'created_id' => Auth::id(),
]);
```

### Warning Conditions

**❌ Error jika:**

-   No HP sudah exist di `ihm_m_users` DAN sudah terdaftar di cluster yang sama dengan house yang sama
-   Kombinasi Blok + Nomor sudah terisi di cluster ini (belum di-delete)

**✅ OK jika:**

-   No HP sudah exist tapi di cluster berbeda → Create link baru
-   No HP sudah exist tapi rumah berbeda → Create link baru
-   No HP baru → Create user & resident baru

---

## 🎨 UI Features

### Upload Section

```html
- Icon CSV besar (purple) - Button "Download Template CSV" (purple) - Input file
(hidden, trigger by label) - Button "Pilih File CSV" (gradient purple-indigo) -
Info: "File maksimal 2MB, format CSV"
```

### Preview Table

```html
Header: Gradient purple-indigo Columns: - No (index) - Status (badge: green
valid / red error) - Nama (bold) - No HP - Blok - Nomor - Status Rumah (badge:
blue Milik / orange Kontrak) - Status User (badge: green Active / gray Inactive)
- Nominal IPL (format Rupiah) - Keterangan (error message) Features: - Sticky
header - Max height 400px with scroll - Hover effect (green-50) - Error rows →
red background (red-50)
```

### Validation Summary

```html
Error Alert (if csvErrorCount > 0): - Red background (red-50) - Warning icon -
List of errors (max 10 shown) - Info: "Data dengan error akan di-skip saat save"
```

### Action Buttons

```html
- "Hapus" → Clear all CSV data - "Validasi Ulang" → Re-run validation - Hidden
input → JSON stringify valid rows
```

---

## 🔒 Security & Validation

### Frontend Validation (Alpine.js)

```javascript
✓ File size check (max 2MB)
✓ File type check (.csv only)
✓ Required fields check
✓ Phone format (numeric only)
✓ Status values (Milik/Kontrak, Active/Inactive)
✓ Nominal IPL format (numeric)
✓ Duplicate phone in CSV
✓ Duplicate house address in CSV
```

### Backend Validation (Laravel)

```php
✓ JSON decode validation
✓ Database transaction (rollback on error)
✓ User exist check (prevent duplicate users)
✓ House occupancy check (prevent duplicate house)
✓ Error handling per row (skip error, continue others)
✓ Password hashing for new users
```

### Error Handling

**Frontend:**

-   Error badge per row
-   Error message in "Keterangan" column
-   Error summary panel (red alert)
-   Error count in header

**Backend:**

```php
try {
    $this->processResidentImport($clusterId, $residentData, Auth::id());
    $residentsImported++;
} catch (\Exception $e) {
    $residentsErrors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
}
```

**Success Message:**

```
"Cluster berhasil dibuat! 🎉 45 residents berhasil di-import."
```

**Partial Success:**

```
"Cluster berhasil dibuat! 🎉 45 residents berhasil di-import.
Namun ada beberapa error: Baris 3: No HP duplikat; Baris 7: Status invalid; ..."
```

---

## 🧪 Testing Scenarios

### Test Case 1: Normal Import

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
Ahmad,081234567890,A,01,Milik,Active,500000
Budi,081234567891,A,02,Kontrak,Active,500000
```

**Expected:** ✅ 2 residents imported successfully

### Test Case 2: Duplicate Phone in CSV

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
Ahmad,081234567890,A,01,Milik,Active,500000
Budi,081234567890,A,02,Kontrak,Active,500000  ← Same phone
```

**Expected:** ❌ Error on row 3 "No HP duplikat dalam file CSV"

### Test Case 3: Duplicate House

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
Ahmad,081234567890,A,01,Milik,Active,500000
Budi,081234567891,A,01,Kontrak,Active,500000  ← Same block+number
```

**Expected:** ❌ Error on row 3 "Alamat A-01 duplikat dalam file CSV"

### Test Case 4: Existing User (Different House)

```
Scenario:
- User phone 081234567890 already exists in ihm_m_users
- Import CSV with same phone but different house (B-05)

Expected:
✅ Use existing user_id
✅ Create new resident record
✅ Link to new house B-05
```

### Test Case 5: Invalid Status

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
Ahmad,081234567890,A,01,Sewa,Active,500000  ← Invalid "Sewa"
```

**Expected:** ❌ Error "Status Rumah harus 'Milik' atau 'Kontrak'"

### Test Case 6: Missing Required Field

```csv
Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
,081234567890,A,01,Milik,Active,500000  ← Missing name
```

**Expected:** ❌ Error "Nama wajib diisi"

---

## 📝 Best Practices

### For Users

1. **Download template** terlebih dahulu
2. **Isi data dengan teliti** sesuai format
3. **Validasi di Excel** sebelum upload
4. **Test dengan sample kecil** (5-10 rows) dulu
5. **Cek preview** sebelum submit
6. **Backup data** sebelum import besar

### For Developers

1. **Always validate** both frontend & backend
2. **Use DB transactions** untuk data integrity
3. **Handle errors gracefully** per row
4. **Log import activities** for audit
5. **Provide clear error messages**
6. **Test edge cases** thoroughly

---

## 🚀 Future Enhancements

### Potential Features

-   [ ] Excel (.xlsx) support
-   [ ] Bulk edit after preview
-   [ ] Import history log
-   [ ] Export residents to CSV
-   [ ] Import progress bar (for large files)
-   [ ] Email notification after import
-   [ ] Import scheduling
-   [ ] Undo import feature

### Performance Optimization

-   [ ] Queue processing for large imports (>100 rows)
-   [ ] Chunk processing (batch insert)
-   [ ] Background job with progress notification
-   [ ] Redis caching for validation rules

---

## 🆘 Troubleshooting

### Problem: "File terlalu besar"

**Solution:**

-   Kompres CSV dengan menghapus kolom tidak perlu
-   Split file menjadi beberapa batch
-   Import bertahap (max 100 rows per file)

### Problem: "Error parsing CSV"

**Solution:**

-   Pastikan delimiter adalah koma (,)
-   Cek encoding file (harus UTF-8)
-   Hapus baris kosong di akhir file
-   Escape karakter khusus dengan quotes

### Problem: "No HP sudah exist"

**Solution:**

-   Jika memang user baru → ganti nomor HP
-   Jika user existing → sistem akan auto-link (OK)
-   Cek di database apakah benar sudah exist

### Problem: "Rumah sudah terisi"

**Solution:**

-   Cek database: apakah rumah memang sudah ada resident?
-   Jika data lama → soft delete data lama dulu
-   Jika data baru → ganti blok/nomor rumah

---

## 📚 Related Files

### Views

-   `resources/views/admin/clusters/wizard/steps/step7-residents.blade.php`
-   `resources/views/admin/clusters/wizard/index.blade.php`

### Controllers

-   `app/Http/Controllers/Admin/ClusterController.php`
    -   Method: `store()` → Handle wizard submission
    -   Method: `processResidentImport()` → Process each CSV row

### Models

-   `app/Models/Resident.php`
-   `app/Models/ClusterResident.php`
-   `app/Models/User.php`

### Migrations

-   `database/migrations/*_ihm_m_residents.php`
-   `database/migrations/*_ihm_m_cluster_d_residents_table.php`
-   `database/migrations/*_ihm_m_users_table.php`

---

## 📞 Support

Untuk pertanyaan atau issue terkait CSV import:

1. Cek dokumentasi ini terlebih dahulu
2. Test dengan template CSV yang disediakan
3. Review error messages di preview table
4. Contact development team jika issue persist

---

**Last Updated:** December 1, 2025  
**Version:** 1.0.0  
**Maintained by:** Klaster Pintar Development Team
