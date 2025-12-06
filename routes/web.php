<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClusterController;
use App\Http\Controllers\Admin\FinanceSettlementController;
use App\Http\Controllers\Admin\ClusterSubscriptionController;
use App\Http\Controllers\Admin\DeviceManagementController;
use App\Http\Controllers\Admin\DeviceTrackingController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\MarketingMappingController;
use App\Http\Controllers\Admin\CommissionSettingController;
use App\Http\Controllers\Admin\RevenueReportController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Settings Routes
        Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
        Route::post('/settings', [ProfileController::class, 'update'])->name('settings.update');
        Route::post('/settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/avatar', [ProfileController::class, 'uploadAvatar'])->name('settings.avatar');

        // User Management Routes
        Route::resource('users', UserController::class);

        // Cluster Management Routes
        Route::post('/clusters/sync-stakeholders', [ClusterController::class, 'syncStakeholders'])->name('clusters.sync-stakeholders');
        Route::post('/clusters/wizard/store', [ClusterController::class, 'storeWizard'])->name('clusters.wizard.store');
        Route::get('/clusters/residents/template', [ClusterController::class, 'downloadResidentTemplate'])->name('clusters.residents.template');
        Route::post('/clusters/{cluster}/residents/upload', [ClusterController::class, 'uploadResidents'])->name('clusters.residents.upload');
        
        // Cluster Basic Info
        Route::put('/clusters/{cluster}/basic-info', [ClusterController::class, 'updateBasicInfo'])->name('clusters.basic-info.update');
        
        // Cluster Offices CRUD
        Route::post('/clusters/{cluster}/offices', [ClusterController::class, 'storeOffice'])->name('clusters.offices.store');
        Route::put('/clusters/{cluster}/offices/{office}', [ClusterController::class, 'updateOffice'])->name('clusters.offices.update');
        Route::delete('/clusters/{cluster}/offices/{office}', [ClusterController::class, 'deleteOffice'])->name('clusters.offices.delete');
        
        // Cluster Patrols CRUD
        Route::post('/clusters/{cluster}/patrols', [ClusterController::class, 'storePatrol'])->name('clusters.patrols.store');
        Route::put('/clusters/{cluster}/patrols/{patrol}', [ClusterController::class, 'updatePatrol'])->name('clusters.patrols.update');
        Route::delete('/clusters/{cluster}/patrols/{patrol}', [ClusterController::class, 'deletePatrol'])->name('clusters.patrols.delete');
        
        // Cluster Bank Accounts CRUD
        Route::post('/clusters/{cluster}/banks', [ClusterController::class, 'storeBankAccount'])->name('clusters.banks.store');
        Route::put('/clusters/{cluster}/banks/{bank}', [ClusterController::class, 'updateBankAccount'])->name('clusters.banks.update');
        Route::delete('/clusters/{cluster}/banks/{bank}', [ClusterController::class, 'deleteBankAccount'])->name('clusters.banks.delete');
        
        // Cluster Employees CRUD
        Route::post('/clusters/{cluster}/employees', [ClusterController::class, 'storeEmployee'])->name('clusters.employees.store');
        Route::put('/clusters/{cluster}/employees/{employee}', [ClusterController::class, 'updateEmployee'])->name('clusters.employees.update');
        Route::delete('/clusters/{cluster}/employees/{employee}', [ClusterController::class, 'deleteEmployee'])->name('clusters.employees.delete');
        
        // Cluster Securities CRUD
        Route::post('/clusters/{cluster}/securities', [ClusterController::class, 'storeSecurity'])->name('clusters.securities.store');
        Route::put('/clusters/{cluster}/securities/{security}', [ClusterController::class, 'updateSecurity'])->name('clusters.securities.update');
        Route::delete('/clusters/{cluster}/securities/{security}', [ClusterController::class, 'deleteSecurity'])->name('clusters.securities.delete');
        
        Route::resource('clusters', ClusterController::class);

        // Finance Management Routes
        Route::prefix('finance')->name('finance.')->group(function () {
            // Cluster Subscription Routes
            Route::get('/subscription', [ClusterSubscriptionController::class, 'index'])->name('subscription.index');
            Route::get('/subscription/{id}', [ClusterSubscriptionController::class, 'show'])->name('subscription.show');
            Route::post('/subscription/{id}', [ClusterSubscriptionController::class, 'update'])->name('subscription.update');

            // Settlement Routes (Penarikan & Setoran)
            Route::get('/settlement', [FinanceSettlementController::class, 'index'])->name('settlement.index');
            Route::get('/settlement/{id}', [FinanceSettlementController::class, 'show'])->name('settlement.show');
            Route::post('/settlement/{id}/approve', [FinanceSettlementController::class, 'approve'])->name('settlement.approve');
            Route::post('/settlement/{id}/reject', [FinanceSettlementController::class, 'reject'])->name('settlement.reject');
        });

        // IoT Monitoring Routes
        Route::prefix('iot')->name('iot.')->group(function () {
            // Device Tracking Routes
            Route::get('/device-tracking', [DeviceTrackingController::class, 'index'])->name('device-tracking.index');
            Route::get('/device-tracking/{device}/status', [DeviceTrackingController::class, 'getDeviceStatus'])->name('device-tracking.status');

            // Device Management Routes (CRUD)
            Route::resource('device-management', DeviceManagementController::class)->parameters([
                'device-management' => 'device'
            ]);
        });

        // Affiliate Management Routes
        Route::prefix('affiliate')->name('affiliate.')->group(function () {
            // Marketing CRUD Routes
            Route::resource('marketing', MarketingController::class);

            // Marketing Cluster Mapping Routes
            Route::resource('mapping', MarketingMappingController::class);

            // Commission Settings Routes
            Route::resource('commission', CommissionSettingController::class);

            // Revenue Report Routes
            Route::get('/revenue', [RevenueReportController::class, 'index'])->name('revenue.index');
        });
    });
});

// Fallback route for 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});