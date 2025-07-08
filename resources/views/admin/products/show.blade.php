@extends('admin.layouts.app')

@section('title', 'Detail Produk')
@section('subtitle', 'Informasi Detail Produk')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
        <i class="ti ti-edit"></i>
        Edit Produk
    </a>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
            Aksi
        </button>
        <ul class="dropdown-menu">
            <li>
                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="dropdown-item">
                        <i class="ti ti-toggle-{{ $product->status === 'active' ? 'right' : 'left' }} me-2"></i>
                        {{ $product->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </li>
            <li>
                <form action="{{ route('admin.products.toggle-sellable', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="dropdown-item">
                        <i class="ti ti-shopping-cart{{ $product->is_sellable ? '-off' : '' }} me-2"></i>
                        {{ $product->is_sellable ? 'Tidak Dapat Dijual' : 'Dapat Dijual' }}
                    </button>
                </form>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="ti ti-trash me-2"></i>
                        Hapus Produk
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Produk</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Produk</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-blue-lt">{{ $product->code }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Barcode</label>
                            <div class="form-control-plaintext">
                                {{ $product->barcode ?: '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <div class="form-control-plaintext">
                        <strong>{{ $product->name }}</strong>
                    </div>
                </div>

                @if($product->description)
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <div class="form-control-plaintext">
                        {{ $product->description }}
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Outlet</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-green-lt">{{ $product->outlet->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-purple-lt">{{ $product->productCategory->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Beli</label>
                            <div class="form-control-plaintext">
                                <strong class="text-red">{{ $product->formatted_purchase_price }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Jual</label>
                            <div class="form-control-plaintext">
                                <strong class="text-green">{{ $product->formatted_selling_price }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Margin Keuntungan</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-yellow-lt">{{ number_format($product->margin_percentage, 2) }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Keuntungan per Unit</label>
                            <div class="form-control-plaintext">
                                <strong>Rp {{ number_format($product->profit_amount, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Berat</label>
                            <div class="form-control-plaintext">
                                {{ $product->weight ? $product->weight . ' gram' : '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($product->notes)
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <div class="form-control-plaintext">
                        {{ $product->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Stock Management Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Manajemen Stok</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.products.add-stock', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tambah Stok</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control" placeholder="Jumlah" min="1" required>
                                    <span class="input-group-text">{{ $product->unit }}</span>
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-plus"></i>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.products.reduce-stock', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Kurangi Stok</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control" placeholder="Jumlah" min="1" max="{{ $product->stock }}" required>
                                    <span class="input-group-text">{{ $product->unit }}</span>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="ti ti-minus"></i>
                                        Kurangi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Product Image Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gambar Produk</h3>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @else
                    <div class="empty">
                        <div class="empty-img">
                            <i class="ti ti-package icon-lg text-muted"></i>
                        </div>
                        <p class="empty-title">Tidak ada gambar</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Status Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Status Stok</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Stok Saat Ini</label>
                    <div class="form-control-plaintext">
                        <span class="badge badge-lg 
                            @if($product->stock_status === 'out_of_stock') bg-red-lt
                            @elseif($product->stock_status === 'low_stock') bg-yellow-lt
                            @else bg-green-lt
                            @endif
                        ">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok Minimum</label>
                    <div class="form-control-plaintext">
                        {{ $product->minimum_stock }} {{ $product->unit }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Stok</label>
                    <div class="form-control-plaintext">
                        @if($product->stock_status === 'out_of_stock')
                            <span class="badge bg-red-lt">Habis</span>
                        @elseif($product->stock_status === 'low_stock')
                            <span class="badge bg-yellow-lt">Stok Menipis</span>
                        @else
                            <span class="badge bg-green-lt">Stok Aman</span>
                        @endif
                    </div>
                </div>

                @if($product->stock <= $product->minimum_stock)
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle"></i>
                    Stok produk sudah mencapai batas minimum!
                </div>
                @endif
            </div>
        </div>

        <!-- Product Status Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Status Produk</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status Aktif</label>
                    <div class="form-control-plaintext">
                        <span class="badge 
                            @if($product->status === 'active') bg-green-lt
                            @else bg-red-lt
                            @endif
                        ">
                            {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dapat Dijual</label>
                    <div class="form-control-plaintext">
                        @if($product->is_sellable)
                            <span class="badge bg-blue-lt">Ya</span>
                        @else
                            <span class="badge bg-gray-lt">Tidak</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tersedia untuk Dijual</label>
                    <div class="form-control-plaintext">
                        @if($product->isAvailableForSale())
                            <span class="badge bg-green-lt">Ya</span>
                        @else
                            <span class="badge bg-red-lt">Tidak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Informasi Waktu</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Dibuat</label>
                    <div class="form-control-plaintext">
                        {{ $product->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Terakhir Diupdate</label>
                    <div class="form-control-plaintext">
                        {{ $product->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const productName = '{{ $product->name }}';
        
        Swal.fire({
            title: 'Hapus Produk?',
            html: `Apakah Anda yakin ingin menghapus produk <strong>${productName}</strong>?<br><br><small class="text-muted">Data produk akan dihapus permanen dan tidak dapat dikembalikan!</small>`,
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
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data produk',
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
    $(document).on('submit', 'form[action*="toggle-status"]', function(e) {
        e.preventDefault();
        
        const form = this;
        const currentStatus = '{{ $product->status }}';
        const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
        const productName = '{{ $product->name }}';
        
        Swal.fire({
            title: `${newStatus === 'aktif' ? 'Aktifkan' : 'Nonaktifkan'} Produk?`,
            html: `Apakah Anda yakin ingin ${newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan'} produk <strong>${productName}</strong>?`,
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

    // Toggle sellable confirmation
    $(document).on('submit', 'form[action*="toggle-sellable"]', function(e) {
        e.preventDefault();
        
        const form = this;
        const currentSellable = {{ $product->is_sellable ? 'true' : 'false' }};
        const newStatus = currentSellable ? 'tidak dapat dijual' : 'dapat dijual';
        const productName = '{{ $product->name }}';
        
        Swal.fire({
            title: `${newStatus === 'dapat dijual' ? 'Aktifkan Penjualan' : 'Nonaktifkan Penjualan'} Produk?`,
            html: `Apakah Anda yakin ingin membuat produk <strong>${productName}</strong> ${newStatus}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: newStatus === 'dapat dijual' ? '#28a745' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="ti ti-check me-1"></i>Ya, ${newStatus === 'dapat dijual' ? 'Aktifkan' : 'Nonaktifkan'}!`,
            cancelButtonText: '<i class="ti ti-x me-1"></i>Batal',
            customClass: {
                confirmButton: `btn ${newStatus === 'dapat dijual' ? 'btn-success' : 'btn-warning'}`,
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
@endpush