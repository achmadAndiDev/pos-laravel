@extends('admin.layouts.app')

@section('title', 'Edit Brand')
@section('subtitle', 'Edit Brand Produk')

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
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
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

          <div class="mb-3 text-center">
            @if($brand->logo)
              <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ Storage::url($brand->logo) }})"></span>
            @else
              <span class="avatar avatar-xl mb-3 rounded bg-primary text-white">{{ substr($brand->name, 0, 1) }}</span>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label required">Nama Brand</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $brand->name) }}" placeholder="Masukkan nama brand" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Masukkan deskripsi brand">{{ old('description', $brand->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" accept="image/*">
            <small class="form-hint">Format yang didukung: JPG, PNG, GIF. Ukuran maksimal: 2MB. Biarkan kosong jika tidak ingin mengubah logo.</small>
            @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Brand Aktif</label>
            </div>
            <small class="form-hint">Brand yang tidak aktif tidak akan ditampilkan di halaman produk.</small>
          </div>
        </div>
        <div class="card-footer text-end">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>
            Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
{{--   
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Informasi Brand</h3>
      </div>
      <div class="card-body">
        <div class="datagrid">
          <div class="datagrid-item">
            <div class="datagrid-title">ID Brand</div>
            <div class="datagrid-content">{{ $brand->id }}</div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Slug</div>
            <div class="datagrid-content">{{ $brand->slug }}</div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Jumlah Produk</div>
            <div class="datagrid-content">{{ $brand->products->count() }}</div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Tanggal Dibuat</div>
            <div class="datagrid-content">{{ $brand->created_at->format('d M Y, H:i') }}</div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Terakhir Diperbarui</div>
            <div class="datagrid-content">{{ $brand->updated_at->format('d M Y, H:i') }}</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Tindakan</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brand ini? Semua produk dengan brand ini akan kehilangan brand-nya.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger w-100">
            <i class="ti ti-trash me-1"></i>
            Hapus Brand
          </button>
        </form>
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