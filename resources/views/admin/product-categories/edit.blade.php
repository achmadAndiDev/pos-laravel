@extends('admin.layouts.app')

@section('title', 'Kategori Produk')
@section('subtitle', 'Edit Kategori Produk')

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
        <form action="{{ route('kasir.product-categories.update', $productCategory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Informasi Kategori Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Nama Kategori</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $productCategory->name) }}" placeholder="Masukkan nama kategori">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kode Kategori</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       name="code" value="{{ old('code', $productCategory->code) }}" placeholder="Contoh: FOOD">
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
                                  name="description" rows="3" placeholder="Deskripsi kategori produk">{{ old('description', $productCategory->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gambar Kategori</label>
                                
                                @if($productCategory->image)
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($productCategory->image) }}" alt="Current Image" 
                                             class="img-thumbnail me-2" style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCurrentImage()">
                                            <i class="ti ti-trash"></i> Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                                @endif
                                
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
                                            <option value="active" {{ old('status', $productCategory->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status', $productCategory->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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
                                               name="sort_order" value="{{ old('sort_order', $productCategory->sort_order) }}" min="0" placeholder="0">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                            Update Kategori
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

// Delete current image
function deleteCurrentImage() {
    Swal.fire({
        title: 'Hapus Gambar?',
        text: "Gambar akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.product-categories.delete-image", $productCategory) }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Terjadi kesalahan saat menghapus gambar');
                }
            });
        }
    });
}
</script>
@endsection