@extends('admin.layouts.app')

@section('title', 'Outlet')
@section('subtitle', 'Kelola Data Outlet')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.outlets.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Outlet
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Outlet</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="outletsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Outlet</th>
                                <th>Alamat</th>
                                <th>Manager</th>
                                <th>Status</th>
                                <th>Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outlets as $index => $outlet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $outlet->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $outlet->name }}</div>
                                            @if($outlet->email)
                                                <div class="text-muted">{{ $outlet->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ Str::limit($outlet->address, 50) }}
                                        @if($outlet->phone)
                                            <br><small class="text-muted">{{ $outlet->phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $outlet->manager ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.outlets.toggle-status', $outlet) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $outlet->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $outlet->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $outlet->products->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.outlets.show', $outlet) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.outlets.edit', $outlet) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.outlets.destroy', $outlet) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            {{-- <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty">
                                        <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                                        </div>
                                        <p class="empty-title">Belum ada data outlet</p>
                                        <p class="empty-subtitle text-muted">
                                            Mulai dengan menambahkan outlet pertama Anda.
                                        </p>
                                        <div class="empty-action">
                                            <a href="{{ route('admin.outlets.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus"></i>
                                                Tambah Outlet
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr> --}}
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
    $('#outletsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        "responsive": true,
        "pageLength": 25,
        "order": [[0, "asc"]]
    });

    // Delete confirmation - using event delegation to work with DataTable
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const outletName = $(this).closest('tr').find('.font-weight-medium').text();
        
        console.log('Delete form submitted for outlet:', outletName); // Debug
        
        Swal.fire({
            title: 'Hapus Outlet?',
            html: `Apakah Anda yakin ingin menghapus outlet <strong>${outletName}</strong>?<br><br><small class="text-muted">Data outlet akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
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
                    text: 'Sedang menghapus data outlet',
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
});
</script>
@endsection