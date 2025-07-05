@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')
@section('subtitle', 'Kelola Pengaturan Toko')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Toko</h3>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Informasi Dasar Toko</h4>
                        <hr>
                    </div>

                    <div class="mb-3">
                        <label for="site_name" class="form-label">Nama Toko</label>
                        <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                               id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">Deskripsi Toko</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                  id="site_description" name="site_description" rows="4" required>{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <h4>Logo Toko</h4>
                        <hr>
                    </div>

                    <div class="mb-3">
                        <label for="site_logo" class="form-label">Logo Toko</label>
                        
                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                            <div class="mb-3">
                                <img src="{{ asset($settings['site_logo']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                <small class="form-text text-muted d-block">Logo saat ini</small>
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('site_logo') is-invalid @enderror" 
                               id="site_logo" name="site_logo" accept="image/*">
                        <small class="form-text text-muted">
                            Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                        </small>
                        @error('site_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview untuk logo baru -->
                    <div class="mb-3" id="logo-preview" style="display: none;">
                        <label class="form-label">Preview Logo Baru:</label>
                        <div>
                            <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Preview image when file is selected
    $('#site_logo').on('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
                $('#logo-preview').show();
            }
            
            reader.readAsDataURL(file);
        } else {
            $('#logo-preview').hide();
        }
    });
});
</script>
@endsection
