/**
 * Cluster Detail CRUD Operations
 * Handles all CRUD for Offices, Patrols, Banks, Employees, Securities
 */

// Global variables for maps
let officeMap = null;
let officeMarker = null;
let patrolMap = null;
let patrolMarkers = [];
let patrolPolyline = null;

// ==================== BASIC INFO ====================

function openBasicInfoModal() {
    // Show modal - data already populated from server-side
    document.getElementById('modalBasicInfo').classList.remove('hidden');
}

function closeBasicInfoModal() {
    document.getElementById('modalBasicInfo').classList.add('hidden');
}

async function saveBasicInfo(clusterId) {
    const formData = {
        name: document.getElementById('basicInfoName').value,
        description: document.getElementById('basicInfoDescription').value,
        phone: document.getElementById('basicInfoPhone').value,
        email: document.getElementById('basicInfoEmail').value,
        radius_checkin: parseInt(document.getElementById('basicInfoRadiusCheckin').value) || 0,
        radius_patrol: parseInt(document.getElementById('basicInfoRadiusPatrol').value) || 0,
        active_flag: parseInt(document.getElementById('basicInfoActiveFlag').value)
    };
    
    try {
        const response = await fetch(`/admin/clusters/${clusterId}/basic-info`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

// ==================== OFFICE CRUD ====================

function openOfficeModal(office = null) {
    const isEdit = office !== null;
    
    // Safe element access
    const modal = document.getElementById('officeModal');
    if (!modal) return;
    
    const titleEl = document.getElementById('officeModalTitle');
    if (titleEl) titleEl.textContent = isEdit ? 'Edit Kantor' : 'Tambah Kantor';
    
    const idEl = document.getElementById('office_id');
    if (idEl) idEl.value = isEdit ? office.id : '';
    
    const nameEl = document.getElementById('office_name');
    if (nameEl) nameEl.value = isEdit ? office.name : '';
    
    const latEl = document.getElementById('office_lat');
    if (latEl) latEl.value = isEdit ? office.lat : '';
    
    const lngEl = document.getElementById('office_lng');
    if (lngEl) lngEl.value = isEdit ? office.lng : '';
    
    const submitEl = document.getElementById('officeSubmitText');
    if (submitEl) submitEl.textContent = isEdit ? 'Update Kantor' : 'Tambah Kantor';
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Initialize map when Google Maps is ready
    const initMapWhenReady = () => {
        if (typeof google !== 'undefined' && google.maps) {
            setTimeout(() => {
                initOfficeMap(isEdit ? office : null);
            }, 300);
        } else {
            // Wait for Google Maps to load
            window.addEventListener('google-maps-ready', () => {
                setTimeout(() => {
                    initOfficeMap(isEdit ? office : null);
                }, 300);
            }, { once: true });
        }
    };
    initMapWhenReady();
}

function closeOfficeModal() {
    const modal = document.getElementById('officeModal');
    if (modal) modal.classList.add('hidden');
    officeMap = null;
    officeMarker = null;
}

function initOfficeMap(office) {
    const lat = office ? parseFloat(office.lat) : -6.2088;
    const lng = office ? parseFloat(office.lng) : 106.8456;
    
    // Set initial values
    const latEl = document.getElementById('office_lat');
    const lngEl = document.getElementById('office_lng');
    const latDisplay = document.getElementById('office_lat_display');
    const lngDisplay = document.getElementById('office_lng_display');
    
    if (latEl) latEl.value = lat.toFixed(6);
    if (lngEl) lngEl.value = lng.toFixed(6);
    if (latDisplay) latDisplay.textContent = lat.toFixed(6);
    if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);
    
    const mapOptions = {
        center: { lat, lng },
        zoom: 15,
        mapTypeControl: true,
        streetViewControl: true
    };
    
    officeMap = new google.maps.Map(document.getElementById('officeMap'), mapOptions);
    
    // Create marker
    officeMarker = new google.maps.Marker({
        position: { lat, lng },
        map: officeMap,
        draggable: true,
        icon: {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z',
            fillColor: '#F97316',
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: '#ffffff',
            scale: 2,
            anchor: new google.maps.Point(12, 24)
        }
    });
    
    // Update coords on drag
    google.maps.event.addListener(officeMarker, 'dragend', function(event) {
        const latEl = document.getElementById('office_lat');
        const lngEl = document.getElementById('office_lng');
        const latDisplay = document.getElementById('office_lat_display');
        const lngDisplay = document.getElementById('office_lng_display');
        
        if (latEl) latEl.value = event.latLng.lat().toFixed(6);
        if (lngEl) lngEl.value = event.latLng.lng().toFixed(6);
        if (latDisplay) latDisplay.textContent = event.latLng.lat().toFixed(6);
        if (lngDisplay) lngDisplay.textContent = event.latLng.lng().toFixed(6);
    });
    
    // Add marker on click
    google.maps.event.addListener(officeMap, 'click', function(event) {
        officeMarker.setPosition(event.latLng);
        
        const latEl = document.getElementById('office_lat');
        const lngEl = document.getElementById('office_lng');
        const latDisplay = document.getElementById('office_lat_display');
        const lngDisplay = document.getElementById('office_lng_display');
        
        if (latEl) latEl.value = event.latLng.lat().toFixed(6);
        if (lngEl) lngEl.value = event.latLng.lng().toFixed(6);
        if (latDisplay) latDisplay.textContent = event.latLng.lat().toFixed(6);
        if (lngDisplay) lngDisplay.textContent = event.latLng.lng().toFixed(6);
    });
}

async function saveOffice(clusterId) {
    const officeId = document.getElementById('office_id')?.value || '';
    const isEdit = officeId !== '';
    
    const formData = {
        name: document.getElementById('office_name')?.value,
        lat: parseFloat(document.getElementById('office_lat')?.value),
        lng: parseFloat(document.getElementById('office_lng')?.value)
    };
    
    const url = isEdit 
        ? `/admin/clusters/${clusterId}/offices/${officeId}`
        : `/admin/clusters/${clusterId}/offices`;
    const method = isEdit ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

async function deleteOffice(clusterId, officeId, officeName) {
    const result = await Swal.fire({
        title: 'Hapus Kantor?',
        html: `Apakah Anda yakin ingin menghapus kantor <strong>${officeName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/admin/clusters/${clusterId}/offices/${officeId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#ef4444'
            });
        }
    }
}

// ==================== PATROL CRUD ====================

function openPatrolModal(patrol = null) {
    const isEdit = patrol !== null;
    
    // Safe element access
    const modal = document.getElementById('patrolModal');
    if (!modal) return;
    
    const titleEl = document.getElementById('patrolModalTitle');
    if (titleEl) titleEl.textContent = isEdit ? 'Edit Rute Patroli' : 'Tambah Rute Patroli';
    
    const idEl = document.getElementById('patrol_id');
    if (idEl) idEl.value = isEdit ? patrol.id : '';
    
    const submitEl = document.getElementById('patrolSubmitText');
    if (submitEl) submitEl.textContent = isEdit ? 'Update Rute' : 'Simpan Rute';
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Initialize map when Google Maps is ready
    const initMapWhenReady = () => {
        if (typeof google !== 'undefined' && google.maps) {
            setTimeout(() => initPatrolMap(isEdit ? patrol : null), 300);
        } else {
            window.addEventListener('google-maps-ready', () => {
                setTimeout(() => initPatrolMap(isEdit ? patrol : null), 300);
            }, { once: true });
        }
    };
    
    initMapWhenReady();
}

function closePatrolModal() {
    const modal = document.getElementById('patrolModal');
    if (modal) modal.classList.add('hidden');
    patrolMap = null;
    patrolMarkers = [];
    patrolPolyline = null;
}

function initPatrolMap(patrol) {
    const mapOptions = {
        center: { lat: -6.2088, lng: 106.8456 },
        zoom: 15,
        mapTypeControl: true,
        streetViewControl: true
    };
    
    patrolMap = new google.maps.Map(document.getElementById('patrolMap'), mapOptions);
    patrolMarkers = [];
    
    // Load existing pinpoints if editing
    if (patrol && patrol.pinpoints) {
        const pinpoints = typeof patrol.pinpoints === 'string' ? JSON.parse(patrol.pinpoints) : patrol.pinpoints;
        pinpoints.forEach((point, index) => {
            addPatrolMarker({ lat: parseFloat(point.lat), lng: parseFloat(point.lng) }, index + 1);
        });
        updatePatrolPolyline();
        
        // Center map on first point
        if (pinpoints.length > 0) {
            patrolMap.setCenter({ lat: parseFloat(pinpoints[0].lat), lng: parseFloat(pinpoints[0].lng) });
        }
    }
    
    // Add marker on click
    google.maps.event.addListener(patrolMap, 'click', function(event) {
        addPatrolMarker(event.latLng, patrolMarkers.length + 1);
        updatePatrolPolyline();
    });
}

function addPatrolMarker(position, number) {
    const marker = new google.maps.Marker({
        position: position,
        map: patrolMap,
        label: {
            text: number.toString(),
            color: 'white',
            fontSize: '14px',
            fontWeight: 'bold'
        },
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: '#3B82F6',
            fillOpacity: 1,
            strokeWeight: 3,
            strokeColor: '#ffffff',
            scale: 15
        },
        draggable: true
    });
    
    google.maps.event.addListener(marker, 'dragend', function() {
        updatePatrolPolyline();
    });
    
    google.maps.event.addListener(marker, 'rightclick', function() {
        marker.setMap(null);
        const index = patrolMarkers.indexOf(marker);
        if (index > -1) {
            patrolMarkers.splice(index, 1);
        }
        // Re-number markers
        patrolMarkers.forEach((m, i) => {
            m.setLabel({
                text: (i + 1).toString(),
                color: 'white',
                fontSize: '14px',
                fontWeight: 'bold'
            });
        });
        updatePatrolPolyline();
    });
    
    patrolMarkers.push(marker);
    
    // Update point count display
    const countEl = document.getElementById('patrol_point_count');
    if (countEl) countEl.textContent = patrolMarkers.length;
}

function updatePatrolPolyline() {
    if (patrolPolyline) {
        patrolPolyline.setMap(null);
    }
    
    const path = patrolMarkers.map(marker => marker.getPosition());
    
    patrolPolyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#3B82F6',
        strokeOpacity: 1.0,
        strokeWeight: 3,
        map: patrolMap
    });
    
    // Update point count display
    const countEl = document.getElementById('patrol_point_count');
    if (countEl) countEl.textContent = patrolMarkers.length;
}

async function savePatrol(clusterId) {
    const patrolId = document.getElementById('patrol_id')?.value || '';
    const isEdit = patrolId !== '';
    
    if (patrolMarkers.length < 2) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Minimal 2 titik patroli diperlukan',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }
    
    const pinpoints = patrolMarkers.map(marker => ({
        lat: marker.getPosition().lat(),
        lng: marker.getPosition().lng()
    }));
    
    const formData = {
        pinpoints: pinpoints
    };
    
    const url = isEdit 
        ? `/admin/clusters/${clusterId}/patrols/${patrolId}`
        : `/admin/clusters/${clusterId}/patrols`;
    const method = isEdit ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

async function deletePatrol(clusterId, patrolId, patrolName) {
    const result = await Swal.fire({
        title: 'Hapus Rute Patroli?',
        html: `Apakah Anda yakin ingin menghapus ${patrolName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/admin/clusters/${clusterId}/patrols/${patrolId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#ef4444'
            });
        }
    }
}

// ==================== BANK ACCOUNT CRUD ====================

function openBankModal(bank = null) {
    const isEdit = bank !== null;
    
    // Safe element access
    const modal = document.getElementById('bankModal');
    if (!modal) return;
    
    const titleEl = document.getElementById('bankModalTitle');
    if (titleEl) titleEl.textContent = isEdit ? 'Edit Rekening Bank' : 'Tambah Rekening Bank';
    
    const idEl = document.getElementById('bank_id');
    if (idEl) idEl.value = isEdit ? bank.id : '';
    
    const typeEl = document.getElementById('bank_type');
    if (typeEl) typeEl.value = isEdit ? bank.bank_type : '';
    
    const codeEl = document.getElementById('bank_code_id');
    if (codeEl) codeEl.value = isEdit ? bank.bank_code_id : '';
    
    const holderEl = document.getElementById('bank_account_holder');
    if (holderEl) holderEl.value = isEdit ? bank.account_holder : '';
    
    const numberEl = document.getElementById('bank_account_number');
    if (numberEl) numberEl.value = isEdit ? bank.account_number : '';
    
    const submitEl = document.getElementById('bankSubmitText');
    if (submitEl) submitEl.textContent = isEdit ? 'Update Rekening' : 'Tambah Rekening';
    
    // Show modal
    modal.classList.remove('hidden');
}

function closeBankModal() {
    const modal = document.getElementById('bankModal');
    if (modal) modal.classList.add('hidden');
}

async function saveBank(clusterId) {
    const bankId = document.getElementById('bank_id')?.value || '';
    const isEdit = bankId !== '';
    
    const formData = {
        bank_type: document.getElementById('bank_type')?.value,
        bank_code_id: parseInt(document.getElementById('bank_code_id')?.value) || 1,
        account_holder: document.getElementById('bank_account_holder')?.value,
        account_number: document.getElementById('bank_account_number')?.value
    };
    
    const url = isEdit 
        ? `/admin/clusters/${clusterId}/banks/${bankId}`
        : `/admin/clusters/${clusterId}/banks`;
    const method = isEdit ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

async function deleteBank(clusterId, bankId, bankName) {
    const result = await Swal.fire({
        title: 'Hapus Rekening?',
        html: `Apakah Anda yakin ingin menghapus rekening <strong>${bankName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/admin/clusters/${clusterId}/banks/${bankId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#ef4444'
            });
        }
    }
}

// ==================== EMPLOYEE CRUD ====================

function openEmployeeModal(employee = null) {
    const isEdit = employee !== null;
    
    // Safe element access
    const modal = document.getElementById('employeeModal');
    if (!modal) return;
    
    const titleEl = document.getElementById('employeeModalTitle');
    if (titleEl) titleEl.textContent = isEdit ? 'Edit Karyawan' : 'Tambah Karyawan';
    
    const idEl = document.getElementById('employee_id');
    if (idEl) idEl.value = isEdit ? employee.id : '';
    
    const nameEl = document.getElementById('employee_name');
    if (nameEl) nameEl.value = isEdit ? employee.employee.name : '';
    
    const usernameEl = document.getElementById('employee_username');
    if (usernameEl) usernameEl.value = isEdit ? employee.employee.username : '';
    
    const emailEl = document.getElementById('employee_email');
    if (emailEl) emailEl.value = isEdit ? (employee.employee.email || '') : '';
    
    const phoneEl = document.getElementById('employee_phone');
    if (phoneEl) phoneEl.value = isEdit ? (employee.employee.phone || '') : '';
    
    const roleEl = document.getElementById('employee_role');
    if (roleEl) roleEl.value = isEdit ? employee.employee.role : '';
    
    const passwordEl = document.getElementById('employee_password');
    if (passwordEl) {
        passwordEl.value = '';
        passwordEl.required = !isEdit;
    }
    
    const submitEl = document.getElementById('employeeSubmitText');
    if (submitEl) submitEl.textContent = isEdit ? 'Update Karyawan' : 'Tambah Karyawan';
    
    // Show modal
    modal.classList.remove('hidden');
}

function closeEmployeeModal() {
    const modal = document.getElementById('employeeModal');
    if (modal) modal.classList.add('hidden');
}

async function saveEmployee(clusterId) {
    const employeeId = document.getElementById('employee_id')?.value || '';
    const isEdit = employeeId !== '';
    
    const formData = {
        name: document.getElementById('employee_name')?.value,
        username: document.getElementById('employee_username')?.value,
        email: document.getElementById('employee_email')?.value || null,
        phone: document.getElementById('employee_phone')?.value || null,
        role: document.getElementById('employee_role')?.value,
        password: document.getElementById('employee_password')?.value || null
    };
    
    const url = isEdit 
        ? `/admin/clusters/${clusterId}/employees/${employeeId}`
        : `/admin/clusters/${clusterId}/employees`;
    const method = isEdit ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

async function deleteEmployee(clusterId, employeeId, employeeName) {
    const result = await Swal.fire({
        title: 'Hapus Karyawan?',
        html: `Apakah Anda yakin ingin menghapus karyawan <strong>${employeeName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/admin/clusters/${clusterId}/employees/${employeeId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#ef4444'
            });
        }
    }
}

// ==================== SECURITY CRUD ====================

function openSecurityModal(security = null) {
    const isEdit = security !== null;
    
    // Safe element access
    const modal = document.getElementById('securityModal');
    if (!modal) return;
    
    const titleEl = document.getElementById('securityModalTitle');
    if (titleEl) titleEl.textContent = isEdit ? 'Edit Security' : 'Tambah Security';
    
    const idEl = document.getElementById('security_id');
    if (idEl) idEl.value = isEdit ? security.id : '';
    
    const nameEl = document.getElementById('security_name');
    if (nameEl) nameEl.value = isEdit ? security.security.name : '';
    
    const usernameEl = document.getElementById('security_username');
    if (usernameEl) usernameEl.value = isEdit ? security.security.username : '';
    
    const emailEl = document.getElementById('security_email');
    if (emailEl) emailEl.value = isEdit ? (security.security.email || '') : '';
    
    const phoneEl = document.getElementById('security_phone');
    if (phoneEl) phoneEl.value = isEdit ? (security.security.phone || '') : '';
    
    const passwordEl = document.getElementById('security_password');
    if (passwordEl) {
        passwordEl.value = '';
        passwordEl.required = !isEdit;
    }
    
    const submitEl = document.getElementById('securitySubmitText');
    if (submitEl) submitEl.textContent = isEdit ? 'Update Security' : 'Tambah Security';
    
    // Show modal
    modal.classList.remove('hidden');
}

function closeSecurityModal() {
    const modal = document.getElementById('securityModal');
    if (modal) modal.classList.add('hidden');
}

async function saveSecurity(clusterId) {
    const securityId = document.getElementById('security_id')?.value || '';
    const isEdit = securityId !== '';
    
    const formData = {
        name: document.getElementById('security_name')?.value,
        username: document.getElementById('security_username')?.value,
        email: document.getElementById('security_email')?.value || null,
        phone: document.getElementById('security_phone')?.value || null,
        password: document.getElementById('security_password')?.value || null
    };
    
    const url = isEdit 
        ? `/admin/clusters/${clusterId}/securities/${securityId}`
        : `/admin/clusters/${clusterId}/securities`;
    const method = isEdit ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    }
}

async function deleteSecurity(clusterId, securityId, securityName) {
    const result = await Swal.fire({
        title: 'Hapus Security?',
        html: `Apakah Anda yakin ingin menghapus security <strong>${securityName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`/admin/clusters/${clusterId}/securities/${securityId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: data.message,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#ef4444'
            });
        }
    }
}

// ==================== FORM EVENT HANDLERS ====================

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Basic Info Form
    const basicInfoForm = document.getElementById('basicInfoForm');
    if (basicInfoForm) {
        basicInfoForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('basic_cluster_id').value;
            await saveBasicInfo(clusterId);
        });
    }
    
    // Office Form
    const officeForm = document.getElementById('officeForm');
    if (officeForm) {
        officeForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('office_cluster_id').value;
            await saveOffice(clusterId);
        });
    }
    
    // Patrol Form
    const patrolForm = document.getElementById('patrolForm');
    if (patrolForm) {
        patrolForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('patrol_cluster_id').value;
            await savePatrol(clusterId);
        });
    }
    
    // Bank Form
    const bankForm = document.getElementById('bankForm');
    if (bankForm) {
        bankForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('bank_cluster_id').value;
            await saveBank(clusterId);
        });
    }
    
    // Employee Form
    const employeeForm = document.getElementById('employeeForm');
    if (employeeForm) {
        employeeForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('employee_cluster_id').value;
            await saveEmployee(clusterId);
        });
    }
    
    // Security Form
    const securityForm = document.getElementById('securityForm');
    if (securityForm) {
        securityForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clusterId = document.getElementById('security_cluster_id').value;
            await saveSecurity(clusterId);
        });
    }
});
