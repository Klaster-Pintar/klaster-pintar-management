# Quick Testing Guide - Cluster Wizard

## Test Scenario 1: Minimal Required Data (Happy Path)

### Step 1: Basic Info
- **Name:** Test Cluster A
- **Phone:** 021-12345678
- **Email:** testcluster@example.com
- **Description:** (optional - leave blank)
- **Logo:** (optional - skip)
- **Picture:** (optional - skip)
- **Radius Checkin:** 5 (default)
- **Radius Patrol:** 5 (default)

### Step 2: Offices (REQUIRED)
1. Click on map to add 1 office marker
2. Marker will appear with auto-generated name "Kantor 1"
3. Coordinates will be captured automatically

### Steps 3-7: Skip All
- Click "Selanjutnya" through all remaining steps
- No data required (all optional)

### Expected Result
✅ Success alert: "Cluster berhasil didaftarkan"  
✅ Redirect to cluster detail page  
✅ Database records:
- 1 record in `ihm_m_clusters`
- 1 record in `ihm_m_cluster_d_offices`
- 0 records in other tables

---

## Test Scenario 2: Full Data Entry

### Step 1: Basic Info
- **Name:** Cluster Premium
- **Phone:** 021-87654321
- **Email:** premium@cluster.com
- **Description:** Cluster premium dengan fasilitas lengkap
- **Logo:** Upload JPG/PNG (max 2MB)
- **Picture:** Upload JPG/PNG (max 2MB)

### Step 2: Offices
1. Add 2 office markers by clicking on different locations
2. Drag markers to adjust positions if needed
3. Verify coordinates update on drag

### Step 3: Patrols
1. Click on map to add 3-5 patrol points
2. Points will be stored as JSON array
3. Keep day_type_id = 1 (default)

### Step 4: Employees
**Employee 1:**
- Name: John Doe
- Username: john.doe
- Email: john@example.com
- Phone: 08123456789

**Employee 2:**
- Name: Jane Smith
- Username: jane.smith
- Email: jane@example.com
- Phone: 08123456788

### Step 5: Securities
**Security 1:**
- Name: Security Alpha
- Username: security.alpha
- Email: alpha@security.com
- Phone: 08123456787

### Step 6: Bank Accounts
**Account 1:**
- Account Number: 1234567890
- Account Holder: Cluster Premium
- Bank Type: BCA
- Bank Code: 1 (default)

### Step 7: Residents
- Skip (CSV upload not yet implemented)

### Expected Result
✅ Success alert  
✅ Redirect to detail page  
✅ Database records:
- 1 cluster
- 2 offices with POINT geometry
- 1 patrol with JSON pinpoints
- 2 employees in `ihm_m_cluster_d_employees`
- 2 user accounts in `ihm_m_users` (role = EMPLOYEE, password = password123)
- 1 security in `ihm_m_cluster_d_securities`
- 1 user account (role = SECURITY)
- 1 bank account

---

## Test Scenario 3: Validation Errors

### Test 3.1: Missing Required Fields
**Steps:**
1. Leave "Name" blank
2. Go to Step 7
3. Click "Selesai & Simpan"

**Expected:**
❌ Error alert: "Data Tidak Lengkap - Mohon lengkapi Nama Cluster, Telepon, dan Email"  
✅ Redirect to Step 1

### Test 3.2: No Offices Added
**Steps:**
1. Fill Step 1 completely
2. Skip Step 2 (don't add any office markers)
3. Go to Step 7
4. Click "Selesai & Simpan"

**Expected:**
❌ Error alert: "Kantor Belum Ditambahkan - Minimal tambahkan 1 lokasi kantor cluster"  
✅ Redirect to Step 2

### Test 3.3: Duplicate Username
**Steps:**
1. Complete Step 1-2
2. In Step 4, add employee with username: "admin" (assume this exists)
3. Submit form

**Expected:**
❌ Validation error alert with message: "Username 'admin' sudah digunakan"  
✅ Transaction rolled back (no data saved)

---

## Test Scenario 4: Transaction Rollback

### Test 4.1: Database Error Midway
**Setup:**
1. Temporarily disable network/database
2. Or: Set invalid `bank_code_id` in Step 6

**Steps:**
1. Fill all steps with valid data
2. Submit form

**Expected:**
❌ Server error alert: "Terjadi kesalahan saat menyimpan data cluster"  
✅ Show retry option  
✅ Transaction rolled back (no partial data)  
✅ Uploaded files deleted from storage  
✅ User can retry after fixing issue

### Test 4.2: File Upload + Rollback
**Steps:**
1. Upload logo and picture in Step 1
2. Fill Step 2 (add office)
3. In Step 4, use duplicate username
4. Submit form

**Expected:**
❌ Validation error  
✅ Transaction rolled back  
✅ **Uploaded logo and picture deleted from storage** (no orphan files)

---

## Test Scenario 5: Google Maps Interaction

### Test 5.1: Office Markers
**Add Marker:**
1. Click on map → Marker appears
2. Info window shows with "Kantor 1"
3. "Hapus" button visible

**Drag Marker:**
1. Drag marker to new position
2. Info window updates with new coordinates
3. formData.offices array updated

**Delete Marker:**
1. Click marker → Info window opens
2. Click "Hapus" button
3. Marker disappears immediately
4. Entry removed from formData.offices array

### Test 5.2: Patrol Markers
**Add Patrol Points:**
1. Switch to Step 3
2. Click on map multiple times
3. Each click adds a patrol point
4. Markers numbered (1, 2, 3...)

**Delete Patrol Point:**
1. Click marker → Info window
2. Click "Hapus"
3. Marker removed
4. formData.patrols[0].pinpoints updated

---

## Browser Console Checks

### Success Flow
```javascript
// After successful submission, check network tab:
POST /admin/clusters/wizard/store
Status: 201 Created
Response: {
  "success": true,
  "message": "Cluster berhasil dibuat!",
  "data": {
    "cluster_id": 1,
    "cluster_name": "Test Cluster A",
    "summary": {...}
  },
  "redirect": "/admin/clusters/1"
}
```

### Error Flow
```javascript
// Validation error:
POST /admin/clusters/wizard/store
Status: 422 Unprocessable Entity
Response: {
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "name": ["Nama cluster wajib diisi"]
  }
}

// Server error:
Status: 500 Internal Server Error
Response: {
  "success": false,
  "message": "Terjadi kesalahan saat menyimpan data cluster",
  "error": "Detailed error message"
}
```

---

## Database Verification Queries

### Check Cluster Creation
```sql
-- Main cluster
SELECT * FROM ihm_m_clusters ORDER BY id DESC LIMIT 1;

-- Offices with POINT geometry
SELECT id, name, ST_AsText(location_point) as coordinates 
FROM ihm_m_cluster_d_offices 
WHERE ihm_m_clusters_id = 1;

-- Patrols with JSON pinpoints
SELECT id, day_type_id, pinpoints 
FROM ihm_m_cluster_d_patrols 
WHERE ihm_m_clusters_id = 1;

-- Employees (linked to users)
SELECT ce.id, u.name, u.username, u.role 
FROM ihm_m_cluster_d_employees ce
JOIN ihm_m_users u ON ce.employee_id = u.id
WHERE ce.ihm_m_clusters_id = 1;

-- Securities (linked to users)
SELECT cs.id, u.name, u.username, u.role 
FROM ihm_m_cluster_d_securities cs
JOIN ihm_m_users u ON cs.security_id = u.id
WHERE cs.ihm_m_clusters_id = 1;

-- Bank accounts
SELECT * FROM ihm_m_cluster_d_bank_accounts 
WHERE ihm_m_clusters_id = 1;
```

### Check User Accounts Created
```sql
-- All users created by wizard
SELECT id, name, username, email, role, roles, created_at 
FROM ihm_m_users 
WHERE username LIKE '%.%' -- Assuming username format: first.last
ORDER BY created_at DESC;

-- Verify default password (should be hashed)
SELECT username, password FROM ihm_m_users WHERE id = 1;
-- Password should start with $2y$ (bcrypt)
```

### Check Transaction Rollback
```sql
-- After a failed transaction, verify NO records created:
SELECT COUNT(*) FROM ihm_m_clusters WHERE name = 'Failed Cluster';
-- Should return 0

-- Check for orphan files in storage/app/public/clusters/
-- Should be empty if rollback worked
```

---

## Performance Testing

### Load Test (Optional)
1. Create 10 clusters sequentially
2. Each with 2 offices, 1 patrol, 2 employees, 1 security
3. Monitor database connections
4. Check transaction duration in logs

**Expected:**
- Each submission < 2 seconds
- No database deadlocks
- No memory leaks
- Consistent response times

---

## Error Log Monitoring

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

**During successful submission:**
- No errors logged
- Only info/debug messages (if enabled)

**During failed submission:**
- Error message with full stack trace
- File path and line number
- User context (ID, action)

---

## Rollback Scenario Verification

### Manual Rollback Test
1. Add `throw new \Exception('Test rollback');` in ClusterService after creating cluster
2. Submit wizard with full data
3. Verify:
   - ❌ No cluster record in database
   - ❌ No office records
   - ❌ No user accounts created
   - ❌ No uploaded files in storage
   - ✅ Error alert shows to user
   - ✅ User can retry

4. Remove test exception
5. Retry submission
6. Verify all data saves successfully

---

## Security Testing

### Test 1: CSRF Token
1. Remove CSRF token from form
2. Submit
3. Expected: 419 Page Expired

### Test 2: File Upload Validation
1. Try uploading .exe file as logo
2. Expected: Validation error "Logo harus berupa gambar"

### Test 3: SQL Injection Prevention
1. Enter `'; DROP TABLE users; --` as cluster name
2. Expected: String saved as-is (Eloquent escapes it)

### Test 4: XSS Prevention
1. Enter `<script>alert('XSS')</script>` as description
2. Expected: Script not executed when displayed

---

## User Acceptance Testing Checklist

- [ ] User can navigate through all 7 steps smoothly
- [ ] "Kembali" button works correctly
- [ ] Progress indicator shows current step
- [ ] Required fields are clearly marked with *
- [ ] Google Maps loads without errors
- [ ] Office markers can be added, dragged, and deleted
- [ ] Patrol markers can be added and deleted
- [ ] Form remembers data when switching steps
- [ ] Loading spinner shows during submission
- [ ] Success message is clear and professional
- [ ] Error messages are helpful and actionable
- [ ] Redirect to detail page works
- [ ] All data is correctly displayed in detail page
- [ ] Indonesian language used throughout
- [ ] Responsive design works on mobile/tablet
- [ ] No console errors

---

## Final Checklist Before Production

### Code Quality
- [ ] No commented-out code
- [ ] No console.log() statements
- [ ] No TODO/FIXME comments
- [ ] All variables properly named
- [ ] Code follows PSR-12 standards

### Configuration
- [ ] GOOGLE_MAPS_API_KEY set in .env
- [ ] Database connection working
- [ ] Storage link created (`php artisan storage:link`)
- [ ] File permissions correct (775 for storage/)
- [ ] TABLE_PREFIX configured correctly

### Security
- [ ] CSRF protection enabled
- [ ] File upload validation working
- [ ] SQL injection tested
- [ ] XSS prevention verified
- [ ] Password hashing confirmed

### Documentation
- [ ] README updated
- [ ] API documentation complete
- [ ] User manual created
- [ ] Admin guide available

### Monitoring
- [ ] Error logging enabled
- [ ] Log rotation configured
- [ ] Backup strategy in place
- [ ] Monitoring alerts set up

---

## Success Criteria

✅ User can complete wizard in < 5 minutes  
✅ Zero data loss on transaction rollback  
✅ Professional error messages  
✅ No console errors  
✅ Mobile responsive  
✅ All validation rules enforced  
✅ Auto user account creation works  
✅ File uploads handled correctly  
✅ Maps integration smooth  
✅ SweetAlert notifications professional  

---

**Testing Status:** Ready for QA ✅  
**Last Updated:** November 22, 2024
