@extends('layouts.admin')

@section('title', 'Tambah Marketing')

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
                        <h1 class="h3 mb-0 fw-bold">Tambah Marketing Baru</h1>
                        <p class="text-muted mb-0">Tambahkan data marketing & affiliate baru</p>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-user-plus me-2"></i>Informasi Marketing
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.affiliate.marketing.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Nama Lengkap -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}" required>
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
                                        name="phone" value="{{ old('phone') }}" required>
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
                                        name="email" value="{{ old('email') }}" required>
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
                                        value="{{ old('cluster_affiliate_name') }}" required>
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
                                        id="id_card_number" name="id_card_number" value="{{ old('id_card_number') }}">
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
                                        id="join_date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}"
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
                                        <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                        <option value="Suspended" {{ old('status') === 'Suspended' ? 'selected' : '' }}>
                                            Suspended</option>
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
                                        name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Info Box -->
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Info:</strong> Kode Referral akan di-generate secara otomatis saat data
                                        disimpan.
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.affiliate.marketing.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Marketing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection