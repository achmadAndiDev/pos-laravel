@extends('admin.layouts.app')

@section('title', 'Pembelian')
@section('subtitle', 'Kelola Data Pembelian')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Pembelian
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pembelian</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="purchasesTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Outlet</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $index => $purchase)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $purchase->code }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-medium">{{ $purchase->purchase_date->format('d/m/Y') }}</div>
                                    <div class="text-muted small">{{ $purchase->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $purchase->supplier_name }}</div>
                                            @if($purchase->supplier_phone)
                                                <div class="text-muted">{{ $purchase->supplier_phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-green-lt">{{ $purchase->outlet->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt">{{ $purchase->purchaseItems->count() }} item</span>
                                </td>
                                <td>
                                    <div class="font-weight-medium">{{ $purchase->formatted_total_amount }}</div>
                                    @if($purchase->invoice_number)
                                        <div class="text-muted small">Invoice: {{ $purchase->invoice_number }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $purchase->status_badge_class }}">
                                        {{ $purchase->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.purchases.show', $purchase) }}" class="btn btn-sm btn-outline-info">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        @if($purchase->canBeEdited())
                                            <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        @endif
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($purchase->canBeCompleted())
                                                    <li>
                                                        <form action="{{ route('admin.purchases.complete', $purchase) }}" method="POST" class="d-inline complete-form">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="ti ti-check me-2"></i>
                                                                Selesaikan
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($purchase->status === 'draft')
                                                    <li>
                                                        <form action="{{ route('admin.purchases.cancel', $purchase) }}" method="POST" class="d-inline cancel-form">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="ti ti-x me-2"></i>
                                                                Batalkan
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($purchase->status !== 'completed')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash me-2"></i>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty">
                                        <div class="empty-img">
                                            <i class="ti ti-shopping-cart" style="font-size: 4rem; color: #6c757d;"></i>
                                        </div>
                                        <p class="empty-title">Belum ada pembelian</p>
                                        <p class="empty-subtitle text-muted">
                                            Klik tombol "Tambah Pembelian" untuk menambahkan pembelian pertama.
                                        </p>
                                        <div class="empty-action">
                                            <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus"></i>
                                                Tambah Pembelian
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
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
    $('#purchasesTable').DataTable({
        autoWidth: false,
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        columnDefs: [
            { orderable: false, targets: [8] } // Disable sorting for action column
        ],
        order: [[0, 'desc']]
    });

    // Delete confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const purchaseCode = $(this).closest('tr').find('.badge.bg-blue-lt').text();
        
        Swal.fire({
            title: 'Hapus Pembelian?',
            html: `Apakah Anda yakin ingin menghapus pembelian <strong>${purchaseCode}</strong>?<br><br><small class="text-muted">Data pembelian akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
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
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data pembelian',
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

    // Complete confirmation
    $(document).on('submit', '.complete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const purchaseCode = $(this).closest('tr').find('.badge.bg-blue-lt').text();
        
        Swal.fire({
            title: 'Selesaikan Pembelian?',
            html: `Apakah Anda yakin ingin menyelesaikan pembelian <strong>${purchaseCode}</strong>?<br><br><small class="text-muted">Stok produk akan otomatis bertambah sesuai dengan jumlah pembelian!</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-check me-1"></i>Ya, Selesaikan!',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Batal',
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Cancel confirmation
    $(document).on('submit', '.cancel-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const purchaseCode = $(this).closest('tr').find('.badge.bg-blue-lt').text();
        
        Swal.fire({
            title: 'Batalkan Pembelian?',
            html: `Apakah Anda yakin ingin membatalkan pembelian <strong>${purchaseCode}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-x me-1"></i>Ya, Batalkan!',
            cancelButtonText: '<i class="ti ti-arrow-back me-1"></i>Kembali',
            customClass: {
                confirmButton: 'btn btn-warning',
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