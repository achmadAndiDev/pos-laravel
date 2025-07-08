@extends('admin.layouts.app')

@section('title', 'Customer')
@section('subtitle', 'Manajemen Data Customer')

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 9999 !important;
  }
  
  /* Additional styles for customer management */
  .customer-row {
    transition: background-color 0.2s;
  }
  
  .customer-row:hover {
    background-color: rgba(32, 107, 196, 0.03);
  }
  
  .pagination {
    margin-bottom: 0;
  }
  
  .pagination .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
</style>
@endpush

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-plus"></i>
    Tambah Customer
  </a>
  <a href="{{ route('admin.customers.create') }}" class="btn btn-primary d-sm-none">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Customer</h3>
  </div>
  <div class="card-body">
    <!-- Filter Customer -->
    <div class="mb-3">
      <div class="row g-3" id="filter-form">
        <div class="col-md-3">
          <div class="input-icon">
            <span class="input-icon-addon">
              <i class="ti ti-search"></i>
            </span>
            <input type="text" class="form-control" id="search" name="search" placeholder="Cari customer..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="category" name="category_id">
            <option value="">Semua Kategori</option>
            <!-- Kategori akan diisi melalui AJAX -->
          </select>
        </div>
        <div class="col-md-2">
          <select class="form-select" id="status" name="status">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </div>
        <div class="col-md-2">
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
    
    <div class="table-responsive" id="customer-table-container">
      <table class="table table-vcenter card-table">
        <thead>
          <tr>
            <th>Customer</th>
            <th>Kontak</th>
            <th>Alamat</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Terdaftar</th>
            <th>Order</th>
            <th class="w-1">Aksi</th>
          </tr>
        </thead>
        <tbody id="customer-list">
          <!-- Data will be loaded via AJAX -->
        </tbody>
      </table>
      
      <!-- Loading indicator -->
      <div class="text-center py-4 d-none" id="loadingIndicator">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat data customer...</p>
      </div>
      
      <!-- Error message -->
      <div class="text-center py-4 d-none" id="errorContainer">
        <div class="empty">
          <div class="empty-icon">
            <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
          </div>
          <p class="empty-title">Terjadi kesalahan</p>
          <p class="empty-subtitle text-muted" id="errorMessage">
            Gagal memuat data customer. Silakan coba lagi.
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
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
              <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
            </svg>
          </div>
          <p class="empty-title">Tidak ada data customer</p>
          <p class="empty-subtitle text-muted">
            Tambahkan customer baru untuk mulai mengelola data pelanggan Anda.
          </p>
          <div class="empty-action">
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
              <i class="ti ti-plus me-1"></i>
              Tambah Customer
            </a>
          </div>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
          <span id="paginationInfo" class="text-muted">
            Menampilkan <span id="fromItem">0</span>-<span id="toItem">0</span> dari <span id="totalItems">0</span> customer
            (Halaman <span id="currentPage">0</span> dari <span id="totalPages">0</span>)
          </span>
        </div>
        <div class="d-flex align-items-center">
          <select class="form-select form-select-sm d-inline-block me-2" id="perPageSelect" style="width: auto;">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          <ul class="pagination m-0" id="pagination"></ul>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Modal Edit Customer -->
<div class="modal modal-blur fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCustomerForm">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="customer_id" id="edit_customer_id">
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Nama Customer</label>
              <input type="text" class="form-control" name="name" id="edit_name" required>
              <div class="invalid-feedback edit-name-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Nomor Telepon</label>
              <div class="input-group">
                <input type="text" class="form-control" name="phone" id="edit_phone" required>
                <button type="button" class="btn btn-success" id="whatsapp_button" data-bs-toggle="tooltip" title="Kirim WhatsApp">
                  <i class="ti ti-brand-whatsapp"></i>
                </button>
                <div class="invalid-feedback edit-phone-error"></div>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="edit_email">
              <div class="invalid-feedback edit-email-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" id="edit_password" placeholder="Kosongkan jika tidak ingin mengubah password">
              <div class="invalid-feedback edit-password-error"></div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">ID Line</label>
              <input type="text" class="form-control" name="line" id="edit_line">
              <div class="invalid-feedback edit-line-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Konfirmasi Password</label>
              <input type="password" class="form-control" name="password_confirmation" id="edit_password_confirmation" placeholder="Kosongkan jika tidak ingin mengubah password">
              <div class="invalid-feedback edit-password_confirmation-error"></div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
              <div class="invalid-feedback edit-address-error"></div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Kota/Kecamatan</label>
              <input type="text" class="form-control" name="district_name" id="edit_district_search">
              <input type="hidden" name="district_id" id="edit_district_id">
              <div class="invalid-feedback edit-district_id-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Kategori Customer</label>
              <select class="form-select" name="customer_category_id" id="edit_customer_category_id">
                <option value="">Pilih Kategori</option>
              </select>
              <div class="invalid-feedback edit-customer_category_id-error"></div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Kode Pos</label>
              <input type="text" class="form-control" name="postal_code" id="edit_postal_code">
              <div class="invalid-feedback edit-postal_code-error"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Status Akun</label>
              <select class="form-select" name="account_status" id="edit_account_status">
                <option value="verified">Terverifikasi</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="rejected">Ditolak</option>
              </select>
              <div class="invalid-feedback edit-account_status-error"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Status</label>
              <select class="form-select" name="is_active" id="edit_is_active">
                <option value="Y">Aktif</option>
                <option value="N">Nonaktif</option>
              </select>
              <div class="invalid-feedback edit-is_active-error"></div>
            </div>
          </div>
          <!-- Private Order field hidden -->
          <input type="hidden" name="is_private_order" id="edit_is_private_order" value="0">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary ms-auto">
            <i class="ti ti-device-floppy me-1"></i>
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Customer Row Template (will be cloned by JS) -->
<template id="customerRowTemplate">
  <tr class="customer-row">
    <td>
      <div class="d-flex align-items-center">
        <span class="avatar me-2 customer-avatar"></span>
        <div>
          <div class="font-weight-medium customer-name"></div>
          <div class="text-muted customer-id"></div>
        </div>
      </div>
    </td>
    <td>
      <div class="customer-phone"></div>
      <div class="text-muted customer-email"></div>
      <div class="text-muted customer-line"></div>
    </td>
    <td>
      <div class="customer-address"></div>
      <div class="text-muted customer-region"></div>
      <div class="text-muted customer-postal"></div>
    </td>
    <td>
      <span class="badge bg-azure-lt customer-category"></span>
    </td>
    <td>
      <span class="badge customer-status"></span>
    </td>
    <td>
      <div class="customer-created"></div>
      <div class="text-muted customer-last-order"></div>
    </td>
    <td class="customer-orders-count"></td>
    <td>
      <div class="btn-list flex-nowrap">
        <a href="#" class="btn btn-sm btn-warning edit-customer">
          <i class="ti ti-edit"></i>
        </a>
        <button class="btn btn-sm btn-danger delete-customer">
          <i class="ti ti-trash"></i>
        </button>
      </div>
    </td>
  </tr>
</template>

<!-- Modal Detail Customer -->
<div class="modal modal-blur fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-12 text-center mb-3">
            <span class="avatar avatar-xl mb-2 view-avatar"></span>
            <h3 class="view-name mb-0"></h3>
            <div class="text-muted view-id"></div>
            <div class="mt-2">
              <span class="badge view-status"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Informasi Kontak</h3>
              </div>
              <div class="card-body">
                <div class="mb-2">
                  <strong>Telepon:</strong>
                  <div class="view-phone"></div>
                </div>
                <div class="mb-2">
                  <strong>Email:</strong>
                  <div class="view-email"></div>
                </div>
                <div class="mb-2">
                  <strong>ID Line:</strong>
                  <div class="view-line"></div>
                </div>
                <div class="mb-2">
                  <strong>Kategori:</strong>
                  <div class="view-category"></div>
                </div>
                <div class="mb-2">
                  <strong>Private Order:</strong>
                  <div class="view-private-order"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Alamat</h3>
              </div>
              <div class="card-body">
                <div class="mb-2">
                  <strong>Alamat Lengkap:</strong>
                  <div class="view-address"></div>
                </div>
                <div class="mb-2">
                  <strong>Provinsi:</strong>
                  <div class="view-province"></div>
                </div>
                <div class="mb-2">
                  <strong>Kabupaten/Kota:</strong>
                  <div class="view-district"></div>
                </div>
                <div class="mb-2">
                  <strong>Kecamatan:</strong>
                  <div class="view-subdistrict"></div>
                </div>
                <div class="mb-2">
                  <strong>Kode Pos:</strong>
                  <div class="view-postal-code"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Informasi Tambahan</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <strong>Terdaftar:</strong>
                    <div class="view-created-at"></div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <strong>Terakhir Diupdate:</strong>
                    <div class="view-updated-at"></div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <strong>Terakhir Order:</strong>
                    <div class="view-last-order"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
          Tutup
        </button>
        <button type="button" class="btn btn-warning edit-from-view">
          <i class="ti ti-edit me-1"></i>
          Edit
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
    // Variables for pagination and filtering
    let currentPage = 1;
    let perPage = 10;
    let totalPages = 0;
    let currentFilters = {};

    // Load initial data
    loadCustomers();
        
    // Filter button click
    $('#btn-filter').on('click', function() {
      applyFilters();
    });
    
    // Filter on enter key in search field
    $('#search').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        applyFilters();
      }
    });
    
    // Per page change
    $('#perPageSelect').on('change', function() {
      perPage = $(this).val();
      currentPage = 1;
      loadCustomers();
    });
    
    // Pagination click
    $(document).on('click', '.pagination a', function(e) {
      e.preventDefault();
      const page = $(this).data('page');
      if (page) {
        currentPage = page;
        loadCustomers();
      }
    });    
        
    // Delete Customer with SweetAlert2
    $(document).on('click', '.delete-customer', function() {
      const customerId = $(this).data('id');
      
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data customer akan dihapus dan tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('admin/customers') }}/" + customerId,
            type: "DELETE",
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response) {
              if (response.success) {
                Swal.fire(
                  'Terhapus!',
                  response.message,
                  'success'
                );
                loadCustomers();
              }
            },
            error: function() {
              Swal.fire(
                'Gagal!',
                'Gagal menghapus customer.',
                'error'
              );
            }
          });
        }
      });
    });

    // Edit customer modal
    $(document).on('click', '.edit-customer', function(e) {
      e.preventDefault();
      const customerId = $(this).data('id');
      
      $.ajax({
        url: "{{ url('admin/customers') }}/" + customerId + "/edit",
        type: "GET",
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
          if (response.success) {
            const customer = response.data;
            
            // Fill edit form
            $('#edit_customer_id').val(customer.id);
            $('#edit_name').val(customer.name);
            $('#edit_phone').val(customer.phone);
            $('#edit_email').val(customer.email);
            $('#edit_address').val(customer.address);
            $('#edit_birth_date').val(customer.birth_date);
            $('#edit_gender').val(customer.gender);
            $('#edit_status').val(customer.status);
            $('#edit_total_points').val(customer.total_points);
            $('#edit_notes').val(customer.notes);
            
            // Show modal
            $('#editCustomerModal').modal('show');
          }
        },
        error: function() {
          Swal.fire('Error', 'Gagal memuat data customer', 'error');
        }
      });
    });

    // Submit edit form
    $('#editCustomerForm').on('submit', function(e) {
      e.preventDefault();
      
      const customerId = $('#edit_customer_id').val();
      const formData = new FormData(this);
      
      $.ajax({
        url: "{{ url('admin/customers') }}/" + customerId,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
          if (response.success) {
            $('#editCustomerModal').modal('hide');
            Swal.fire('Berhasil!', response.message, 'success');
            loadCustomers();
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            // Clear previous errors
            $('.invalid-feedback').text('');
            $('.form-control, .form-select').removeClass('is-invalid');
            
            // Show new errors
            Object.keys(errors).forEach(function(key) {
              $('#edit_' + key).addClass('is-invalid');
              $('.edit-' + key + '-error').text(errors[key][0]);
            });
          } else {
            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
          }
        }
      });
    });
        
    /**
     * Apply current filters
     */
    function applyFilters() {
      currentFilters = {
        search: $('#search').val(),
        status: $('#status').val(),
        sort_by: $('#sort_by').val()
      };
      currentPage = 1;
      loadCustomers();
    }
    
    /**
     * Load customers with current filters and pagination
     */
    function loadCustomers() {
      $('#errorContainer').addClass('d-none');
      $('#emptyContainer').addClass('d-none');
      
      showLoadingIndicator();
      
      const params = {
        page: currentPage,
        per_page: perPage,
        ...currentFilters
      };
      
      $.ajax({
        url: "{{ route('admin.customers.index') }}",
        type: "GET",
        data: params,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
          hideLoadingIndicator();
          
          if (response.success && response.data && response.data.length > 0) {
            renderCustomers(response.data);
            updatePagination(response.pagination);
          } else {
            showEmptyState();
          }
        },
        error: function(xhr) {
          hideLoadingIndicator();
          $('#errorMessage').text('Gagal memuat data customer. Silakan coba lagi.');
          $('#errorContainer').removeClass('d-none');
          console.error('Error loading customers:', xhr);
        }
      });
    }
    
    /**
     * Render customers in the table
     */
    function renderCustomers(customers) {
      $('#customer-list').empty();
      
      customers.forEach(function(customer) {
        const template = document.getElementById('customerRowTemplate');
        const customerRow = document.importNode(template.content, true).querySelector('tr');
        
        // Set customer data
        customerRow.querySelector('.customer-name').textContent = customer.name;
        customerRow.querySelector('.customer-id').textContent = customer.code || customer.id;
        
        // Set avatar
        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(customer.name)}&background=1a73e8&color=fff`;
        customerRow.querySelector('.customer-avatar').style.backgroundImage = `url(${avatarUrl})`;
        
        // Contact info
        customerRow.querySelector('.customer-phone').textContent = customer.phone || '-';
        customerRow.querySelector('.customer-email').textContent = customer.email || '-';
        customerRow.querySelector('.customer-line').textContent = '';
        
        // Address info
        customerRow.querySelector('.customer-address').textContent = customer.address || '-';
        customerRow.querySelector('.customer-region').textContent = '';
        customerRow.querySelector('.customer-postal').textContent = '';
        
        // Category
        customerRow.querySelector('.customer-category').textContent = 'Umum';
        
        // Status
        const statusBadge = customerRow.querySelector('.customer-status');
        if (customer.status === 'active') {
          statusBadge.classList.add('bg-success');
          statusBadge.textContent = 'Aktif';
        } else {
          statusBadge.classList.add('bg-danger');
          statusBadge.textContent = 'Nonaktif';
        }
        statusBadge.classList.add('text-white');
        
        // Dates
        const createdDate = new Date(customer.created_at);
        customerRow.querySelector('.customer-created').textContent = createdDate.toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'short',
          year: 'numeric'
        });
        
        customerRow.querySelector('.customer-last-order').textContent = '';
        customerRow.querySelector('.customer-orders-count').textContent = '0';
        
        // Action buttons
        const editButton = customerRow.querySelector('.edit-customer');
        editButton.setAttribute('data-id', customer.id);
        
        const deleteButton = customerRow.querySelector('.delete-customer');
        deleteButton.setAttribute('data-id', customer.id);
        
        $('#customer-list').append(customerRow);
      });
    }
    
    /**
     * Update pagination information and controls
     */
    function updatePagination(pagination) {
      // Update pagination info
      $('#fromItem').text(pagination.from || 0);
      $('#toItem').text(pagination.to || 0);
      $('#totalItems').text(pagination.total || 0);
      $('#currentPage').text(pagination.current_page || 1);
      $('#totalPages').text(pagination.last_page || 1);
      
      // Update current page and total pages variables
      currentPage = pagination.current_page;
      totalPages = pagination.last_page;
      
      // Generate pagination links
      generatePaginationLinks(pagination);
    }
    
    /**
     * Generate pagination links
     */
    function generatePaginationLinks(pagination) {
      const $pagination = $('#pagination');
      $pagination.empty();
      
      // Previous page link
      const $prevLi = $('<li class="page-item"></li>');
      if (pagination.current_page === 1) {
        $prevLi.addClass('disabled');
      }
      
      const $prevLink = $('<a class="page-link" href="#" aria-label="Previous"></a>');
      $prevLink.html('<span aria-hidden="true">&laquo;</span>');
      $prevLink.on('click', function(e) {
        e.preventDefault();
        if (pagination.current_page > 1) {
          goToPage(pagination.current_page - 1);
        }
      });
      
      $prevLi.append($prevLink);
      $pagination.append($prevLi);
      
      // Page number links
      let startPage = Math.max(1, pagination.current_page - 2);
      let endPage = Math.min(pagination.total_pages, startPage + 4);
      
      if (endPage - startPage < 4 && startPage > 1) {
        startPage = Math.max(1, endPage - 4);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        const $pageLi = $('<li class="page-item"></li>');
        if (i === pagination.current_page) {
          $pageLi.addClass('active');
        }
        
        const $pageLink = $('<a class="page-link" href="#"></a>');
        $pageLink.text(i);
        $pageLink.on('click', function(e) {
          e.preventDefault();
          goToPage(i);
        });
        
        $pageLi.append($pageLink);
        $pagination.append($pageLi);
      }
      
      // Next page link
      const $nextLi = $('<li class="page-item"></li>');
      if (pagination.current_page === pagination.total_pages) {
        $nextLi.addClass('disabled');
      }
      
      const $nextLink = $('<a class="page-link" href="#" aria-label="Next"></a>');
      $nextLink.html('<span aria-hidden="true">&raquo;</span>');
      $nextLink.on('click', function(e) {
        e.preventDefault();
        if (pagination.current_page < pagination.total_pages) {
          goToPage(pagination.current_page + 1);
        }
      });
      
      $nextLi.append($nextLink);
      $pagination.append($nextLi);
    }
    
    /**
     * Go to a specific page
     */
    function goToPage(page) {
      currentPage = page;
      loadCustomers();
    }
    
    /**
     * Apply filters and reload customers
     */
    function applyFilters() {
      // Reset to first page when applying filters
      currentPage = 1;
      
      // Get filter values
      currentFilters = {
        search: $('#search').val(),
        status: $('#status').val(),
        category_name: $('#category').val(),
        sort_by: $('#sort_by').val()
      };
      
      // Clean up empty filters
      Object.keys(currentFilters).forEach(key => {
        if (!currentFilters[key]) {
          delete currentFilters[key];
        }
      });
      
      // Reload customers with new filters
      loadCustomers();
    }
    
    /**
     * Load customer categories for filter and form
     */
    function loadCustomerCategories() {
      $.ajax({
        url: "{{ route('api.customer-categories.index') }}",
        type: "GET",
        success: function(response) {
          if (response.data && response.data.length > 0) {
            // Populate filter dropdown
            const filterSelect = $('#category');
            filterSelect.find('option:not(:first)').remove();
            
            // Populate edit form dropdown
            const editSelect = $('#edit_customer_category_id');
            editSelect.find('option:not(:first)').remove();
            
            response.data.forEach(function(category) {
              // Add to filter dropdown
              filterSelect.append(
                $('<option></option>')
                  .attr('value', category.category_name)
                  .text(category.category_name)
              );
              
              // Add to edit form dropdown
              editSelect.append(
                $('<option></option>')
                  .attr('value', category.category_name)
                  .text(category.category_name)
              );
            });
          }
        },
        error: function(xhr) {
          console.error('Error loading customer categories:', xhr);
        }
      });
    }
    
    /**
     * Show loading indicator
     */
    function showLoadingIndicator() {
      $('#customer-list').empty();
      $('#loadingIndicator').removeClass('d-none');
    }
    
    /**
     * Hide loading indicator
     */
    function hideLoadingIndicator() {
      $('#loadingIndicator').addClass('d-none');
    }
    
    /**
     * Show empty state
     */
    function showEmptyState() {
      $('#emptyContainer').removeClass('d-none');
    }
    
    // Show SweetAlert message if session has success or error
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
      });
    @endif
    
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 3000
      });
    @endif
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // WhatsApp button click handler
    $('#whatsapp_button').on('click', function() {
      const phone = $('#edit_phone').val().replace(/[^0-9]/g, '');
      const name = $('#edit_name').val();
      const email = $('#edit_email').val();
      
      if (phone && email) {
        const message = `Halo ${name}, akun Anda telah dibuat. Silahkan login dengan email: ${email}`;
        const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
      } else {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian',
          text: 'Nomor telepon dan email harus diisi untuk mengirim pesan WhatsApp',
        });
      }
    });
  });
</script>

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection