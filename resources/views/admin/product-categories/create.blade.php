@extends('admin.layouts.app')

@section('title', 'Kategori Produk')
@section('subtitle', 'Tambah Kategori Produk Baru')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.product-categories.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('kasir.product-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kategori Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Nama Kategori</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" placeholder="Masukkan nama kategori">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kode Kategori</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       name="code" value="{{ old('code') }}" placeholder="Contoh: FOOD">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Kode unik untuk kategori (akan otomatis menjadi huruf besar)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" placeholder="Deskripsi kategori produk">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gambar Kategori</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       name="image" accept="image/*" onchange="previewImage(this)">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                
                                <!-- Image Preview -->
                                <div class="mt-2" id="imagePreview" style="display: none;">
                                    <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                                            <option value="">Pilih Status</option>
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Urutan</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               name="sort_order" value="{{ old('sort_order') }}" min="0" placeholder="0">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-hint">Kosongkan untuk urutan otomatis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('kasir.product-categories.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-device-floppy"></i>
                            Simpan Kategori
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Auto generate code from name
    $('input[name="name"]').on('input', function() {
        let name = $(this).val();
        let code = name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 6).toUpperCase();
        if (code && !$('input[name="code"]').val()) {
            $('input[name="code"]').val(code);
        }
    });
    
    // Convert code to uppercase
    $('input[name="code"]').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
});

// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').show();
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#imagePreview').hide();
    }
}
</script>
@endsection