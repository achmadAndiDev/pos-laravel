@extends('admin.layouts.app')

@section('title', 'Customer')
@section('subtitle', 'Detail Customer')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.customers.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
    <a href="{{ route('kasir.customers.edit', $customer) }}" class="btn btn-primary">
        <i class="ti ti-edit"></i>
        Edit Customer
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Customer</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Customer</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-blue-lt">{{ $customer->code }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Customer</label>
                            <div class="form-control-plaintext">{{ $customer->name }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <div class="form-control-plaintext">
                                @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control-plaintext">
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <div class="form-control-plaintext">
                                @if($customer->birth_date)
                                    {{ $customer->birth_date->format('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Umur</label>
                            <div class="form-control-plaintext">
                                @if($customer->age)
                                    {{ $customer->age }} tahun
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="form-control-plaintext">
                                @if($customer->gender)
                                    <span class="badge {{ $customer->gender === 'male' ? 'bg-blue-lt' : 'bg-pink-lt' }}">
                                        {{ $customer->gender_name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-control-plaintext">
                                <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $customer->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <div class="form-control-plaintext">
                        @if($customer->address)
                            {{ $customer->address }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                @if($customer->notes)
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <div class="form-control-plaintext">{{ $customer->notes }}</div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Dibuat</label>
                            <div class="form-control-plaintext">{{ $customer->created_at->format('d F Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Terakhir Diupdate</label>
                            <div class="form-control-plaintext">{{ $customer->updated_at->format('d F Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Points Management Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manajemen Poin</h3>
            </div>
            <div class="card-body">
                <div class="mb-3 text-center">
                    <div class="display-6 text-primary">{{ number_format($customer->total_points, 0) }}</div>
                    <div class="text-muted">Total Poin</div>
                </div>

                <!-- Add Points Form -->
                <form action="{{ route('kasir.customers.add-points', $customer) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Tambah Poin</label>
                        <input type="number" name="points" class="form-control" min="0.01" step="0.01" placeholder="Jumlah poin">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="notes" class="form-control" placeholder="Catatan (opsional)">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="ti ti-plus"></i> Tambah Poin
                    </button>
                </form>

                <!-- Deduct Points Form -->
                <form action="{{ route('kasir.customers.deduct-points', $customer) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Kurangi Poin</label>
                        <input type="number" name="points" class="form-control" min="0.01" step="0.01" placeholder="Jumlah poin" max="{{ $customer->total_points }}">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="notes" class="form-control" placeholder="Catatan (opsional)">
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm w-100" {{ $customer->total_points <= 0 ? 'disabled' : '' }}>
                        <i class="ti ti-minus"></i> Kurangi Poin
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('kasir.customers.toggle-status', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $customer->status === 'active' ? 'btn-warning' : 'btn-success' }} w-100">
                            <i class="ti {{ $customer->status === 'active' ? 'ti-user-off' : 'ti-user-check' }}"></i>
                            {{ $customer->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }} Customer
                        </button>
                    </form>

                    <a href="{{ route('kasir.customers.edit', $customer) }}" class="btn btn-primary">
                        <i class="ti ti-edit"></i> Edit Customer
                    </a>

                    <form action="{{ route('kasir.customers.destroy', $customer) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash"></i> Hapus Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const customerName = '{{ $customer->name }}';
        const customerCode = '{{ $customer->code }}';
        
        Swal.fire({
            title: 'Hapus Customer?',
            html: `Apakah Anda yakin ingin menghapus customer:<br><br><strong>${customerCode} - ${customerName}</strong><br><br><small class="text-muted">Data customer akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i>Ya, Hapus!',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data customer',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                form.submit();
            }
        });
    });

    // Toggle status confirmation
    $('form[action*="toggle-status"]').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const currentStatus = '{{ $customer->status }}';
        const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
        const customerName = '{{ $customer->name }}';
        
        Swal.fire({
            title: `${newStatus === 'aktif' ? 'Aktifkan' : 'Nonaktifkan'} Customer?`,
            html: `Apakah Anda yakin ingin ${newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan'} customer <strong>${customerName}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: newStatus === 'aktif' ? '#28a745' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="ti ti-check me-1"></i>Ya, ${newStatus === 'aktif' ? 'Aktifkan' : 'Nonaktifkan'}!`,
            cancelButtonText: '<i class="ti ti-x me-1"></i>Batal',
            customClass: {
                confirmButton: `btn ${newStatus === 'aktif' ? 'btn-success' : 'btn-warning'}`,
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Points form validation and confirmation
    $('form[action*="add-points"], form[action*="deduct-points"]').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const isAddPoints = $(this).attr('action').includes('add-points');
        const pointsInput = $(this).find('input[name="points"]');
        const notesInput = $(this).find('input[name="notes"]');
        const points = parseFloat(pointsInput.val());
        
        // Validation
        if (!points || points <= 0) {
            Swal.fire({
                title: 'Input Tidak Valid',
                text: 'Silakan masukkan jumlah poin yang valid (lebih dari 0)',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
            pointsInput.focus();
            return;
        }

        if (!isAddPoints && points > {{ $customer->total_points }}) {
            Swal.fire({
                title: 'Poin Tidak Mencukupi',
                text: `Poin customer saat ini hanya ${{{ number_format($customer->total_points, 0) }}}. Tidak dapat mengurangi ${points} poin.`,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
            return;
        }

        const action = isAddPoints ? 'menambahkan' : 'mengurangi';
        const customerName = '{{ $customer->name }}';
        
        Swal.fire({
            title: `${isAddPoints ? 'Tambah' : 'Kurangi'} Poin?`,
            html: `Apakah Anda yakin ingin ${action} <strong>${points} poin</strong> ${isAddPoints ? 'ke' : 'dari'} customer <strong>${customerName}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isAddPoints ? '#28a745' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="ti ti-${isAddPoints ? 'plus' : 'minus'} me-1"></i>Ya, ${isAddPoints ? 'Tambah' : 'Kurangi'}!`,
            cancelButtonText: '<i class="ti ti-x me-1"></i>Batal',
            customClass: {
                confirmButton: `btn ${isAddPoints ? 'btn-success' : 'btn-warning'}`,
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection