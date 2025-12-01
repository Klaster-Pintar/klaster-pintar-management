@extends('layouts.app')

@section('title', 'Wizard: Buat Cluster Baru - iManagement')

@push('styles')
    <style>
        .step-indicator {
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 1.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }

        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .step-item.pending .step-circle {
            background: white;
            border: 2px solid #e5e7eb;
            color: #9ca3af;
        }

        /* Google Maps Styles */
        .map-container {
            height: 500px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .pac-container {
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
            font-family: 'Poppins', sans-serif;
        }

        .pac-item {
            padding: 10px;
            cursor: pointer;
        }

        .pac-item:hover {
            background-color: #f3f4f6;
        }

        .marker-info-window {
            font-family: 'Poppins', sans-serif;
        }

        .marker-info-window h4 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .marker-info-window button {
            margin-top: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="flex min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50" x-data="clusterWizard()"
        x-init="init()">

        <!-- Main Wrapper - Full Width -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Simple Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.clusters.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fa-solid fa-arrow-left text-gray-600"></i>
                        </a>
                        <div>
                            <h1 class="text-lg lg:text-xl font-bold text-gray-800">Wizard Pendaftaran Cluster</h1>
                            <p class="text-xs text-gray-600">Ikuti langkah-langkah untuk mendaftarkan cluster baru</p>
                        </div>
                    </div>
                    <div class="hidden lg:flex items-center gap-2 text-sm text-gray-600">
                        <i class="fa-solid fa-building text-green-600"></i>
                        <span>iHome Management</span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <div class="max-w-5xl mx-auto">

                    <!-- Progress Indicator -->
                    <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 mb-6">
                        <!-- Navigation Buttons - Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <button type="button" @click="previousStep" x-show="currentStep > 1"
                                class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                            </button>

                            <div class="flex-1 text-center">
                                <span class="text-sm font-semibold text-gray-600">
                                    Step <span x-text="currentStep"></span> dari 7
                                </span>
                            </div>

                            <button type="button" @click="nextStep" x-show="currentStep < 7"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                Selanjutnya <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>

                            <button type="submit" x-show="currentStep === 7" form="wizard-form"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition font-semibold">
                                <i class="fa-solid fa-check-circle mr-1"></i> Selesai & Simpan
                            </button>
                        </div>

                        <div class="step-indicator">
                            <div class="grid grid-cols-4 lg:grid-cols-7 gap-2 lg:gap-4 relative z-10">
                                <!-- Step 1 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 1 ? 'active' : currentStep > 1 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 1 ? 'fa-check' : 'fa-info-circle'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Info Dasar</span>
                                </div>

                                <!-- Step 2 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 2 ? 'active' : currentStep > 2 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 2 ? 'fa-check' : 'fa-building'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Kantor</span>
                                </div>

                                <!-- Step 3 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 3 ? 'active' : currentStep > 3 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 3 ? 'fa-check' : 'fa-route'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Patroli</span>
                                </div>

                                <!-- Step 4 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 4 ? 'active' : currentStep > 4 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 4 ? 'fa-check' : 'fa-users'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Karyawan</span>
                                </div>

                                <!-- Step 5 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 5 ? 'active' : currentStep > 5 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 5 ? 'fa-check' : 'fa-shield-halved'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Security</span>
                                </div>

                                <!-- Step 6 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 6 ? 'active' : currentStep > 6 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid"
                                            :class="currentStep > 6 ? 'fa-check' : 'fa-building-columns'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Bank</span>
                                </div>

                                <!-- Step 7 -->
                                <div class="step-item flex flex-col items-center"
                                    :class="currentStep === 7 ? 'active' : currentStep > 7 ? 'completed' : 'pending'">
                                    <div
                                        class="step-circle w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm mb-2 transition-all">
                                        <i class="fa-solid" :class="currentStep > 7 ? 'fa-check' : 'fa-users'"></i>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700">Residents</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <form id="wizard-form" @submit.prevent="submitForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Basic Information -->
                        <div x-show="currentStep === 1" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step1-basic')
                        </div>

                        <!-- Step 2: Offices -->
                        <div x-show="currentStep === 2" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step2-offices')
                        </div>

                        <!-- Step 3: Patrol Points -->
                        <div x-show="currentStep === 3" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step3-patrols')
                        </div>

                        <!-- Step 4: Employees -->
                        <div x-show="currentStep === 4" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step4-employees')
                        </div>

                        <!-- Step 5: Securities -->
                        <div x-show="currentStep === 5" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step5-securities')
                        </div>

                        <!-- Step 6: Bank Accounts -->
                        <div x-show="currentStep === 6" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step6-banks')
                        </div>

                        <!-- Step 7: Residents -->
                        <div x-show="currentStep === 7" x-transition class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                            @include('admin.clusters.wizard.steps.step7-residents')
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Google Maps API -->
    <script>
        // Global variable for Google Maps API Key
        const GOOGLE_MAPS_API_KEY = '{{ env('GOOGLE_MAPS_API_KEY', '') }}';
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}&libraries=places&callback=initGoogleMaps">
        </script>

    <script>
        // Global Google Maps initialization callback
        let googleMapsReady = false;
        window.initGoogleMaps = function () {
            googleMapsReady = true;
            console.log('Google Maps API loaded successfully');
            window.dispatchEvent(new Event('google-maps-ready'));
        };

        function clusterWizard() {
            return {
                currentStep: 1,
                formData: {
                    // Step 1
                    name: '',
                    description: '',
                    phone: '',
                    email: '',
                    logo: null,
                    picture: null,
                    radius_checkin: 5,
                    radius_patrol: 5,

                    // Step 2
                    offices: [],

                    // Step 3
                    patrols: [
                        { day_type_id: 1, pinpoints: [] }
                    ],

                    // Step 4
                    employees: [],

                    // Step 5
                    securities: [],

                    // Step 6
                    bank_accounts: [
                        { account_number: '', account_holder: '', bank_type: 'BCA', bank_code_id: 1 }
                    ],

                    // Step 7
                    residents: []
                },

                // Google Maps - Step 2 (Offices)
                officeMap: null,
                officeMarkers: [],
                officeSearchBox: null,

                // Google Maps - Step 3 (Patrols)
                patrolMap: null,
                patrolMarkers: [],
                currentPatrolIndex: 0,

                // CSV Upload - Step 7 (Residents)
                csvFileName: '',
                csvPreviewData: [],
                csvValidCount: 0,
                csvErrorCount: 0,
                csvValidationErrors: [],

                // CSV Upload - Step 4 (Employees)
                employeeInputMode: 'manual',
                employeeCsvFileName: '',
                employeeCsvPreviewData: [],
                employeeCsvValidCount: 0,
                employeeCsvErrorCount: 0,
                employeeCsvValidationErrors: [],

                // CSV Upload - Step 5 (Securities)
                securityInputMode: 'manual',
                securityCsvFileName: '',
                securityCsvPreviewData: [],
                securityCsvValidCount: 0,
                securityCsvErrorCount: 0,
                securityCsvValidationErrors: [],

                init() {
                    console.log('Wizard initialized');

                    // Wait for Google Maps to be ready
                    if (googleMapsReady) {
                        this.initializeMaps();
                    } else {
                        window.addEventListener('google-maps-ready', () => {
                            this.initializeMaps();
                        });
                    }

                    // Watch for step changes to initialize maps
                    this.$watch('currentStep', (value) => {
                        if (value === 2 && !this.officeMap && googleMapsReady) {
                            setTimeout(() => this.initOfficeMap(), 100);
                        }
                        if (value === 3 && !this.patrolMap && googleMapsReady) {
                            setTimeout(() => this.initPatrolMap(), 100);
                        }
                    });
                },

                initializeMaps() {
                    console.log('Initializing maps...');
                },

                // ============================================
                // STEP 2: OFFICE MAP FUNCTIONS
                // ============================================
                initOfficeMap() {
                    const mapElement = document.getElementById('officeMap');
                    const searchInput = document.getElementById('officeSearchInput');

                    if (!mapElement || !searchInput) {
                        console.error('Office map elements not found');
                        return;
                    }

                    // Default center: Jakarta
                    const defaultCenter = { lat: -6.200000, lng: 106.816666 };

                    this.officeMap = new google.maps.Map(mapElement, {
                        center: defaultCenter,
                        zoom: 13,
                        mapTypeControl: true,
                        streetViewControl: false,
                        fullscreenControl: true,
                        styles: [
                            {
                                featureType: 'poi',
                                elementType: 'labels',
                                stylers: [{ visibility: 'on' }]
                            }
                        ]
                    });

                    // Initialize search box
                    this.officeSearchBox = new google.maps.places.SearchBox(searchInput);
                    this.officeMap.controls[google.maps.ControlPosition.TOP_LEFT].push(searchInput);

                    // Bias search results to map viewport
                    this.officeMap.addListener('bounds_changed', () => {
                        this.officeSearchBox.setBounds(this.officeMap.getBounds());
                    });

                    // Listen for search box selection
                    this.officeSearchBox.addListener('places_changed', () => {
                        const places = this.officeSearchBox.getPlaces();
                        if (places.length === 0) return;

                        const place = places[0];
                        if (!place.geometry || !place.geometry.location) return;

                        // Add marker at searched location
                        this.addOfficeMarker(
                            place.geometry.location.lat(),
                            place.geometry.location.lng(),
                            place.name || 'Office Location'
                        );

                        // Pan to location
                        this.officeMap.setCenter(place.geometry.location);
                        this.officeMap.setZoom(17);
                    });

                    // Click to add marker
                    this.officeMap.addListener('click', (e) => {
                        this.addOfficeMarker(
                            e.latLng.lat(),
                            e.latLng.lng(),
                            `Office ${this.formData.offices.length + 1}`
                        );
                    });

                    console.log('Office map initialized');
                },

                addOfficeMarker(lat, lng, name = '') {
                    const marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: this.officeMap,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        icon: {
                            url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                            scaledSize: new google.maps.Size(40, 40)
                        }
                    });

                    const officeIndex = this.formData.offices.length;

                    // Add to offices array
                    this.formData.offices.push({
                        name: name,
                        type_id: 1,
                        latitude: lat.toFixed(7),
                        longitude: lng.toFixed(7)
                    });

                    // Store marker reference
                    this.officeMarkers.push({
                        marker: marker,
                        index: officeIndex
                    });

                    // Info window
                    const infoWindow = new google.maps.InfoWindow({
                        content: this.getOfficeInfoWindowContent(officeIndex, lat, lng)
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(this.officeMap, marker);
                    });

                    // Update coordinates when dragged
                    marker.addListener('dragend', (e) => {
                        const newLat = e.latLng.lat();
                        const newLng = e.latLng.lng();
                        this.formData.offices[officeIndex].latitude = newLat.toFixed(7);
                        this.formData.offices[officeIndex].longitude = newLng.toFixed(7);
                        infoWindow.setContent(this.getOfficeInfoWindowContent(officeIndex, newLat, newLng));
                    });
                },

                getOfficeInfoWindowContent(index, lat, lng) {
                    return `
                                            <div class="marker-info-window" style="min-width: 200px;">
                                                <h4 class="text-sm font-bold text-gray-800">Office ${index + 1}</h4>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Lat: ${lat.toFixed(7)}<br>
                                                    Lng: ${lng.toFixed(7)}
                                                </p>
                                                <button onclick="window.clusterWizardInstance.deleteOfficeMarker(${index})" 
                                                    class="mt-2 px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </div>
                                        `;
                },

                deleteOfficeMarker(index) {
                    // Find marker with this index
                    const markerObj = this.officeMarkers.find(m => m.index === index);
                    if (markerObj) {
                        markerObj.marker.setMap(null);
                        this.officeMarkers = this.officeMarkers.filter(m => m.index !== index);
                    }

                    // Remove from offices array
                    this.formData.offices.splice(index, 1);

                    // Update remaining markers' indices
                    this.officeMarkers.forEach((m, i) => {
                        if (m.index > index) {
                            m.index--;
                        }
                    });
                },

                clearAllOfficeMarkers() {
                    if (confirm('Hapus semua marker kantor?')) {
                        this.officeMarkers.forEach(m => m.marker.setMap(null));
                        this.officeMarkers = [];
                        this.formData.offices = [];
                    }
                },

                // ============================================
                // STEP 3: PATROL MAP FUNCTIONS
                // ============================================
                initPatrolMap() {
                    const mapElement = document.getElementById('patrolMap');
                    if (!mapElement) {
                        console.error('Patrol map element not found');
                        return;
                    }

                    const defaultCenter = { lat: -6.200000, lng: 106.816666 };

                    this.patrolMap = new google.maps.Map(mapElement, {
                        center: defaultCenter,
                        zoom: 13,
                        mapTypeControl: true,
                        streetViewControl: false,
                        fullscreenControl: true
                    });

                    // Click to add patrol marker
                    this.patrolMap.addListener('click', (e) => {
                        this.addPatrolMarker(e.latLng.lat(), e.latLng.lng());
                    });

                    console.log('Patrol map initialized');
                },

                addPatrolMarker(lat, lng) {
                    const markerIndex = this.patrolMarkers.length;
                    const markerLabel = String(markerIndex + 1);

                    const marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: this.patrolMap,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        label: {
                            text: markerLabel,
                            color: 'white',
                            fontWeight: 'bold'
                        },
                        icon: {
                            url: 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png',
                            scaledSize: new google.maps.Size(40, 40)
                        }
                    });

                    // Store marker
                    this.patrolMarkers.push({
                        marker: marker,
                        lat: lat,
                        lng: lng
                    });

                    // Update formData
                    this.updatePatrolPinpoints();

                    // Info window
                    const infoWindow = new google.maps.InfoWindow({
                        content: this.getPatrolInfoWindowContent(markerIndex, lat, lng)
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(this.patrolMap, marker);
                    });

                    // Update on drag
                    marker.addListener('dragend', (e) => {
                        const newLat = e.latLng.lat();
                        const newLng = e.latLng.lng();
                        this.patrolMarkers[markerIndex].lat = newLat;
                        this.patrolMarkers[markerIndex].lng = newLng;
                        this.updatePatrolPinpoints();
                        infoWindow.setContent(this.getPatrolInfoWindowContent(markerIndex, newLat, newLng));
                    });
                },

                getPatrolInfoWindowContent(index, lat, lng) {
                    return `
                                            <div class="marker-info-window" style="min-width: 200px;">
                                                <h4 class="text-sm font-bold text-gray-800">Patrol Point ${index + 1}</h4>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Lat: ${lat.toFixed(7)}<br>
                                                    Lng: ${lng.toFixed(7)}
                                                </p>
                                                <button onclick="window.clusterWizardInstance.deletePatrolMarker(${index})" 
                                                    class="mt-2 px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </div>
                                        `;
                },

                deletePatrolMarker(index) {
                    // Remove marker from map
                    if (this.patrolMarkers[index]) {
                        this.patrolMarkers[index].marker.setMap(null);
                        this.patrolMarkers.splice(index, 1);
                    }

                    // Re-label remaining markers
                    this.patrolMarkers.forEach((m, i) => {
                        m.marker.setLabel({
                            text: String(i + 1),
                            color: 'white',
                            fontWeight: 'bold'
                        });
                    });

                    this.updatePatrolPinpoints();
                },

                clearAllPatrolMarkers() {
                    if (confirm('Hapus semua marker patroli?')) {
                        this.patrolMarkers.forEach(m => m.marker.setMap(null));
                        this.patrolMarkers = [];
                        this.updatePatrolPinpoints();
                    }
                },

                updatePatrolPinpoints() {
                    const pinpoints = this.patrolMarkers.map(m => ({
                        lat: m.lat.toFixed(7),
                        lng: m.lng.toFixed(7)
                    }));

                    if (this.formData.patrols[this.currentPatrolIndex]) {
                        this.formData.patrols[this.currentPatrolIndex].pinpoints = pinpoints;
                    }
                },

                // ============================================
                // GENERAL FUNCTIONS
                // ============================================
                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.currentStep++;
                    }
                },

                previousStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                validateCurrentStep() {
                    // Basic validation - you can enhance this
                    return true;
                },

                addOffice() {
                    this.formData.offices.push({ name: '', type_id: 1, latitude: '', longitude: '' });
                },

                removeOffice(index) {
                    this.formData.offices.splice(index, 1);
                },

                addEmployee() {
                    this.formData.employees.push({ name: '', username: '', email: '', phone: '', role: 'ADMIN', password: '' });
                },

                removeEmployee(index) {
                    this.formData.employees.splice(index, 1);
                },

                addSecurity() {
                    this.formData.securities.push({ name: '', username: '', email: '', phone: '', password: '' });
                },

                removeSecurity(index) {
                    this.formData.securities.splice(index, 1);
                },

                addBankAccount() {
                    this.formData.bank_accounts.push({ account_number: '', account_holder: '', bank_type: 'BCA', bank_code_id: 1 });
                },

                removeBankAccount(index) {
                    this.formData.bank_accounts.splice(index, 1);
                },

                // ============================================
                // STEP 7: CSV RESIDENTS FUNCTIONS
                // ============================================
                downloadCsvTemplate() {
                    const csvContent = [
                        ['Nama', 'No HP', 'Blok', 'Nomor', 'Status Rumah', 'Status User', 'Nominal IPL'],
                        ['John Doe', '081234567890', 'A', '01', 'HUNI', 'Active', '500000'],
                        ['Jane Smith', '081234567891', 'A', '02', 'KOSONG', 'Active', '500000'],
                        ['Bob Johnson', '081234567892', 'B', '01', 'HUNI', 'Inactive', '500000']
                    ].map(row => row.join(',')).join('\n');

                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'template_residents.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                handleCsvUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File terlalu besar! Maksimal 2MB');
                        event.target.value = '';
                        return;
                    }

                    // Validate file type
                    if (!file.name.endsWith('.csv')) {
                        alert('Format file harus CSV!');
                        event.target.value = '';
                        return;
                    }

                    this.csvFileName = file.name;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            this.parseCsvData(e.target.result);
                        } catch (error) {
                            alert('Error parsing CSV: ' + error.message);
                            this.clearCsvData();
                        }
                    };
                    reader.readAsText(file);
                },

                parseCsvData(csvText) {
                    // Remove BOM if present
                    csvText = csvText.replace(/^\ufeff/, '');

                    // Split by newline and filter empty lines
                    const lines = csvText.split(/\r?\n/).filter(line => line.trim());

                    if (lines.length < 2) {
                        throw new Error('File CSV kosong atau tidak valid');
                    }

                    // Skip header row
                    const dataLines = lines.slice(1); this.csvPreviewData = dataLines.map((line, index) => {
                        const columns = this.parseCsvLine(line);

                        if (columns.length < 7) {
                            return {
                                name: columns[0] || '',
                                phone: columns[1] || '',
                                house_block: columns[2] || '',
                                house_number: columns[3] || '',
                                house_status: columns[4] || '',
                                user_status: columns[5] || '',
                                nominal_ipl: columns[6] || '0',
                                isValid: false,
                                error: 'Kolom tidak lengkap (minimal 7 kolom)'
                            };
                        }

                        const row = {
                            name: columns[0].trim(),
                            phone: columns[1].trim(),
                            house_block: columns[2].trim(),
                            house_number: columns[3].trim(),
                            house_status: columns[4].trim(),
                            user_status: columns[5].trim(),
                            nominal_ipl: columns[6].trim(),
                            isValid: true,
                            error: ''
                        };

                        return row;
                    });

                    this.validateCsvData();
                },

                parseCsvLine(line) {
                    const result = [];
                    let current = '';
                    let inQuotes = false;

                    for (let i = 0; i < line.length; i++) {
                        const char = line[i];

                        if (char === '\"') {
                            inQuotes = !inQuotes;
                        } else if (char === ',' && !inQuotes) {
                            result.push(current);
                            current = '';
                        } else {
                            current += char;
                        }
                    }
                    result.push(current);

                    // Remove quotes and trim whitespace
                    return result.map(col => col.replace(/^\"|\"$/g, '').trim());
                }, validateCsvData() {
                    this.csvValidationErrors = [];
                    const phoneNumbers = new Set();
                    const houseAddresses = new Set();

                    this.csvPreviewData.forEach((row, index) => {
                        const errors = [];

                        // Validate required fields
                        if (!row.name) errors.push(`Baris ${index + 2}: Nama wajib diisi`);
                        if (!row.phone) {
                            errors.push(`Baris ${index + 2}: No HP wajib diisi`);
                        } else {
                            // Validate phone format (should be numbers)
                            if (!/^\d+$/.test(row.phone)) {
                                errors.push(`Baris ${index + 2}: No HP harus berisi angka saja`);
                            }
                            // Check duplicate phone in CSV
                            if (phoneNumbers.has(row.phone)) {
                                errors.push(`Baris ${index + 2}: No HP ${row.phone} duplikat dalam file CSV`);
                            }
                            phoneNumbers.add(row.phone);
                        }

                        if (!row.house_block) errors.push(`Baris ${index + 2}: Blok wajib diisi`);
                        if (!row.house_block) errors.push(`Baris ${index + 2}: Blok wajib diisi`);
                        if (!row.house_number) errors.push(`Baris ${index + 2}: Nomor wajib diisi`);

                        // Validate house status (accept both old and new format)
                        if (row.house_status && !['HUNI', 'KOSONG', 'Milik', 'Kontrak'].includes(row.house_status)) {
                            errors.push(`Baris ${index + 2}: Status Rumah harus \"HUNI\" atau \"KOSONG\"`);
                        }

                        // Validate user status
                        if (row.user_status && !['Active', 'Inactive'].includes(row.user_status)) {
                            errors.push(`Baris ${index + 2}: Status User harus \"Active\" atau \"Inactive\"`);
                        }

                        // Validate nominal IPL (should be number)
                        const nominalClean = row.nominal_ipl.toString().replace(/[.,\s]/g, '');
                        if (row.nominal_ipl && nominalClean && !/^\d+$/.test(nominalClean)) {
                            errors.push(`Baris ${index + 2}: Nominal IPL harus berupa angka`);
                        }
                        // Check duplicate house address
                        const houseKey = `${row.house_block}-${row.house_number}`;
                        if (houseAddresses.has(houseKey)) {
                            errors.push(`Baris ${index + 2}: Alamat ${houseKey} duplikat dalam file CSV`);
                        }
                        houseAddresses.add(houseKey);

                        if (errors.length > 0) {
                            row.isValid = false;
                            row.error = errors.join('; ');
                            this.csvValidationErrors.push(...errors);
                        } else {
                            row.isValid = true;
                            row.error = '';
                        }
                    });

                    this.csvValidCount = this.csvPreviewData.filter(r => r.isValid).length;
                    this.csvErrorCount = this.csvPreviewData.filter(r => !r.isValid).length;
                },

                clearCsvData() {
                    this.csvFileName = '';
                    this.csvPreviewData = [];
                    this.csvValidCount = 0;
                    this.csvErrorCount = 0;
                    this.csvValidationErrors = [];
                    document.getElementById('csvFileInput').value = '';
                },

                formatRupiah(amount) {
                    if (!amount) return 'Rp 0';
                    const number = parseInt(amount.toString().replace(/[^0-9]/g, ''));
                    return 'Rp ' + number.toLocaleString('id-ID');
                },

                // ============================================
                // EMPLOYEE CSV FUNCTIONS
                // ============================================
                downloadEmployeeCsvTemplate() {
                    const csvContent = [
                        ['Nama', 'Username', 'Email', 'Phone', 'Role', 'Password'],
                        ['Ahmad Sulaiman', 'ahmad.rt01', 'ahmad@example.com', '081234567890', 'RT', 'password123'],
                        ['Budi Santoso', 'budi.rw02', 'budi@example.com', '081234567891', 'RW', 'password123'],
                        ['Citra Admin', 'citra.admin', 'citra@example.com', '081234567892', 'ADMIN', 'password123']
                    ].map(row => row.join(',')).join('\n');

                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'template_employees.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                handleEmployeeCsvUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (file.size > 2 * 1024 * 1024) {
                        alert('File terlalu besar. Maksimal 2MB');
                        return;
                    }

                    if (!file.name.endsWith('.csv')) {
                        alert('Hanya file CSV yang diizinkan');
                        return;
                    }

                    this.employeeCsvFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            this.parseEmployeeCsvData(e.target.result);
                        } catch (error) {
                            alert('Error parsing CSV: ' + error.message);
                            this.clearEmployeeCsvData();
                        }
                    };
                    reader.readAsText(file);
                },

                parseEmployeeCsvData(csvText) {
                    csvText = csvText.replace(/^\ufeff/, '');
                    const lines = csvText.split(/\r?\n/).filter(line => line.trim());

                    if (lines.length < 2) {
                        throw new Error('File CSV kosong atau tidak valid');
                    }

                    const dataLines = lines.slice(1);
                    this.employeeCsvPreviewData = dataLines.map((line, index) => {
                        const columns = this.parseCsvLine(line);

                        if (columns.length < 6) {
                            return {
                                name: columns[0] || '',
                                username: columns[1] || '',
                                email: columns[2] || '',
                                phone: columns[3] || '',
                                role: columns[4] || '',
                                password: columns[5] || '',
                                isValid: false,
                                error: 'Kolom tidak lengkap (minimal 6 kolom)'
                            };
                        }

                        return {
                            name: columns[0].trim(),
                            username: columns[1].trim(),
                            email: columns[2].trim(),
                            phone: columns[3].trim(),
                            role: columns[4].trim(),
                            password: columns[5].trim(),
                            isValid: true,
                            error: ''
                        };
                    });

                    this.validateEmployeeCsvData();
                },

                validateEmployeeCsvData() {
                    this.employeeCsvValidationErrors = [];
                    const usernames = new Set();

                    this.employeeCsvPreviewData.forEach((row, index) => {
                        const errors = [];

                        if (!row.name) errors.push(`Baris ${index + 2}: Nama wajib diisi`);
                        if (!row.username) {
                            errors.push(`Baris ${index + 2}: Username wajib diisi`);
                        } else {
                            if (usernames.has(row.username)) {
                                errors.push(`Baris ${index + 2}: Username ${row.username} duplikat dalam file CSV`);
                            }
                            usernames.add(row.username);
                        }

                        if (!row.role) {
                            errors.push(`Baris ${index + 2}: Role wajib diisi`);
                        } else if (!['RT', 'RW', 'ADMIN'].includes(row.role)) {
                            errors.push(`Baris ${index + 2}: Role harus RT, RW, atau ADMIN`);
                        }

                        if (!row.password) {
                            errors.push(`Baris ${index + 2}: Password wajib diisi`);
                        } else if (row.password.length < 8) {
                            errors.push(`Baris ${index + 2}: Password minimal 8 karakter`);
                        }

                        if (row.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(row.email)) {
                            errors.push(`Baris ${index + 2}: Format email tidak valid`);
                        }

                        if (row.phone && !/^\d+$/.test(row.phone)) {
                            errors.push(`Baris ${index + 2}: No HP harus berisi angka saja`);
                        }

                        if (errors.length > 0) {
                            row.isValid = false;
                            row.error = errors.join('; ');
                            this.employeeCsvValidationErrors.push(...errors);
                        } else {
                            row.isValid = true;
                            row.error = '';
                        }
                    });

                    this.employeeCsvValidCount = this.employeeCsvPreviewData.filter(r => r.isValid).length;
                    this.employeeCsvErrorCount = this.employeeCsvPreviewData.filter(r => !r.isValid).length;
                },

                clearEmployeeCsvData() {
                    this.employeeCsvFileName = '';
                    this.employeeCsvPreviewData = [];
                    this.employeeCsvValidCount = 0;
                    this.employeeCsvErrorCount = 0;
                    this.employeeCsvValidationErrors = [];
                    document.getElementById('employeeCsvFileInput').value = '';
                },

                // ============================================
                // SECURITY CSV FUNCTIONS
                // ============================================
                downloadSecurityCsvTemplate() {
                    const csvContent = [
                        ['Nama', 'Username', 'Email', 'Phone', 'Password'],
                        ['Eko Prasetyo', 'eko.security01', 'eko@example.com', '081234567893', 'password123'],
                        ['Fajar Hidayat', 'fajar.security02', 'fajar@example.com', '081234567894', 'password123'],
                        ['Gunawan', 'gunawan.security03', 'gunawan@example.com', '081234567895', 'password123']
                    ].map(row => row.join(',')).join('\n');

                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'template_securities.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                handleSecurityCsvUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (file.size > 2 * 1024 * 1024) {
                        alert('File terlalu besar. Maksimal 2MB');
                        return;
                    }

                    if (!file.name.endsWith('.csv')) {
                        alert('Hanya file CSV yang diizinkan');
                        return;
                    }

                    this.securityCsvFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            this.parseSecurityCsvData(e.target.result);
                        } catch (error) {
                            alert('Error parsing CSV: ' + error.message);
                            this.clearSecurityCsvData();
                        }
                    };
                    reader.readAsText(file);
                },

                parseSecurityCsvData(csvText) {
                    csvText = csvText.replace(/^\ufeff/, '');
                    const lines = csvText.split(/\r?\n/).filter(line => line.trim());

                    if (lines.length < 2) {
                        throw new Error('File CSV kosong atau tidak valid');
                    }

                    const dataLines = lines.slice(1);
                    this.securityCsvPreviewData = dataLines.map((line, index) => {
                        const columns = this.parseCsvLine(line);

                        if (columns.length < 5) {
                            return {
                                name: columns[0] || '',
                                username: columns[1] || '',
                                email: columns[2] || '',
                                phone: columns[3] || '',
                                password: columns[4] || '',
                                isValid: false,
                                error: 'Kolom tidak lengkap (minimal 5 kolom)'
                            };
                        }

                        return {
                            name: columns[0].trim(),
                            username: columns[1].trim(),
                            email: columns[2].trim(),
                            phone: columns[3].trim(),
                            password: columns[4].trim(),
                            isValid: true,
                            error: ''
                        };
                    });

                    this.validateSecurityCsvData();
                },

                validateSecurityCsvData() {
                    this.securityCsvValidationErrors = [];
                    const usernames = new Set();

                    this.securityCsvPreviewData.forEach((row, index) => {
                        const errors = [];

                        if (!row.name) errors.push(`Baris ${index + 2}: Nama wajib diisi`);
                        if (!row.username) {
                            errors.push(`Baris ${index + 2}: Username wajib diisi`);
                        } else {
                            if (usernames.has(row.username)) {
                                errors.push(`Baris ${index + 2}: Username ${row.username} duplikat dalam file CSV`);
                            }
                            usernames.add(row.username);
                        }

                        if (!row.password) {
                            errors.push(`Baris ${index + 2}: Password wajib diisi`);
                        } else if (row.password.length < 8) {
                            errors.push(`Baris ${index + 2}: Password minimal 8 karakter`);
                        }

                        if (row.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(row.email)) {
                            errors.push(`Baris ${index + 2}: Format email tidak valid`);
                        }

                        if (row.phone && !/^\d+$/.test(row.phone)) {
                            errors.push(`Baris ${index + 2}: No HP harus berisi angka saja`);
                        }

                        if (errors.length > 0) {
                            row.isValid = false;
                            row.error = errors.join('; ');
                            this.securityCsvValidationErrors.push(...errors);
                        } else {
                            row.isValid = true;
                            row.error = '';
                        }
                    });

                    this.securityCsvValidCount = this.securityCsvPreviewData.filter(r => r.isValid).length;
                    this.securityCsvErrorCount = this.securityCsvPreviewData.filter(r => !r.isValid).length;
                },

                clearSecurityCsvData() {
                    this.securityCsvFileName = '';
                    this.securityCsvPreviewData = [];
                    this.securityCsvValidCount = 0;
                    this.securityCsvErrorCount = 0;
                    this.securityCsvValidationErrors = [];
                    document.getElementById('securityCsvFileInput').value = '';
                },

                submitForm() {
                    // Submit via normal form submission or AJAX
                    this.$el.closest('form').submit();
                }
            }
        }

        // Make wizard instance globally accessible for info window buttons
        document.addEventListener('alpine:init', () => {
            Alpine.store('wizard', {});
        });

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const wizardElement = document.querySelector('[x-data*="clusterWizard"]');
                if (wizardElement && wizardElement.__x) {
                    window.clusterWizardInstance = wizardElement.__x.$data;
                }
            }, 500);
        });
    </script>
@endpush