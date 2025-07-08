@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('subtitle', 'Form Edit Customer')

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
    <h3 class="card-title">Form Edit Customer</h3>
  </div>
  <div class="card-body">
    <form id="customerForm" action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label required">Nama Customer</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $customer->name) }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Kode Customer</label>
          <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $customer->code) }}">
          @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Nomor Telepon</label>
          <div class="input-group">
            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $customer->phone) }}">
            @if($customer->phone)
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}?text={{ urlencode('Halo ' . $customer->name) }}" 
                 target="_blank" class="btn btn-success" data-bs-toggle="tooltip" title="Kirim WhatsApp">
                <i class="ti ti-brand-whatsapp"></i>
              </a>
            @endif
          </div>
          @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $customer->email) }}">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label">Alamat</label>
          <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', $customer->birth_date ? $customer->birth_date->format('Y-m-d') : '') }}">
          @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Jenis Kelamin</label>
          <select class="form-select @error('gender') is-invalid @enderror" name="gender">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
            <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
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
            <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Total Poin</label>
          <input type="number" class="form-control @error('total_points') is-invalid @enderror" name="total_points" value="{{ old('total_points', $customer->total_points) }}" min="0" step="0.01">
          @error('total_points')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label">Catatan</label>
          <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
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
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Form validation and submission
    $('#customerForm').on('submit', function(e) {
      // Add any custom validation here if needed
    });
  });
</script>
@endpush