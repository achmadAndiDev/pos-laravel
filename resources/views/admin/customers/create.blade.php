@extends('admin.layouts.app')

@section('title', 'Tambah Customer')
@section('subtitle', 'Form Tambah Customer Baru')

@push('styles')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>
@endpush

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Form Tambah Customer</h3>
  </div>
  <div class="card-body">
    <form id="customerForm" action="{{ route('admin.customers.store') }}" method="POST">
      @csrf
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label required">Nama Customer</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Kode Customer</label>
          <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" placeholder="Kosongkan untuk generate otomatis">
          @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Nomor Telepon</label>
          <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
          @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label">Alamat</label>
          <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}">
          @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Jenis Kelamin</label>
          <select class="form-select @error('gender') is-invalid @enderror" name="gender">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
          </select>
          @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select class="form-select @error('status') is-invalid @enderror" name="status">
            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Total Poin</label>
          <input type="number" class="form-control @error('total_points') is-invalid @enderror" name="total_points" value="{{ old('total_points', 0) }}" min="0" step="0.01">
          @error('total_points')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label">Catatan</label>
          <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
          @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="form-footer">
        <div class="row">
          <div class="col-md-6">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary w-100">
              <i class="ti ti-x me-1"></i>
              Batal
            </a>
          </div>
          <div class="col-md-6">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti ti-device-floppy me-1"></i>
              Simpan
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Form validation and submission
    $('#customerForm').on('submit', function(e) {
      // Add any custom validation here if needed
    });
  });
</script>
@endpush