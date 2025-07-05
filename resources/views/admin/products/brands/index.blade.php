@extends('admin.layouts.app')

@section('title', 'Brand Produk')
@section('subtitle', 'Manajemen Brand Produk')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.brands.create') }}" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-plus"></i>
    Tambah Brand
  </a>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-backpack"></i>
    Kembali ke Produk
  </a>
  <a href="{{ route('admin.brands.create') }}" class="btn btn-primary d-sm-none">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Brand Produk</h3>
      </div>
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <div class="d-flex">
            <div>
              <i class="ti ti-check icon alert-icon"></i>
            </div>
            <div>
              <h4 class="alert-title">Sukses!</h4>
              <div class="text-secondary">{{ session('success') }}</div>
            </div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <div class="table-responsive">
          <table id="brandTable" class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Brand</th>
                <th>Status</th>
                <th class="w-1">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($brands as $brand)
              <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                  @if($brand->is_active)
                    <span class="badge bg-success text-white">Aktif</span>
                  @else
                    <span class="badge bg-danger text-white">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-warning btn-icon" title="Edit Brand">
                      <i class="ti ti-edit"></i>
                    </a>
                    <div class="form-check form-switch d-inline-block ms-2">
                      <input class="form-check-input status-switch" type="checkbox" id="status-{{ $brand->id }}" 
                        data-id="{{ $brand->id }}" 
                        data-name="{{ $brand->name }}"
                        {{ $brand->is_active ? 'checked' : '' }}>
                    </div>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="text-center">Tidak ada data brand</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div>
            <span id="paginationInfo" class="text-muted">
              Menampilkan {{ $brands->firstItem() ?? 0 }}-{{ $brands->lastItem() ?? 0 }} dari {{ $brands->total() ?? 0 }} brand
              (Halaman {{ $brands->currentPage() ?? 0 }} dari {{ $brands->lastPage() ?? 0 }})
            </span>
          </div>
          <div class="d-flex align-items-center">
            <select class="form-select form-select-sm d-inline-block me-2" id="perPageSelect" style="width: auto;">
              <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
              <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
              <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
              <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            {{ $brands->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
  
  {{-- <div class="col-md-12 mt-3">
    <div class="row row-cards">
      @foreach($brands as $brand)
      <div class="col-md-3 mb-3">
        <div class="card">
          <div class="card-body p-4 text-center">
            @if($brand->logo)
              <span class="avatar avatar-xl mb-3 rounded" style="background-image: url({{ Storage::url($brand->logo) }})"></span>
            @else
              <span class="avatar avatar-xl mb-3 rounded bg-primary text-white">{{ substr($brand->name, 0, 1) }}</span>
            @endif
            <h3 class="m-0 mb-1">{{ $brand->name }}</h3>
            <div class="text-muted">{{ $brand->products->count() }} Produk</div>
            <div class="mt-3">
              @if($brand->is_active)
                <span class="badge  bg-success text-white">Aktif</span>
              @else
                <span class="badge  bg-danger text-white">Nonaktif</span>
              @endif
            </div>
          </div>
          <div class="d-flex">
            <a href="{{ route('admin.brands.edit', $brand) }}" class="card-btn">
              <i class="ti ti-edit"></i>
              Edit
            </a>
            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline card-btn" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brand ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-link text-danger w-100 h-100">
                <i class="ti ti-trash"></i>
                Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div> --}}
</div>

@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 32px;
    width: 32px;
    padding: 0;
  }
  
  .badge {
    padding: 5px 10px;
    border-radius: 3px;
  }
  
  
  /* Style untuk switch toggle */
  .form-check.form-switch {
    margin-bottom: 0;
    display: flex;
    align-items: center;
  }
  
  .form-check-input.status-switch {
    height: 1.5rem;
    width: 3rem;
    cursor: pointer;
  }
  
  .form-check-input.status-switch:checked {
    background-color: var(--tblr-success);
    border-color: var(--tblr-success);
  }
  
  .form-check-input.status-switch:not(:checked) {
    background-color: var(--tblr-danger);
    border-color: var(--tblr-danger);
  }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  });
</script>

<script>
  $(document).ready(function() {
    // Handle per page selection
    $('#perPageSelect').on('change', function() {
      const perPage = $(this).val();
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.set('per_page', perPage);
      window.location.href = currentUrl.toString();
    });
    
    // Inisialisasi DataTable
    const brandTable = $('#brandTable').DataTable({
      responsive: true,
      language: {
        emptyTable: "Tidak ada data yang tersedia pada tabel ini",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
        infoPostFix: "",
        thousands: ".",
        lengthMenu: "Tampilkan _MENU_ entri",
        loadingRecords: "Sedang memuat...",
        processing: "Sedang memproses...",
        search: "Cari:",
        zeroRecords: "Tidak ditemukan data yang sesuai",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya"
        },
        aria: {
          sortAscending: ": aktifkan untuk mengurutkan kolom ke atas",
          sortDescending: ": aktifkan untuk mengurutkan kolom ke bawah"
        }
      },
      pagingType: "full_numbers",
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      // hide pagination baawaan datatables
      paging: false,
      info: false,
      drawCallback: function() {
        $('.dataTables_paginate > .pagination').addClass('pagination-sm');
      },
      initComplete: function() {
        // Menambahkan kelas Bootstrap pada elemen search dan length
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_length select').addClass('form-select');
        
        // Menambahkan label yang lebih jelas
        $('.dataTables_filter label').contents().filter(function() {
          return this.nodeType === 3;
        }).replaceWith('<span class="me-2">Cari:</span>');
      }
    });
    
    // Fungsi untuk menghasilkan slug dari nama
    function generateSlug(name) {
      return name.toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
    }
    
    // Event untuk menghasilkan slug otomatis saat nama diketik
    $('input[name="name"]').on('keyup', function() {
      const name = $(this).val();
      const slugField = $(this).closest('form').find('input[name="slug"]');
      
      // Hanya update jika field slug kosong atau belum diubah manual
      if (!slugField.data('manually-changed')) {
        slugField.val(generateSlug(name));
      }
    });
    
    // Tandai jika slug diubah manual
    $('input[name="slug"]').on('keyup', function() {
      $(this).data('manually-changed', true);
    });
    
    // Event handler untuk toggle switch status
    $(document).on('change', '.status-switch', function() {
      const checkbox = $(this);
      const brandId = checkbox.data('id');
      const brandName = checkbox.data('name');
      const newStatus = checkbox.prop('checked') ? 1 : 0;
      const statusText = newStatus === 1 ? 'mengaktifkan' : 'menonaktifkan';
      const resultText = newStatus === 1 ? 'diaktifkan' : 'dinonaktifkan';
      
      // Kembalikan ke status sebelumnya sampai konfirmasi
      checkbox.prop('checked', !checkbox.prop('checked'));
      
      Swal.fire({
        title: 'Konfirmasi Perubahan Status',
        text: `Apakah Anda yakin ingin ${statusText} brand "${brandName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah Status!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Simulasi AJAX request untuk mengubah status
          $.ajax({
            url: `/admin/brands/${brandId}/toggle-status`,
            type: 'POST',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              status: newStatus
            },
            beforeSend: function() {
              // Tampilkan loading
              Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengubah status brand',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });
            },
            success: function(response) {
              // Update UI
              checkbox.prop('checked', newStatus === 1);
              
              checkbox.closest('tr').find('td:nth-child(3) .badge')
                .removeClass('bg-success bg-danger')
                .addClass(newStatus === 1 ? 'bg-success' : 'bg-danger')
                .text(newStatus === 1 ? 'Aktif' : 'Nonaktif');
              
              // Tampilkan pesan sukses
              Swal.fire({
                title: 'Berhasil!',
                text: `Brand "${brandName}" telah ${resultText}`,
                icon: 'success',
                confirmButtonText: 'OK'
              });
            },
            error: function(xhr, status, error) {
              // Kembalikan ke status sebelumnya jika gagal
              checkbox.prop('checked', !newStatus === 1);
              
              // Tampilkan pesan error
              Swal.fire({
                title: 'Gagal!',
                text: `Terjadi kesalahan saat mengubah status brand: ${error}`,
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        }
      });
    });
    
    // Event handler untuk tombol Tambah Brand
    $('#saveBrandBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('addBrandForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi penyimpanan data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Brand baru telah ditambahkan',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#addBrandModal').modal('hide');
          $('#addBrandForm').trigger('reset');
          
          // Refresh halaman untuk menampilkan data terbaru
          location.reload();
        }
      });
    });
    
    // Event handler untuk tombol Edit Brand
    $('#updateBrandBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('editBrandForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi update data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Brand telah diperbarui',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#editBrandModal').modal('hide');
          
          // Refresh halaman untuk menampilkan data terbaru
          location.reload();
        }
      });
    });
    
    // Event handler untuk modal Edit Brand
    $('#editBrandModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const brandId = button.data('id');
      
      // Dalam implementasi nyata, di sini akan ada AJAX request untuk mengambil data brand
      // Untuk contoh ini, kita gunakan data dummy
      
      // Simulasi data yang diambil dari server
      const brandData = {
        id: 'BRD-001',
        name: 'Pindon Outdoor',
        slug: 'pindon-outdoor',
        description: 'Brand utama Pindon Outdoor',
        website: 'https://pindonoutdoor.com',
        status: 'active'
      };
      
      // Mengisi data ke dalam form edit
      $('#edit_brand_id').val(brandData.id);
      $('#edit_name').val(brandData.name);
      $('#edit_slug').val(brandData.slug);
      $('#edit_description').val(brandData.description);
      $('#edit_website').val(brandData.website);
      $('#edit_status').val(brandData.status);
      
      // Reset flag manually-changed untuk slug
      $('#edit_slug').data('manually-changed', true);
    });
  });
</script>
@endsection