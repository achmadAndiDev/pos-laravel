@extends('admin.layouts.app')

@section('title', 'Kategori Produk')
@section('subtitle', 'Kelola Kategori Produk')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Kategori
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kategori Produk</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Urutan</th>
                                <th>Status</th>
                                <th>Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($category->image)
                                        <span class="avatar avatar-sm" style="background-image: url({{ Storage::url($category->image) }})"></span>
                                    @else
                                        <span class="avatar avatar-sm">
                                            <i class="ti ti-category"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $category->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $category->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ Str::limit($category->description, 50) ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.product-categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $category->status === 'active' ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $category->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->products->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.product-categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product-categories.edit', $category) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
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
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty">
                                        <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                                        </div>
                                        <p class="empty-title">Belum ada kategori produk</p>
                                        <p class="empty-subtitle text-muted">
                                            Mulai dengan menambahkan kategori produk pertama Anda.
                                        </p>
                                        <div class="empty-action">
                                            <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus"></i>
                                                Tambah Kategori
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

@section('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#categoriesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        "responsive": true,
        "pageLength": 25,
        "order": [[5, "asc"]] // Sort by sort_order
    });

    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kategori akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endsection