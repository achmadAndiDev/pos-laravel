@extends('admin.layouts.app')

@section('title', 'Detail Pembelian')
@section('subtitle', 'Detail Pembelian ' . $purchase->code)

@section('right-header')
<div class="btn-list">
    @if($purchase->canBeEdited())
        <a href="{{ route('kasir.purchases.edit', $purchase) }}" class="btn btn-warning">
            <i class="ti ti-edit"></i>
            Edit
        </a>
    @endif
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
            Aksi
        </button>
        <ul class="dropdown-menu">
            @if($purchase->canBeCompleted())
                <li>
                    <form action="{{ route('kasir.purchases.complete', $purchase) }}" method="POST" class="d-inline complete-form">
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
                    <form action="{{ route('kasir.purchases.cancel', $purchase) }}" method="POST" class="d-inline cancel-form">
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
                    <form action="{{ route('kasir.purchases.destroy', $purchase) }}" method="POST" class="d-inline delete-form">
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
    <a href="{{ route('kasir.purchases.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Purchase Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pembelian</h3>
                <div class="card-actions">
                    <span class="badge {{ $purchase->status_badge_class }} fs-3">{{ $purchase->status_text }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kode Pembelian</label>
                            <div class="fw-bold">{{ $purchase->code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Pembelian</label>
                            <div class="fw-bold">{{ $purchase->purchase_date->format('d/m/Y') }}</div>
                            <div class="text-muted small">Dibuat: {{ $purchase->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Outlet</label>
                            <div class="fw-bold">{{ $purchase->outlet->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Dibuat Oleh</label>
                            <div class="fw-bold">{{ $purchase->created_by }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Supplier</label>
                            <div class="fw-bold">{{ $purchase->supplier_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Telepon Supplier</label>
                            <div class="fw-bold">{{ $purchase->supplier_phone ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                @if($purchase->supplier_address)
                <div class="mb-3">
                    <label class="form-label text-muted">Alamat Supplier</label>
                    <div class="fw-bold">{{ $purchase->supplier_address }}</div>
                </div>
                @endif

                @if($purchase->invoice_number)
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor Invoice</label>
                            <div class="fw-bold">{{ $purchase->invoice_number }}</div>
                        </div>
                    </div>
                </div>
                @endif

                @if($purchase->notes)
                <div class="mb-3">
                    <label class="form-label text-muted">Catatan</label>
                    <div class="fw-bold">{{ $purchase->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Purchase Items -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Item Pembelian</h3>
                <div class="card-actions">
                    <span class="badge bg-purple-lt">{{ $purchase->purchaseItems->count() }} item</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->purchaseItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $item->product->name }}</div>
                                            <div class="text-muted">{{ $item->product->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $item->product->productCategory->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-green-lt">{{ number_format($item->quantity) }} {{ $item->product->unit }}</span>
                                </td>
                                <td>{{ $item->formatted_unit_price }}</td>
                                <td class="fw-bold">{{ $item->formatted_total_price }}</td>
                            </tr>
                            @if($item->notes)
                            <tr>
                                <td></td>
                                <td colspan="5">
                                    <small class="text-muted">
                                        <i class="ti ti-note me-1"></i>
                                        {{ $item->notes }}
                                    </small>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Summary -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Pembelian</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-bold">{{ $purchase->formatted_subtotal }}</span>
                </div>
                
                @if($purchase->tax_amount > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Pajak:</span>
                    <span class="fw-bold">Rp {{ number_format($purchase->tax_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                @if($purchase->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Diskon:</span>
                    <span class="fw-bold text-danger">- Rp {{ number_format($purchase->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-primary fs-3">{{ $purchase->formatted_total_amount }}</strong>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status & Aksi</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Status Saat Ini</label>
                    <div>
                        <span class="badge {{ $purchase->status_badge_class }} fs-4">{{ $purchase->status_text }}</span>
                    </div>
                </div>

                @if($purchase->status === 'draft')
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    Pembelian masih dalam status draft. Stok produk belum bertambah.
                </div>
                @elseif($purchase->status === 'completed')
                <div class="alert alert-success">
                    <i class="ti ti-check me-2"></i>
                    Pembelian telah selesai. Stok produk telah diperbarui.
                </div>
                @elseif($purchase->status === 'cancelled')
                <div class="alert alert-warning">
                    <i class="ti ti-x me-2"></i>
                    Pembelian telah dibatalkan.
                </div>
                @endif

                <div class="d-grid gap-2">
                    @if($purchase->canBeCompleted())
                        <form action="{{ route('kasir.purchases.complete', $purchase) }}" method="POST" class="complete-form">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-check me-2"></i>
                                Selesaikan Pembelian
                            </button>
                        </form>
                    @endif

                    @if($purchase->status === 'draft')
                        <form action="{{ route('kasir.purchases.cancel', $purchase) }}" method="POST" class="cancel-form">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="ti ti-x me-2"></i>
                                Batalkan Pembelian
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center px-0">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-green text-white">
                                <i class="ti ti-plus"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Pembelian Dibuat</div>
                            <div class="text-muted small">{{ $purchase->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    @if($purchase->status === 'completed')
                    <div class="list-group-item d-flex align-items-center px-0">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-blue text-white">
                                <i class="ti ti-check"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Pembelian Selesai</div>
                            <div class="text-muted small">{{ $purchase->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @elseif($purchase->status === 'cancelled')
                    <div class="list-group-item d-flex align-items-center px-0">
                        <div class="me-3">
                            <span class="avatar avatar-sm bg-yellow text-white">
                                <i class="ti ti-x"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Pembelian Dibatalkan</div>
                            <div class="text-muted small">{{ $purchase->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        
        Swal.fire({
            title: 'Hapus Pembelian?',
            html: `Apakah Anda yakin ingin menghapus pembelian <strong>{{ $purchase->code }}</strong>?<br><br><small class="text-muted">Data pembelian akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
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
        
        Swal.fire({
            title: 'Selesaikan Pembelian?',
            html: `Apakah Anda yakin ingin menyelesaikan pembelian <strong>{{ $purchase->code }}</strong>?<br><br><small class="text-muted">Stok produk akan otomatis bertambah sesuai dengan jumlah pembelian!</small>`,
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
        
        Swal.fire({
            title: 'Batalkan Pembelian?',
            html: `Apakah Anda yakin ingin membatalkan pembelian <strong>{{ $purchase->code }}</strong>?`,
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