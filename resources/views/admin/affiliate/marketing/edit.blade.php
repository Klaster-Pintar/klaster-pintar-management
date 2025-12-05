@extends('layouts.admin')

@section('title', 'Edit Marketing')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.affiliate.marketing.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="h3 mb-0 fw-bold">Edit Marketing</h1>
                        <p class="text-muted mb-0">Perbarui data marketing {{ $marketing->name }}</p>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-user-edit me-2"></i>Informasi Marketing
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.affiliate.marketing.update', $marketing) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Referral Code (Read-only) -->
                                <div class="col-12">
                                    <div class="alert alert-secondary">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><strong>Kode Referral:</strong></span>
                                            <span class="badge bg-dark fs-6">{{ $marketing->referral_code }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama Lengkap -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $marketing->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No Telepon -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">
                                        No Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        name="phone" value="{{ old('phone', $marketing->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email', $marketing->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Cluster Affiliate Name -->
                                <div class="col-md-6">
                                    <label for="cluster_affiliate_name" class="form-label fw-bold">
                                        Cluster Affiliate Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('cluster_affiliate_name') is-invalid @enderror"
                                        id="cluster_affiliate_name" name="cluster_affiliate_name"
                                        value="{{ old('cluster_affiliate_name', $marketing->cluster_affiliate_name) }}"
                                        required>
                                    @error('cluster_affiliate_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No KTP -->
                                <div class="col-md-6">
                                    <label for="id_card_number" class="form-label fw-bold">
                                        No KTP/Identitas
                                    </label>
                                    <input type="text" class="form-control @error('id_card_number') is-invalid @enderror"
                                        id="id_card_number" name="id_card_number"
                                        value="{{ old('id_card_number', $marketing->id_card_number) }}">
                                    @error('id_card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Join Date -->
                                <div class="col-md-6">
                                    <label for="join_date" class="form-label fw-bold">
                                        Tanggal Bergabung <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('join_date') is-invalid @enderror"
                                        id="join_date" name="join_date"
                                        value="{{ old('join_date', $marketing->join_date ? \Carbon\Carbon::parse($marketing->join_date)->format('Y-m-d') : '') }}"
                                        required>
                                    @error('join_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="Active" {{ old('status', $marketing->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $marketing->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Suspended" {{ old('status', $marketing->status) === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-bold">
                                        Alamat Lengkap
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                        name="address" rows="3">{{ old('address', $marketing->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.affiliate.marketing.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Marketing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-chart-line me-2"></i>Statistik Marketing
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-0 fw-bold">{{ $marketing->getTotalClusters() }}</h4>
                                    <small class="text-muted">Total Cluster</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0 fw-bold">Rp
                                        {{ number_format($marketing->getTotalRevenue(), 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Revenue</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-hand-holding-usd fa-2x text-warning mb-2"></i>
                                    <h4 class="mb-0 fw-bold">Rp
                                        {{ number_format($marketing->getTotalCommission(), 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Komisi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection