# CSV Upload Guide - Employees & Securities

## Format CSV untuk Karyawan (Employees)

### Kolom yang Diperlukan:

1. **Nama** - Nama lengkap karyawan (Required)
2. **Username** - Username unik untuk login (Required)
3. **Email** - Email karyawan (Optional)
4. **Phone** - Nomor telepon (Optional)
5. **Role** - Jabatan: RT, RW, ADMIN (Required)
6. **Password** - Password minimal 8 karakter (Required)

### Contoh Template CSV Karyawan:

```csv
Nama,Username,Email,Phone,Role,Password
Ahmad Sulaiman,ahmad.rt01,ahmad@example.com,081234567890,RT,password123
Budi Santoso,budi.rw02,budi@example.com,081234567891,RW,password123
Citra Admin,citra.admin,citra@example.com,081234567892,ADMIN,password123
```

### Validasi Karyawan:

-   Nama, Username, Role, Password: **Wajib diisi**
-   Username: Harus unik, belum terdaftar di sistem
-   Role: Harus salah satu dari RT, RW, ADMIN
-   Password: Minimal 8 karakter
-   Email: Format email yang valid (jika diisi)
-   Phone: Hanya angka (jika diisi)

---

## Format CSV untuk Security

### Kolom yang Diperlukan:

1. **Nama** - Nama lengkap security (Required)
2. **Username** - Username unik untuk login (Required)
3. **Email** - Email security (Optional)
4. **Phone** - Nomor telepon (Optional)
5. **Password** - Password minimal 8 karakter (Required)

### Contoh Template CSV Security:

```csv
Nama,Username,Email,Phone,Password
Eko Prasetyo,eko.security01,eko@example.com,081234567893,password123
Fajar Hidayat,fajar.security02,fajar@example.com,081234567894,password123
Gunawan,gunawan.security03,gunawan@example.com,081234567895,password123
```

### Validasi Security:

-   Nama, Username, Password: **Wajib diisi**
-   Username: Harus unik, belum terdaftar di sistem
-   Password: Minimal 8 karakter
-   Email: Format email yang valid (jika diisi)
-   Phone: Hanya angka (jika diisi)
-   Role: Otomatis di-set sebagai "SECURITY"

---

## Struktur Database

### Table: ihm_m_users

-   Menyimpan semua user (karyawan, security, residents)
-   Kolom: id, name, username, email, phone, password, role, dll

### Table: ihm_m_cluster_d_employees

-   Mapping antara cluster dengan karyawan
-   Kolom: id, ihm_m_clusters_id, employee_id (FK ke ihm_m_users)

### Table: ihm_m_cluster_d_securities

-   Mapping antara cluster dengan security
-   Kolom: id, ihm_m_clusters_id, security_id (FK ke ihm_m_users)

---

## Alur Proses Import

### Karyawan (Employees):

1. **Parse CSV** - Baca file CSV dan validasi format
2. **Validasi Data** - Cek required fields, format, duplikasi
3. **Preview** - Tampilkan data dengan status valid/error
4. **Import** - Ketika wizard di-submit:
    - Cek apakah username sudah ada di ihm_m_users
    - Jika belum ada: Create user baru dengan role dari CSV
    - Jika sudah ada: Gunakan user_id existing
    - Insert ke ihm_m_cluster_d_employees

### Security:

1. **Parse CSV** - Baca file CSV dan validasi format
2. **Validasi Data** - Cek required fields, format, duplikasi
3. **Preview** - Tampilkan data dengan status valid/error
4. **Import** - Ketika wizard di-submit:
    - Cek apakah username sudah ada di ihm_m_users
    - Jika belum ada: Create user baru dengan role "SECURITY"
    - Jika sudah ada: Gunakan user_id existing
    - Insert ke ihm_m_cluster_d_securities

---

## Perubahan pada ClusterController

### Validation Rules:

```php
'employees_csv_data' => 'nullable|json',
'securities_csv_data' => 'nullable|json',
```

### Process Methods:

```php
private function processEmployeeImport($clusterId, $employeeData, $createdId)
{
    // Check existing user by username
    $user = User::where('username', $employeeData['username'])->first();

    if (!$user) {
        // Create new user
        $user = User::create([
            'name' => $employeeData['name'],
            'username' => $employeeData['username'],
            'email' => $employeeData['email'] ?? null,
            'phone' => $employeeData['phone'] ?? null,
            'role' => $employeeData['role'], // RT, RW, ADMIN
            'password' => bcrypt($employeeData['password']),
            'created_id' => $createdId,
            'active_flag' => true,
            'status' => 'ACTIVE',
        ]);
    }

    // Create employee link
    ClusterEmployee::create([
        'ihm_m_clusters_id' => $clusterId,
        'employee_id' => $user->id,
        'created_id' => $createdId,
    ]);
}

private function processSecurityImport($clusterId, $securityData, $createdId)
{
    // Check existing user by username
    $user = User::where('username', $securityData['username'])->first();

    if (!$user) {
        // Create new user with SECURITY role
        $user = User::create([
            'name' => $securityData['name'],
            'username' => $securityData['username'],
            'email' => $securityData['email'] ?? null,
            'phone' => $securityData['phone'] ?? null,
            'role' => 'SECURITY',
            'password' => bcrypt($securityData['password']),
            'created_id' => $createdId,
            'active_flag' => true,
            'status' => 'ACTIVE',
        ]);
    }

    // Create security link
    ClusterSecurity::create([
        'ihm_m_clusters_id' => $clusterId,
        'security_id' => $user->id,
        'created_id' => $createdId,
    ]);
}
```

---

## Alpine.js State Variables

### For Employees:

```javascript
employeeInputMode: 'manual', // 'manual' or 'csv'
employeeCsvFileName: '',
employeeCsvPreviewData: [],
employeeCsvValidCount: 0,
employeeCsvErrorCount: 0,
employeeCsvValidationErrors: [],
```

### For Securities:

```javascript
securityInputMode: 'manual', // 'manual' or 'csv'
securityCsvFileName: '',
securityCsvPreviewData: [],
securityCsvValidCount: 0,
securityCsvErrorCount: 0,
securityCsvValidationErrors: [],
```

---

## Functions to Add

### Employee CSV Functions:

-   `downloadEmployeeCsvTemplate()` - Download template CSV karyawan
-   `handleEmployeeCsvUpload(event)` - Handle file upload
-   `parseEmployeeCsvData(csvText)` - Parse CSV text
-   `validateEmployeeCsvData()` - Validate all rows
-   `clearEmployeeCsvData()` - Clear preview

### Security CSV Functions:

-   `downloadSecurityCsvTemplate()` - Download template CSV security
-   `handleSecurityCsvUpload(event)` - Handle file upload
-   `parseSecurityCsvData(csvText)` - Parse CSV text
-   `validateSecurityCsvData()` - Validate all rows
-   `clearSecurityCsvData()` - Clear preview

---

## Error Messages

### Karyawan:

-   "Nama wajib diisi"
-   "Username wajib diisi"
-   "Role wajib diisi"
-   "Password wajib diisi"
-   "Role harus RT, RW, atau ADMIN"
-   "Password minimal 8 karakter"
-   "Username sudah terdaftar"
-   "Format email tidak valid"

### Security:

-   "Nama wajib diisi"
-   "Username wajib diisi"
-   "Password wajib diisi"
-   "Password minimal 8 karakter"
-   "Username sudah terdaftar"
-   "Format email tidak valid"

---

## Testing Checklist

-   [ ] Download template CSV karyawan
-   [ ] Upload template dan validasi berhasil
-   [ ] Test validasi: required fields
-   [ ] Test validasi: role yang tidak valid
-   [ ] Test validasi: password < 8 karakter
-   [ ] Test validasi: username duplikat dalam CSV
-   [ ] Test import: create user baru
-   [ ] Test import: gunakan user existing
-   [ ] Download template CSV security
-   [ ] Upload template dan validasi berhasil
-   [ ] Test validasi: required fields
-   [ ] Test validasi: password < 8 karakter
-   [ ] Test import security
-   [ ] Test combined: manual + CSV input
-   [ ] Test wizard flow lengkap

---

Last Updated: December 1, 2025
