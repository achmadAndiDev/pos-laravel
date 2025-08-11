@extends('admin.layouts.app')

@section('title', 'Tambah User')

@section('css')
<style>
    .role-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .outlet-checkbox {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        transition: all 0.15s ease-in-out;
    }
    .outlet-checkbox:hover {
        background-color: #f8f9fa;
    }
    .outlet-checkbox.selected {
        background-color: var(--tblr-primary-lt);
        border-color: var(--tblr-primary);
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                    <div class="page-pretitle">
                        Manajemen User
                    </div>
                    <h2 class="page-title">
                        Tambah User Baru
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi User</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email') }}" placeholder="Masukkan alamat email" required>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Password</label>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                                   placeholder="Masukkan password" required>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" 
                                                   placeholder="Konfirmasi password" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Telepon</label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone') }}" placeholder="Masukkan nomor telepon">
                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label">User Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Role & Akses</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">Role</label>
                                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}
                                                data-multiple="{{ $role->canAccessMultipleOutlets() ? 'true' : 'false' }}">
                                            {{ $role->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="role-description" class="role-description"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Outlet</label>
                                    <div id="outlet-selection">
                                        @error('outlets')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        
                                        @foreach($outlets as $outlet)
                                        <div class="outlet-checkbox">
                                            <div class="form-check">
                                                <input class="form-check-input outlet-checkbox-input" type="checkbox" 
                                                       name="outlets[]" value="{{ $outlet->id }}" id="outlet_{{ $outlet->id }}"
                                                       {{ in_array($outlet->id, old('outlets', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="outlet_{{ $outlet->id }}">
                                                    <div class="fw-bold">{{ $outlet->full_name }}</div>
                                                    <div class="text-muted small">{{ $outlet->address }}</div>
                                                    @if($outlet->manager)
                                                    <div class="text-muted small">Manager: {{ $outlet->manager }}</div>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text">
                                        <span id="outlet-help-text">Pilih outlet yang dapat diakses oleh user ini.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent mt-3">
                            <div class="btn-list justify-content-end">
                                <a href="{{ route('admin.users.index') }}" class="btn">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i>
                                    Simpan User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleDescription = document.getElementById('role-description');
    const outletCheckboxes = document.querySelectorAll('.outlet-checkbox-input');
    const outletHelpText = document.getElementById('outlet-help-text');
    
    // Role descriptions
    const roleDescriptions = {
        'super_admin': 'Akses penuh ke seluruh sistem dan semua outlet.',
        'admin': 'Akses penuh ke outlet yang ditugaskan.',
        'staf_pembelian': 'Akses khusus untuk modul pembelian. Hanya dapat memiliki satu outlet.',
        'staf_penjualan': 'Akses khusus untuk modul penjualan. Hanya dapat memiliki satu outlet.'
    };

    function updateRoleInfo() {
        const selectedRole = roleSelect.value;
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const canMultiple = selectedOption.getAttribute('data-multiple') === 'true';
        
        // Update description
        if (selectedRole && roleDescriptions[selectedRole]) {
            roleDescription.textContent = roleDescriptions[selectedRole];
            roleDescription.style.display = 'block';
        } else {
            roleDescription.style.display = 'none';
        }
        
        // Update outlet selection behavior
        if (selectedRole) {
            if (canMultiple) {
                outletHelpText.textContent = 'Pilih satu atau lebih outlet yang dapat diakses oleh user ini.';
                // Allow multiple selections
                outletCheckboxes.forEach(checkbox => {
                    checkbox.type = 'checkbox';
                });
            } else {
                outletHelpText.textContent = 'Pilih satu outlet untuk user ini (role staff hanya dapat memiliki satu outlet).';
                // Convert to radio-like behavior
                outletCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            outletCheckboxes.forEach(other => {
                                if (other !== this) {
                                    other.checked = false;
                                    other.closest('.outlet-checkbox').classList.remove('selected');
                                }
                            });
                        }
                    });
                });
            }
        }
    }

    // Handle outlet checkbox styling
    outletCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const outletBox = this.closest('.outlet-checkbox');
            if (this.checked) {
                outletBox.classList.add('selected');
            } else {
                outletBox.classList.remove('selected');
            }
        });
        
        // Initialize styling
        if (checkbox.checked) {
            checkbox.closest('.outlet-checkbox').classList.add('selected');
        }
    });

    roleSelect.addEventListener('change', updateRoleInfo);
    
    // Initialize on page load
    updateRoleInfo();
});
</script>
@endsection