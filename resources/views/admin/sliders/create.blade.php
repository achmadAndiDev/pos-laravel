@extends('admin.layouts.app')

@section('title', 'Tambah Slider Baru')
@section('subtitle', 'Tambahkan slider baru untuk halaman utama')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali ke Daftar Slider
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <div class="col-md-12">
    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" id="slider-form">
      @csrf
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Form Slider Baru</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">Judul Slider</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Deskripsi singkat yang akan ditampilkan pada slider</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Teks Tombol</label>
                <input type="text" class="form-control @error('button_text') is-invalid @enderror" name="button_text" value="{{ old('button_text') }}">
                @error('button_text')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Teks yang akan ditampilkan pada tombol (opsional)</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Link Tombol</label>
                <input type="text" class="form-control @error('button_link') is-invalid @enderror" name="button_link" value="{{ old('button_link') }}">
                @error('button_link')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">URL tujuan saat tombol diklik (opsional)</small>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">Gambar Slider</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" required>
                @error('image')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Ukuran gambar yang direkomendasikan: 1920x800 piksel. Format: JPG, PNG, GIF. Maks: 2MB.</small>
              </div>
              
              <div class="mb-3">
                <div id="image-preview" class="mt-3 d-none">
                  <label class="form-label">Preview Gambar:</label>
                  <div class="mt-2 text-center">
                    <img src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                  </div>
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control @error('order') is-invalid @enderror" name="order" value="{{ old('order', 0) }}" min="0">
                @error('order')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Urutan penampilan slider (0 = pertama)</small>
              </div>
              
              <div class="mb-3">
                <label class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                  <span class="form-check-label">Aktif</span>
                </label>
                <small class="form-hint d-block">Slider akan ditampilkan jika status aktif</small>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-end">
          <div class="d-flex">
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-link">Batal</a>
            <button type="submit" class="btn btn-primary ms-auto">
              <i class="ti ti-device-floppy"></i>
              Simpan Slider
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Preview gambar saat dipilih
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    
    imageInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.classList.remove('d-none');
          imagePreview.querySelector('img').src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
    
    // Form submission dengan SweetAlert
    const form = document.getElementById('slider-form');
    form.addEventListener('submit', function(e) {
      if (!form.checkValidity()) {
        return; // Biarkan validasi HTML5 berjalan
      }
      
      e.preventDefault();
      
      Swal.fire({
        title: 'Menyimpan Slider',
        text: 'Apakah Anda yakin ingin menyimpan slider ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return new Promise((resolve) => {
            form.submit();
            resolve();
          });
        }
      });
    });
  });
</script>
@endsection