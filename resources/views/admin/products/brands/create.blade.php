@extends('admin.layouts.app')

@section('title', 'Tambah Brand Baru')
@section('subtitle', 'Tambahkan Brand Produk Baru')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali ke Daftar Brand
  </a>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informasi Brand</h3>
        </div>
        <div class="card-body">
          @if($errors->any())
          <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
              <div>
                <i class="ti ti-alert-circle icon alert-icon"></i>
              </div>
              <div>
                <h4 class="alert-title">Terjadi Kesalahan!</h4>
                <div class="text-secondary">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
          </div>
          @endif

          <div class="mb-3">
            <label class="form-label required">Nama Brand</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan nama brand" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Masukkan deskripsi brand">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" accept="image/*">
            <small class="form-hint">Format yang didukung: JPG, PNG, GIF. Ukuran maksimal: 2MB.</small>
            @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Brand Aktif</label>
            </div>
            <small class="form-hint">Brand yang tidak aktif tidak akan ditampilkan di halaman produk.</small>
          </div>
        </div>
        <div class="card-footer text-end">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>
            Simpan Brand
          </button>
        </div>
      </div>
    </form>
  </div>
  
  {{-- <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Petunjuk</h3>
      </div>
      <div class="card-body">
        <p>Berikut adalah beberapa petunjuk untuk menambahkan brand baru:</p>
        <ul class="mb-3">
          <li>Nama brand harus unik dan belum ada di sistem.</li>
          <li>Logo brand sebaiknya memiliki latar belakang transparan.</li>
          <li>Deskripsi brand akan membantu pelanggan mengenali brand Anda.</li>
          <li>Brand yang tidak aktif tidak akan ditampilkan di halaman produk.</li>
        </ul>
        <p class="mb-0">Brand yang sudah dibuat dapat diedit atau dihapus nanti.</p>
      </div>
    </div>
  </div> --}}
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  });
</script>
@endsection