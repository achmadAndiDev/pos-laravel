@extends('admin.layouts.app')

@section('title', 'Customer')
@section('subtitle', 'Kelola Data Customer')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Customer
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Customer</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="customersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Customer</th>
                                <th>Kontak</th>
                                <th>Gender</th>
                                <th>Umur</th>
                                <th>Status</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                            @forelse($customers as $index => $customer)
                                <tbody>
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-blue-lt">{{ $customer->code }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ $customer->name }}</div>
                                                    @if($customer->email)
                                                        <div class="text-muted">{{ $customer->email }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                @if($customer->phone)
                                                    <div><i class="ti ti-phone me-1"></i>{{ $customer->phone }}</div>
                                                @endif
                                                @if($customer->address)
                                                    <div><i class="ti ti-map-pin me-1"></i>{{ Str::limit($customer->address, 30) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($customer->gender)
                                                <span class="badge {{ $customer->gender === 'male' ? 'bg-blue-lt' : 'bg-pink-lt' }}">
                                                    {{ $customer->gender_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer->age)
                                                {{ $customer->age }} tahun
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $customer->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $customer->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ number_format($customer->total_points, 0) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody> 
                                    {{-- <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                                                </div>
                                                <p class="empty-title">Belum ada data customer</p>
                                                <p class="empty-subtitle text-muted">
                                                    Mulai dengan menambahkan customer pertama Anda.
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                                                        <i class="ti ti-plus"></i>
                                                        Tambah Customer
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#customersTable').DataTable({
        autoWidth: false,
        responsive: true
    });

    // Delete confirmation - using event delegation to work with DataTable
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const customerName = $(this).closest('tr').find('.font-weight-medium').text();
        
        console.log('Delete form submitted for customer:', customerName); // Debug
        
        Swal.fire({
            title: 'Hapus Customer?',
            html: `Apakah Anda yakin ingin menghapus customer <strong>${customerName}</strong>?<br><br><small class="text-muted">Data customer akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
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
            // buttonsStyling: false
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

    // Toggle status confirmation - using event delegation
    $(document).on('submit', 'form[action*="toggle-status"]', function(e) {
        e.preventDefault();
        
        const form = this;
        const button = $(this).find('button[type="submit"]');
        const currentStatus = button.hasClass('btn-success') ? 'active' : 'inactive';
        const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
        const customerName = $(this).closest('tr').find('.font-weight-medium').text();
        
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
});
</script>
@endsection