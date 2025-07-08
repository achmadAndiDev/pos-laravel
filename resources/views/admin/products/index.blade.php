@extends('admin.layouts.app')

@section('title', 'Produk')
@section('subtitle', 'Kelola Data Produk')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Produk
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Produk</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="productsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Outlet</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="avatar avatar-sm">
                                    @else
                                        <div class="avatar avatar-sm bg-secondary-lt">
                                            <i class="ti ti-package"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $product->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $product->name }}</div>
                                            @if($product->barcode)
                                                <div class="text-muted">Barcode: {{ $product->barcode }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt">{{ $product->productCategory->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-green-lt">{{ $product->outlet->name }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-medium">{{ $product->formatted_selling_price }}</div>
                                    <div class="text-muted small">Beli: {{ $product->formatted_purchase_price }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge 
                                            @if($product->stock_status === 'out_of_stock') bg-red-lt
                                            @elseif($product->stock_status === 'low_stock') bg-yellow-lt
                                            @else bg-green-lt
                                            @endif
                                        ">
                                            {{ $product->stock }} {{ $product->unit }}
                                        </span>
                                    </div>
                                    @if($product->stock <= $product->minimum_stock)
                                        <div class="text-muted small">Min: {{ $product->minimum_stock }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge 
                                            @if($product->status === 'active') bg-green-lt
                                            @else bg-red-lt
                                            @endif
                                        ">
                                            {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        @if($product->is_sellable)
                                            <span class="badge bg-blue-lt">Dapat Dijual</span>
                                        @else
                                            <span class="badge bg-gray-lt">Tidak Dijual</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
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
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="empty">
                                        <div class="empty-img"><img src="{{ asset('assets/img/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                                        </div>
                                        <p class="empty-title">Belum ada produk</p>
                                        <p class="empty-subtitle text-muted">
                                            Klik tombol "Tambah Produk" untuk menambahkan produk pertama.
                                        </p>
                                        <div class="empty-action">
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus"></i>
                                                Tambah Produk
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
    $('#productsTable').DataTable({
        autoWidth: false,
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        columnDefs: [
            { orderable: false, targets: [1, 9] } // Disable sorting for image and action columns
        ],
        order: [[0, 'asc']]
    });

    // Delete confirmation - using event delegation to work with DataTable
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        const form = this;
        const productName = $(this).closest('tr').find('.font-weight-medium').text();
        
        console.log('Delete form submitted for product:', productName); // Debug
        
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
            // buttonsStyling: false
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

    // Toggle status confirmation - using event delegation
    $(document).on('submit', 'form[action*="toggle-status"]', function(e) {
        e.preventDefault();
        
        const form = this;
        const button = $(this).find('button[type="submit"]');
        const currentStatus = button.text().trim().toLowerCase();
        const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
        const productName = $(this).closest('tr').find('.font-weight-medium').text();
        
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

    // Toggle sellable confirmation - using event delegation
    $(document).on('submit', 'form[action*="toggle-sellable"]', function(e) {
        e.preventDefault();
        
        const form = this;
        const button = $(this).find('button[type="submit"]');
        const buttonText = button.text().trim();
        const newStatus = buttonText.includes('Tidak Dapat Dijual') ? 'tidak dapat dijual' : 'dapat dijual';
        const productName = $(this).closest('tr').find('.font-weight-medium').text();
        
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
@endsection