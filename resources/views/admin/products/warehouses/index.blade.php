@extends('admin.layouts.app')

@section('title', 'Gudang')
@section('subtitle', 'Manajemen Gudang')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.products.warehouses.create') }}" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-plus"></i>
    Tambah Gudang
  </a>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-backpack"></i>
    Kembali ke Produk
  </a>
  <a href="{{ route('admin.products.warehouses.create') }}" class="btn btn-primary d-sm-none">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Gudang</h3>
  </div>
  <div class="card-body">
    <!-- Filter Gudang -->
    <div class="mb-3">
      <div class="row g-3" id="filter-form">
        <div class="col-md-4">
          <div class="input-icon">
            <span class="input-icon-addon">
              <i class="ti ti-search"></i>
            </span>
            <input type="text" class="form-control" id="search" name="search" placeholder="Cari gudang..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="status" name="status">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="sort_by" name="sort_by">
            <option value="">Urutkan</option>
            <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
            <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Terlama</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" id="btn-filter" class="btn btn-primary w-100">
            <i class="ti ti-filter me-1"></i>
            Filter
          </button>
        </div>
      </div>
    </div>
    
    <div class="table-responsive" id="warehouse-table-container">
      <table class="table table-vcenter card-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Gudang</th>
            <th>Alamat</th>
            <th>Kota/Kecamatan</th>
            <th>Status</th>
            <th class="w-1">Aksi</th>
          </tr>
        </thead>
        <tbody id="warehouse-list">
          @foreach($warehouses as $warehouse)
          <tr class="warehouse-row">
            <td>{{ $warehouse->id }}</td>
            <td>{{ $warehouse->name }}</td>
            <td>{{ $warehouse->address }}</td>
            <td>
              @if($warehouse->subdistrict)
                {{ $warehouse->subdistrict->name }} - {{ $warehouse->district->name ?? '' }}, {{ $warehouse->province->name ?? '' }}
              @else
                -
              @endif
            </td>
            <td>
              @if($warehouse->is_active)
                <span class="badge bg-success text-white">Aktif</span>
              @else
                <span class="badge bg-danger text-white">Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="btn-list flex-nowrap">
                <a href="{{ route('admin.products.warehouses.edit', $warehouse->id) }}" class="btn btn-sm btn-warning btn-icon">
                  <i class="ti ti-edit"></i>
                </a>
                <div class="form-check form-switch d-inline-block ms-2">
                  <input class="form-check-input status-switch" type="checkbox" id="status-{{ $warehouse->id }}" 
                    data-id="{{ $warehouse->id }}" {{ $warehouse->is_active ? 'checked' : '' }}>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      
      <!-- Loading indicator -->
      <div class="text-center py-4 d-none" id="loadingIndicator">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat data gudang...</p>
      </div>
      
      <!-- Error message -->
      <div class="text-center py-4 d-none" id="errorContainer">
        <div class="empty">
          <div class="empty-icon">
            <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
          </div>
          <p class="empty-title">Terjadi kesalahan</p>
          <p class="empty-subtitle text-muted" id="errorMessage">
            Gagal memuat data gudang. Silakan coba lagi.
          </p>
          <div class="empty-action">
            <button class="btn btn-primary" id="retryButton">
              <i class="ti ti-refresh me-1"></i>
              Coba Lagi
            </button>
          </div>
        </div>
      </div>
      
      <!-- Empty state -->
      <div class="text-center py-4 d-none" id="emptyContainer">
        <div class="empty">
          <div class="empty-img">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-warehouse" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M3 21v-13l9 -4l9 4v13"></path>
              <path d="M13 13h4v8h-10v-6h6"></path>
              <path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3"></path>
            </svg>
          </div>
          <p class="empty-title">Tidak ada data gudang</p>
          <p class="empty-subtitle text-muted">
            Tambahkan gudang baru untuk mulai mengelola data gudang Anda.
          </p>
          <div class="empty-action">
            <a href="{{ route('admin.products.warehouses.create') }}" class="btn btn-primary">
              <i class="ti ti-plus me-1"></i>
              Tambah Gudang
            </a>
          </div>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
          <span id="paginationInfo" class="text-muted">
            Menampilkan {{ $warehouses->firstItem() ?? 0 }}-{{ $warehouses->lastItem() ?? 0 }} dari {{ $warehouses->total() ?? 0 }} gudang
            (Halaman {{ $warehouses->currentPage() ?? 0 }} dari {{ $warehouses->lastPage() ?? 0 }})
          </span>
        </div>
        <div class="d-flex align-items-center">
          <select class="form-select form-select-sm d-inline-block me-2" id="perPageSelect" style="width: auto;">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
          </select>
          {{ $warehouses->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Gudang -->
<div class="modal modal-blur fade" id="addWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addWarehouseModalLabel">Tambah Gudang Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addWarehouseForm">
          @csrf
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Nama Gudang</label>
              <input type="text" class="form-control" name="name" placeholder="Masukkan nama gudang" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kode Pos</label>
              <input type="text" class="form-control" name="postal_code" placeholder="Masukkan kode pos" maxlength="10">
            </div>
          </div>
{{--           
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Provinsi</label>
              <select class="form-select select2-add" name="province_id" id="add_province_id">
                <option value="">Pilih Provinsi</option>
                @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kabupaten/Kota</label>
              <select class="form-select select2-add" name="district_id" id="add_district_id" disabled>
                <option value="">Pilih Kabupaten/Kota</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kecamatan</label>
              <select class="form-select select2-add" name="subdistrict_id" id="add_subdistrict_id" disabled>
                <option value="">Pilih Kecamatan</option>
              </select>
            </div>
          </div> --}}
          
          <div class="mb-3">
            <label class="form-label required">Alamat Lengkap</label>
            <textarea class="form-control" name="address" rows="3" placeholder="Masukkan alamat lengkap gudang" required></textarea>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label required">Kota/Kecamatan</label>
              <input type="text" class="form-control" name="subdistrict_name" id="add_subdistrict_search" placeholder="Masukan Minimal 4 karakter untuk mencari" required>
              <input type="hidden" name="subdistrict_id" id="add_subdistrict_id">
              <input type="hidden" name="district_id" id="add_district_id">
              <input type="hidden" name="province_id" id="add_province_id">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Kode Pos</label>
              <select class="form-select" name="postal_code" id="add_postal_code_select" required>
                <option value="">Pilih Kode Pos</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" name="is_active">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveWarehouseBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Gudang -->
<div class="modal modal-blur fade" id="viewWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="viewWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewWarehouseModalLabel">Detail Gudang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">Informasi Gudang</h3>
                <div class="mb-2">
                  <strong>ID Gudang:</strong> <span id="view_warehouse_id"></span>
                </div>
                <div class="mb-2">
                  <strong>Nama Gudang:</strong> <span id="view_name"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">Alamat</h3>
                <div class="mb-2">
                  <strong>Alamat:</strong> <span id="view_address"></span>
                </div>
                <div class="mb-2">
                  <strong>Kota/Kecamatan:</strong> <span id="view_location"></span>
                </div>
                <div class="mb-2">
                  <strong>Kode Pos:</strong> <span id="view_postal_code"></span>
                </div>
                <div class="mb-2">
                  <strong>Status:</strong> <span id="view_status"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="card mt-3">
          <div class="card-header">
            <h3 class="card-title">Stok Produk di Gudang</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-vcenter">
                <thead>
                  <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Stok Tersedia</th>
                    <th>Rak</th>
                  </tr>
                </thead>
                <tbody id="view_stock">
                  <!-- Data produk akan diisi melalui AJAX -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary edit-from-view" data-bs-dismiss="modal">Edit Gudang</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Gudang -->
<div class="modal modal-blur fade" id="editWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editWarehouseModalLabel">Edit Gudang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editWarehouseForm">
          <input type="hidden" name="warehouse_id" id="edit_warehouse_id">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Nama Gudang</label>
              <input type="text" class="form-control" name="name" id="edit_name" placeholder="Masukkan nama gudang" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kode Pos</label>
              <input type="text" class="form-control" name="postal_code" id="edit_postal_code" placeholder="Masukkan kode pos" maxlength="10">
            </div>
          </div>
          
          {{-- <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Provinsi</label>
              <select class="form-select select2-edit" name="province_id" id="edit_province_id">
                <option value="">Pilih Provinsi</option>
                @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kabupaten/Kota</label>
              <select class="form-select select2-edit" name="district_id" id="edit_district_id">
                <option value="">Pilih Kabupaten/Kota</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kecamatan</label>
              <select class="form-select select2-edit" name="subdistrict_id" id="edit_subdistrict_id">
                <option value="">Pilih Kecamatan</option>
              </select>
            </div>
          </div> --}}
          
          <div class="mb-3">
            <label class="form-label required">Alamat Lengkap</label>
            <textarea class="form-control" name="address" id="edit_address" rows="3" placeholder="Masukkan alamat lengkap gudang" required></textarea>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label required">Kota/Kecamatan</label>
              <input type="text" class="form-control" name="subdistrict_name" id="edit_subdistrict_search" placeholder="Masukan Minimal 4 karakter untuk mencari" required>
              <input type="hidden" name="subdistrict_id" id="edit_subdistrict_id">
              <input type="hidden" name="district_id" id="edit_district_id">
              <input type="hidden" name="province_id" id="edit_province_id">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Kode Pos</label>
              <select class="form-select" name="postal_code" id="edit_postal_code_select" required>
                <option value="">Pilih Kode Pos</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" name="is_active" id="edit_is_active">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="updateWarehouseBtn">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<!-- Konfirmasi Hapus Gudang -->
<div class="modal modal-blur fade" id="deleteWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="deleteWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Apakah Anda yakin?</div>
        <div>Anda akan menghapus gudang ini. Tindakan ini tidak dapat dibatalkan.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" data-id="">Hapus Gudang</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  .modal-header {
    background-color: var(--tblr-primary);
    color: white;
  }
  
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
  
  .form-label.required:after {
    content: " *";
    color: red;
  }
  
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 9999 !important;
    background: white !important;
    background-color: white !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
    padding: 5px !important;
  }
  .ui-menu-item {
    margin-bottom: 5px !important;
  }
  .ui-menu-item:last-child {
    margin-bottom: 0 !important;
  }
  .ui-menu .ui-menu-item-wrapper {
    padding: 8px 10px !important;
    border-radius: 4px !important;
  }
  .ui-menu .ui-menu-item-wrapper.ui-state-active {
    background-color: var(--tblr-primary) !important;
    border-color: var(--tblr-primary) !important;
    color: white !important;
    margin: 0 !important;
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
  
  /* Styling untuk warehouse row */
  .warehouse-row {
    transition: background-color 0.2s;
  }
  
  .warehouse-row:hover {
    background-color: rgba(32, 107, 196, 0.03);
  }
  
  /* Styling untuk pagination */
  .pagination {
    margin-bottom: 0;
  }
  
  .pagination .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
    // Tampilkan notifikasi jika ada session flash message
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
      });
    @endif
    
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        timer: 3000,
        showConfirmButton: false
      });
    @endif
    
    // Variabel untuk menyimpan request AJAX yang sedang berjalan
    let currentRequest = null;
    
    // Fungsi untuk memuat data gudang
    function loadWarehouses(page = 1) {
      // Tampilkan loading indicator
      $('#warehouse-list').addClass('d-none');
      $('#loadingIndicator').removeClass('d-none');
      $('#errorContainer').addClass('d-none');
      $('#emptyContainer').addClass('d-none');
      
      // Ambil parameter filter
      const search = $('#search').val();
      const status = $('#status').val();
      const sortBy = $('#sort_by').val();
      const perPage = $('#perPageSelect').val();
      
      // Batalkan request sebelumnya jika ada
      if (currentRequest) {
        currentRequest.abort();
      }
      
      // Kirim request AJAX
      currentRequest = $.ajax({
        url: "{{ route('admin.products.warehouses.index') }}",
        type: 'GET',
        data: {
          page: page,
          search: search,
          status: status,
          sort_by: sortBy,
          per_page: perPage
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            // Update URL dengan parameter filter
            const url = new URL(window.location);
            url.searchParams.set('page', page);
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');
            if (sortBy) url.searchParams.set('sort_by', sortBy);
            else url.searchParams.delete('sort_by');
            if (perPage) url.searchParams.set('per_page', perPage);
            else url.searchParams.delete('per_page');
            window.history.pushState({}, '', url);
            
            // Update tabel
            $('#warehouse-list').html(response.html);
            
            // Tampilkan tabel jika ada data
            if (response.html.trim()) {
              $('#warehouse-list').removeClass('d-none');
              $('#emptyContainer').addClass('d-none');
            } else {
              $('#emptyContainer').removeClass('d-none');
            }
            
            // Update pagination
            $('.d-flex.justify-content-between.align-items-center.mt-4').html(response.pagination);
          } else {
            // Tampilkan pesan error
            $('#errorContainer').removeClass('d-none');
            $('#errorMessage').text(response.message || 'Terjadi kesalahan saat memuat data.');
          }
        },
        error: function(xhr, status, error) {
          // Tampilkan pesan error
          $('#errorContainer').removeClass('d-none');
          $('#errorMessage').text('Terjadi kesalahan saat memuat data: ' + error);
        },
        complete: function() {
          // Sembunyikan loading indicator
          $('#loadingIndicator').addClass('d-none');
          currentRequest = null;
        }
      });
    }
    
    // Event handler untuk tombol filter
    $('#btn-filter').on('click', function() {
      loadWarehouses(1);
    });
    
    // Event handler untuk perubahan jumlah item per halaman
    $('#perPageSelect').on('change', function() {
      loadWarehouses(1);
    });
    
    // Event handler untuk pencarian dengan enter key
    $('#search').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        loadWarehouses(1);
      }
    });
    
    // Event handler untuk pagination
    $(document).on('click', '.pagination .page-link', function(e) {
      e.preventDefault();
      const href = $(this).attr('href');
      if (href) {
        const url = new URL(href);
        const page = url.searchParams.get('page') || 1;
        loadWarehouses(page);
      }
    });
    
    // Event handler untuk tombol retry
    $('#retryButton').on('click', function() {
      loadWarehouses($('#currentPage').text() || 1);
    });
    
    // Token CSRF untuk AJAX
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });
      
      Toast.fire({
        icon: type,
        title: message
      });
      
      $('.select2-edit').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#editWarehouseModal'),
        placeholder: function() {
          return $(this).find('option:first').text();
        },
        allowClear: true
      });
    }
    
    // Load districts based on province
    function loadDistricts(provinceId, targetSelect, selectedValue = null) {
      if (!provinceId) {
        $(targetSelect).html('<option value="">Pilih Kabupaten/Kota</option>').prop('disabled', true).trigger('change');
        return;
      }
      
      $.ajax({
        url: "{{ url('admin/warehouses/districts') }}/" + provinceId,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
          if (response.status === 'success') {
            const districts = response.data;
            let options = '<option value="">Pilih Kabupaten/Kota</option>';
            
            districts.forEach(function(district) {
              const selected = selectedValue && selectedValue == district.id ? 'selected' : '';
              options += `<option value="${district.id}" ${selected}>${district.name}</option>`;
            });
            
            $(targetSelect).html(options).prop('disabled', false).trigger('change');
          }
        },
        error: function() {
          showNotification('Gagal memuat data kabupaten/kota', 'error');
        }
      });
    }
    
    // Load subdistricts based on district
    function loadSubdistricts(districtId, targetSelect, selectedValue = null) {
      if (!districtId) {
        $(targetSelect).html('<option value="">Pilih Kecamatan</option>').prop('disabled', true).trigger('change');
        return;
      }
      
      $.ajax({
        url: "{{ url('admin/warehouses/subdistricts') }}/" + districtId,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
          if (response.status === 'success') {
            const subdistricts = response.data;
            let options = '<option value="">Pilih Kecamatan</option>';
            
            subdistricts.forEach(function(subdistrict) {
              const selected = selectedValue && selectedValue == subdistrict.id ? 'selected' : '';
              options += `<option value="${subdistrict.id}" ${selected}>${subdistrict.name}</option>`;
            });
            
            $(targetSelect).html(options).prop('disabled', false).trigger('change');
          }
        },
        error: function() {
          showNotification('Gagal memuat data kecamatan', 'error');
        }
      });
    }
    
    
    // Event handlers for location changes - Add Form
    $('#add_province_id').on('change', function() {
      const provinceId = $(this).val();
      loadDistricts(provinceId, '#add_district_id');
      // Reset subdistrict when province changes
      $('#add_subdistrict_id').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true).trigger('change');
    });
    
    $('#add_district_id').on('change', function() {
      const districtId = $(this).val();
      loadSubdistricts(districtId, '#add_subdistrict_id');
    });
    
    // Event handlers for location changes - Edit Form
    $('#edit_province_id').on('change', function() {
      const provinceId = $(this).val();
      loadDistricts(provinceId, '#edit_district_id');
      // Reset subdistrict when province changes
      $('#edit_subdistrict_id').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true).trigger('change');
    });
    
    $('#edit_district_id').on('change', function() {
      const districtId = $(this).val();
      loadSubdistricts(districtId, '#edit_subdistrict_id');
    });
    
    // Reset form function
    function resetForm(formId) {
      $(formId).trigger('reset');
      // Reset select2 dropdowns
      $(formId).find('.select2-add').val('').trigger('change');
      // Disable district and subdistrict
      $('#add_district_id, #add_subdistrict_id').prop('disabled', true);
    }
    
    // Inisialisasi autocomplete untuk pencarian subdistrict pada form tambah
    $('#add_subdistrict_search').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: "{{ route('admin.subdistricts.search') }}",
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {
            response($.map(data, function(item) {
              return {
                label: item.name + ' - ' + item.district.name + ', ' + item.province.name,
                value: item.name + ' - ' + item.district.name + ', ' + item.province.name,
                id: item.id,
                district_id: item.district_id,
                province_id: item.province_id,
                subdistrict: item
              };
            }));
          }
        });
      },
      minLength: 4,
      select: function(event, ui) {
        $('#add_subdistrict_id').val(ui.item.id);
        $('#add_district_id').val(ui.item.district_id);
        $('#add_province_id').val(ui.item.province_id);
        
        // Load postal codes for the selected subdistrict
        loadPostalCodes(ui.item.id, 'add');
        
        return true;
      }
    });
    
    // Inisialisasi autocomplete untuk pencarian subdistrict pada form edit
    $('#edit_subdistrict_search').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: "{{ route('admin.subdistricts.search') }}",
          dataType: "json",
          data: {
            search: request.term
          },
          success: function(data) {
            response($.map(data, function(item) {
              return {
                label: item.name + ' - ' + item.district.name + ', ' + item.province.name,
                value: item.name + ' - ' + item.district.name + ', ' + item.province.name,
                id: item.id,
                district_id: item.district_id,
                province_id: item.province_id,
                subdistrict: item
              };
            }));
          }
        });
      },
      minLength: 4,
      select: function(event, ui) {
        $('#edit_subdistrict_id').val(ui.item.id);
        $('#edit_district_id').val(ui.item.district_id);
        $('#edit_province_id').val(ui.item.province_id);
        
        // Load postal codes for the selected subdistrict
        loadPostalCodes(ui.item.id, 'edit');
        
        return true;
      }
    });
    
    // Function to load postal codes for a subdistrict
    function loadPostalCodes(subdistrictId, formType) {
      $.ajax({
        url: "{{ route('admin.subdistricts.postal-codes') }}",
        dataType: "json",
        data: {
          subdistrict_id: subdistrictId
        },
        success: function(data) {
          const selectId = formType === 'add' ? '#add_postal_code_select' : '#edit_postal_code_select';
          
          // Clear existing options
          $(selectId).empty();
          $(selectId).append('<option value="">Pilih Kode Pos</option>');
          
          // Add new options
          if (data && data.length > 0) {
            $.each(data, function(index, postalCode) {
              $(selectId).append('<option value="' + postalCode + '">' + postalCode + '</option>');
            });
          }
        }
      });
    }
    
    // Tambah Gudang
    $('#saveWarehouseBtn').on('click', function() {
      const form = $('#addWarehouseForm');
      
      // Validasi form di sisi client
      if (!form[0].checkValidity()) {
        form[0].reportValidity();
        return;
      }
      
      // Ambil data form
      const formData = form.serialize();
      
      // Kirim data ke server
      $.ajax({
        url: "{{ route('admin.products.warehouses.store') }}",
        type: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
          $('#saveWarehouseBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i> Menyimpan...');
        },
        success: function(response) {
          if (response.status === 'success') {
            // Tutup modal
            $('#addWarehouseModal').modal('hide');
            
            // Reset form
            resetForm('#addWarehouseForm');
            
            // Tampilkan notifikasi
            showNotification(response.message);
            
            // Reload halaman untuk memperbarui data
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            showNotification(response.message, 'error');
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            // Error validasi
            showValidationErrors(xhr.responseJSON.errors);
          } else {
            showNotification('Terjadi kesalahan pada server', 'error');
          }
        },
        complete: function() {
          $('#saveWarehouseBtn').prop('disabled', false).html('Simpan');
        }
      });
    });
    
    // Lihat Detail Gudang
    $(document).on('click', '.view-warehouse', function() {
      const warehouseId = $(this).data('id');
      
      // Ambil data gudang dari server
      $.ajax({
        url: "{{ url('admin/products/warehouses') }}/" + warehouseId,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
          // Reset data modal
          $('#view_warehouse_id, #view_name, #view_address, #view_location, #view_postal_code, #view_status').text('Memuat...');
          $('#view_stock').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');
        },
        success: function(response) {
          if (response.status === 'success') {
            const warehouse = response.data;
            
            // Isi data gudang
            $('#view_warehouse_id').text(warehouse.id);
            $('#view_name').text(warehouse.name);
            $('#view_address').text(warehouse.address);
            
            // Isi data lokasi
            let locationText = '-';
            if (warehouse.subdistrict) {
              locationText = warehouse.subdistrict.name;
              if (warehouse.district) {
                locationText += ' - ' + warehouse.district.name;
                if (warehouse.province) {
                  locationText += ', ' + warehouse.province.name;
                }
              }
            }
            $('#view_location').text(locationText);
            
            // Isi kode pos
            $('#view_postal_code').text(warehouse.postal_code || '-');
            
            // Isi status
            const statusText = warehouse.is_active ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-danger text-white">Nonaktif</span>';
            $('#view_status').html(statusText);
            
            // Isi data produk
            if (warehouse.product_variations && warehouse.product_variations.length > 0) {
              let stockHtml = '';
              $.each(warehouse.product_variations, function(index, product) {
                stockHtml += `
                  <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.pivot.stock}</td>
                    <td>${product.pivot.rack || '-'}</td>
                  </tr>
                `;
              });
              $('#view_stock').html(stockHtml);
            } else {
              $('#view_stock').html('<tr><td colspan="4" class="text-center">Tidak ada data produk</td></tr>');
            }
            
            // Simpan ID untuk tombol edit
            $('.edit-from-view').data('id', warehouse.id);
          } else {
            showNotification(response.message, 'error');
          }
        },
        error: function() {
          showNotification('Gagal memuat data gudang', 'error');
        }
      });
    });
    
    // Persiapkan Edit Gudang
    $(document).on('click', '.edit-warehouse, .edit-from-view', function() {
      const warehouseId = $(this).data('id');
      
      // Jika tombol edit dari modal detail
      if ($(this).hasClass('edit-from-view')) {
        $('#viewWarehouseModal').modal('hide');
        setTimeout(function() {
          $('#editWarehouseModal').modal('show');
        }, 500);
      }
      
      // Ambil data gudang dari server
      $.ajax({
        url: "{{ url('admin/products/warehouses') }}/" + warehouseId,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
          // Reset form
          $('#edit_warehouse_id').val('');
          $('#edit_name, #edit_address, #edit_subdistrict_search').val('Memuat...');
          $('#edit_subdistrict_id, #edit_district_id, #edit_province_id').val('');
          $('#edit_postal_code_select').empty().append('<option value="">Memuat...</option>');
          $('#edit_is_active').val('1');
        },
        success: function(response) {
          if (response.status === 'success') {
            const warehouse = response.data;
            
            // Isi form edit
            $('#edit_warehouse_id').val(warehouse.id);
            $('#edit_name').val(warehouse.name);
            $('#edit_address').val(warehouse.address);
            
            // Isi data lokasi
            $('#edit_subdistrict_id').val(warehouse.subdistrict_id || '');
            $('#edit_district_id').val(warehouse.district_id || '');
            $('#edit_province_id').val(warehouse.province_id || '');
            
            // Isi subdistrict name
            let subdistrictName = '';
            if (warehouse.subdistrict) {
              subdistrictName = warehouse.subdistrict.name;
              if (warehouse.district) {
                subdistrictName += ' - ' + warehouse.district.name;
                if (warehouse.province) {
                  subdistrictName += ', ' + warehouse.province.name;
                }
              }
            }
            $('#edit_subdistrict_search').val(subdistrictName);
            
            // Load postal codes
            if (warehouse.subdistrict_id) {
              loadPostalCodes(warehouse.subdistrict_id, 'edit');
              
              // Set selected postal code after a short delay to ensure options are loaded
              setTimeout(function() {
                $('#edit_postal_code_select').val(warehouse.postal_code || '');
              }, 500);
            }
            
            // Set status
            $('#edit_is_active').val(warehouse.is_active ? '1' : '0');
          } else {
            showNotification(response.message, 'error');
          }
        },
        error: function() {
          showNotification('Gagal memuat data gudang', 'error');
        }
      });
    });
    
    // Update Gudang
    $('#updateWarehouseBtn').on('click', function() {
      const form = $('#editWarehouseForm');
      const warehouseId = $('#edit_warehouse_id').val();
      
      // Validasi form di sisi client
      if (!form[0].checkValidity()) {
        form[0].reportValidity();
        return;
      }
      
      // Ambil data form
      const formData = form.serialize();
      
      // Kirim data ke server
      $.ajax({
        url: "{{ url('admin/products/warehouses') }}/" + warehouseId,
        type: 'PUT',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
          $('#updateWarehouseBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i> Menyimpan...');
        },
        success: function(response) {
          if (response.status === 'success') {
            // Tutup modal
            $('#editWarehouseModal').modal('hide');
            
            // Tampilkan notifikasi
            showNotification(response.message);
            
            // Reload halaman untuk memperbarui data
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            showNotification(response.message, 'error');
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            // Error validasi
            showValidationErrors(xhr.responseJSON.errors);
          } else {
            showNotification('Terjadi kesalahan pada server', 'error');
          }
        },
        complete: function() {
          $('#updateWarehouseBtn').prop('disabled', false).html('Simpan Perubahan');
        }
      });
    });
    
    // Hapus Gudang
    $(document).on('click', '.delete-warehouse', function() {
      const warehouseId = $(this).data('id');
      
      // Set ID gudang yang akan dihapus
      $('#confirmDeleteBtn').data('id', warehouseId);
      
      // Tampilkan modal konfirmasi
      $('#deleteWarehouseModal').modal('show');
    });
    
    // Konfirmasi Hapus Gudang
    $('#confirmDeleteBtn').on('click', function() {
      const warehouseId = $(this).data('id');
      
      // Kirim permintaan hapus ke server
      $.ajax({
        url: "{{ url('admin/products/warehouses') }}/" + warehouseId,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        beforeSend: function() {
          $('#confirmDeleteBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i> Menghapus...');
        },
        success: function(response) {
          if (response.status === 'success') {
            // Tutup modal
            $('#deleteWarehouseModal').modal('hide');
            
            // Tampilkan notifikasi
            showNotification(response.message);
            
            // Reload halaman untuk memperbarui data
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            showNotification(response.message, 'error');
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            showNotification(xhr.responseJSON.message, 'error');
          } else {
            showNotification('Gagal menghapus gudang', 'error');
          }
        },
        complete: function() {
          $('#confirmDeleteBtn').prop('disabled', false).html('Hapus Gudang');
        }
      });
    });
    
    // Inisialisasi halaman
    // Jika ada data di tabel, sembunyikan empty state
    if ($('#warehouse-list tr').length > 0) {
      $('#emptyContainer').addClass('d-none');
    } else {
      $('#emptyContainer').removeClass('d-none');
    }
    
    // Toggle Status Gudang
    $(document).on('change', '.status-switch', function() {
      const checkbox = $(this);
      const warehouseId = checkbox.data('id');
      const warehouseName = checkbox.closest('tr').find('td:nth-child(2)').text();
      const newStatus = checkbox.prop('checked') ? 1 : 0;
      const statusText = newStatus === 1 ? 'mengaktifkan' : 'menonaktifkan';
      const resultText = newStatus === 1 ? 'diaktifkan' : 'dinonaktifkan';
      
      // Kembalikan ke status sebelumnya sampai konfirmasi
      checkbox.prop('checked', !checkbox.prop('checked'));
      
      Swal.fire({
        title: 'Konfirmasi Perubahan Status',
        text: `Apakah Anda yakin ingin ${statusText} gudang "${warehouseName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah Status!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // AJAX request untuk mengubah status
          $.ajax({
            url: "{{ url('admin/products/warehouses') }}/" + warehouseId + "/toggle-status",
            type: 'PATCH',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              status: newStatus
            },
            beforeSend: function() {
              // Tampilkan loading
              Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengubah status gudang',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });
            },
            success: function(response) {
              if (response.status === 'success') {
                // Update UI
                checkbox.prop('checked', newStatus === 1);
                
                // Update status di UI
                const statusCell = checkbox.closest('tr').find('td:nth-child(5)');
                statusCell.html('<span class="badge bg-' + (newStatus === 1 ? 'success' : 'danger') + ' text-white">' + (newStatus === 1 ? 'Aktif' : 'Nonaktif') + '</span>');
                
                // Tampilkan pesan sukses
                Swal.fire({
                  title: 'Berhasil!',
                  text: `Gudang "${warehouseName}" telah ${resultText}`,
                  icon: 'success',
                  confirmButtonText: 'OK'
                });
              } else {
                // Kembalikan ke status sebelumnya jika gagal
                checkbox.prop('checked', !newStatus === 1);
                
                // Tampilkan pesan error
                Swal.fire({
                  title: 'Gagal!',
                  text: response.message || 'Terjadi kesalahan saat mengubah status gudang',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function(xhr, status, error) {
              // Kembalikan ke status sebelumnya jika gagal
              checkbox.prop('checked', !newStatus === 1);
              
              // Tampilkan pesan error
              Swal.fire({
                title: 'Gagal!',
                text: `Terjadi kesalahan saat mengubah status gudang: ${error}`,
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        }
      });
    });
    
    // Event handler untuk modal Detail Gudang
    $('#viewWarehouseModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const warehouseId = button.data('id');
      
      // Dalam implementasi nyata, di sini akan ada AJAX request untuk mengambil data gudang
      // Untuk contoh ini, kita gunakan data dummy
      
      // Simulasi data yang diambil dari server
      const warehouseData = {
        id: 'WH-001',
        name: 'Gudang Jakarta',
        code: 'JKT',
        address: 'Jl. Raya Pasar Minggu No. 123, Jakarta Selatan',
        city: 'Jakarta Selatan',
        postal_code: '12345',
        phone: '021-12345678',
        email: 'gudang.jakarta@pindonoutdoor.com',
        manager: 'Budi Santoso',
        status: 'active',
        notes: 'Gudang utama untuk wilayah Jakarta dan sekitarnya. Memiliki fasilitas penyimpanan khusus untuk barang elektronik dan peralatan sensitif terhadap kelembaban.'
      };
      
      // Mengisi data ke dalam modal
      $('#view_warehouse_id').text(warehouseData.id);
      $('#view_name').text(warehouseData.name);
      $('#view_code').text(warehouseData.code);
      $('#view_address').text(warehouseData.address);
      $('#view_city').text(warehouseData.city);
      $('#view_postal_code').text(warehouseData.postal_code);
      $('#view_phone').text(warehouseData.phone);
      $('#view_email').text(warehouseData.email);
      $('#view_manager').text(warehouseData.manager);
      $('#view_notes').text(warehouseData.notes);
      
      // Set status badge
      let statusBadge = '';
      if (warehouseData.status === 'active') {
        statusBadge = '<span class="badge bg-success">Aktif</span>';
      } else {
        statusBadge = '<span class="badge bg-danger">Nonaktif</span>';
      }
      $('#view_status').html(statusBadge);
    });
    
    // Event handler untuk modal Edit Gudang
    $('#editWarehouseModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const warehouseId = button.data('id');
      
      // Dalam implementasi nyata, di sini akan ada AJAX request untuk mengambil data gudang
      // Untuk contoh ini, kita gunakan data dummy
      
      // Simulasi data yang diambil dari server
      const warehouseData = {
        id: 'WH-001',
        name: 'Gudang Jakarta',
        code: 'JKT',
        address: 'Jl. Raya Pasar Minggu No. 123, Jakarta Selatan',
        city: 'Jakarta Selatan',
        postal_code: '12345',
        phone: '021-12345678',
        email: 'gudang.jakarta@pindonoutdoor.com',
        manager: 'Budi Santoso',
        status: 'active',
        notes: 'Gudang utama untuk wilayah Jakarta dan sekitarnya. Memiliki fasilitas penyimpanan khusus untuk barang elektronik dan peralatan sensitif terhadap kelembaban.'
      };
      
      // Mengisi data ke dalam form edit
      $('#edit_warehouse_id').val(warehouseData.id);
      $('#edit_name').val(warehouseData.name);
      $('#edit_code').val(warehouseData.code);
      $('#edit_address').val(warehouseData.address);
      $('#edit_city').val(warehouseData.city);
      $('#edit_postal_code').val(warehouseData.postal_code);
      $('#edit_phone').val(warehouseData.phone);
      $('#edit_email').val(warehouseData.email);
      $('#edit_manager').val(warehouseData.manager);
      $('#edit_status').val(warehouseData.status);
      $('#edit_notes').val(warehouseData.notes);
    });
  });
</script>
@endsection