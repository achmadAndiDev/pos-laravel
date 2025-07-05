@extends('admin.layouts.app')

@section('title', 'Kategori Produk')
@section('subtitle', 'Manajemen Kategori Produk')

@section('right-header')
<div class="btn-list">
  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    <i class="ti ti-plus"></i>
    Tambah Kategori
  </a>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-backpack"></i>
    Kembali ke Produk
  </a>
  <a href="#" class="btn btn-primary d-sm-none" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Kategori Produk</h3>
  </div>
  <div class="card-body">
    <!-- Filter Section -->
    <div class="mb-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Filter Kategori</h4>
          <div class="card-actions">
            <a href="#" class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
              <i class="ti ti-chevron-down"></i>
            </a>
          </div>
        </div>
        <div class="card-body collapse show" id="filterCollapse">
          <form id="categoryFilterForm">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="name" id="filter-name">
              </div>
              <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" id="filter-status">
                  <option value="">Semua Status</option>
                  <option value="active">Aktif</option>
                  <option value="inactive">Nonaktif</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Kategori Induk</label>
                <select class="form-select" name="parent_category" id="filter-parent">
                  <option value="">Semua Kategori</option>
                  <!-- Parent categories will be loaded dynamically -->
                </select>
              </div>
              <div class="col-md-12 text-end">
                <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary">
                  <i class="ti ti-refresh me-1"></i>
                  Reset
                </button>
                <button type="button" id="applyFilterBtn" class="btn btn-primary">
                  <i class="ti ti-filter me-1"></i>
                  Filter
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Sorting Options -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center">
        <!-- Pagination info is now shown at the bottom of the category list -->
      </div>
      <div class="d-flex align-items-center">
        <label class="form-label me-2 mb-0">Sort By:</label>
        <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
          <option value="created_at-desc">Terbaru</option>
          <option value="created_at-asc">Terlama</option>
          <option value="name-asc">Nama (A-Z)</option>
          <option value="name-desc">Nama (Z-A)</option>
          <option value="products_count-desc">Jumlah Produk (Tinggi-Rendah)</option>
          <option value="products_count-asc">Jumlah Produk (Rendah-Tinggi)</option>
        </select>
      </div>
    </div>
    
    <!-- Categories Container -->
    <div class="row" id="categoriesContainer">
      <!-- Categories will be loaded here via AJAX -->
      <div class="col-12 text-center py-5" id="loadingIndicator">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat data kategori...</p>
      </div>
      
      <!-- Error message -->
      <div class="col-12 text-center py-5 d-none" id="errorContainer">
        <div class="empty">
          <div class="empty-icon">
            <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
          </div>
          <p class="empty-title">Terjadi kesalahan</p>
          <p class="empty-subtitle text-muted" id="errorMessage">
            Gagal memuat data kategori. Silakan coba lagi.
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
      <div class="col-12 text-center py-5 d-none" id="emptyContainer">
        <div class="empty">
          <div class="empty-icon">
            <i class="ti ti-category text-muted" style="font-size: 3rem;"></i>
          </div>
          <p class="empty-title">Tidak ada kategori</p>
          <p class="empty-subtitle text-muted">
            Belum ada kategori yang tersedia atau sesuai dengan filter yang dipilih.
          </p>
          <div class="empty-action">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
              <i class="ti ti-plus me-1"></i>
              Tambah Kategori
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div>
        <span id="paginationInfo" class="text-muted">
          Menampilkan <span id="fromItem">0</span>-<span id="toItem">0</span> dari <span id="totalItems">0</span> kategori
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

    <!-- Category Card Template (will be cloned by JS) -->
    <template id="categoryCardTemplate">
      <div class="col-12 mb-3 category-item">
        <div class="card card-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="avatar avatar-lg rounded category-image"></span>
              </div>
              <div class="col-md-3">
                <div class="text-truncate">
                  <strong class="category-name"></strong>
                  <span class="badge ms-1 category-status"></span>
                </div>
                <div class="text-muted mt-1 category-slug"></div>
                <div class="text-muted category-parent">
                  <i class="ti ti-category me-1"></i> <span></span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex flex-column">
                  <div class="text-truncate mb-1 category-description">
                  </div>
                  <div class="text-muted">
                    <i class="ti ti-packages me-1"></i> <span class="category-products-count"></span> Produk
                  </div>
                  <div class="text-muted small">
                    <i class="ti ti-calendar me-1"></i> Dibuat: <span class="category-created-at"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-auto ms-auto">
                <div class="btn-list">
                  <button class="btn btn-sm btn-warning edit-category" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                    <i class="ti ti-edit me-1"></i>
                    Edit
                  </button>
                  <button class="btn btn-sm btn-danger delete-category">
                    <i class="ti ti-trash me-1"></i>
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal modal-blur fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCategoryForm">
          <div class="mb-3">
            <label class="form-label required">Nama Kategori</label>
            <input type="text" class="form-control" name="name" placeholder="Masukkan nama kategori" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" name="slug" placeholder="Slug akan dibuat otomatis">
            <small class="form-hint">Biarkan kosong untuk membuat slug otomatis dari nama kategori</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi kategori"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" class="form-control" name="image" accept="image/*">
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select class="form-select" name="status" required>
              <option value="active" selected>Aktif</option>
              <option value="inactive">Nonaktif</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveCategoryBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal modal-blur fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm">
          <input type="hidden" name="category_id" id="edit_category_id" value="1">
          <div class="mb-3">
            <label class="form-label required">Nama Kategori</label>
            <input type="text" class="form-control" name="name" id="edit_name" value="Perlengkapan Camping" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" name="slug" id="edit_slug" value="perlengkapan-camping">
            <small class="form-hint">Biarkan kosong untuk membuat slug otomatis dari nama kategori</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" id="edit_description" rows="3">Berbagai perlengkapan untuk kegiatan camping</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" class="form-control" name="image" accept="image/*">
            <small class="form-hint">Biarkan kosong jika tidak ingin mengubah gambar</small>
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select class="form-select" name="status" id="edit_status" required>
              <option value="active" selected>Aktif</option>
              <option value="inactive">Nonaktif</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="updateCategoryBtn">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
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
  
  /* Card Styling */
  .category-item .card {
    transition: all 0.3s ease;
    border-left: 3px solid var(--tblr-primary);
  }
  
  .category-item .card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
  
  .category-image {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--tblr-primary);
    background-color: var(--tblr-primary-subtle);
  }
  
  .category-description {
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .category-slug {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
    font-style: italic;
  }
  
  /* Status Badge Styling */
  .category-status.bg-success {
    background-color: #10b981 !important;
    color: white !important;
  }
  
  .category-status.bg-danger {
    background-color: #ef4444 !important;
    color: white !important;
  }
  
  /* Pagination Styling */
  .pagination {
    margin-bottom: 0;
  }
  
  .pagination .page-item .page-link {
    border-radius: 4px;
    margin: 0 2px;
  }
  
  .pagination .page-item.active .page-link {
    background-color: var(--tblr-primary);
    border-color: var(--tblr-primary);
  }
  
  /* Filter Card Styling */
  #filterCollapse {
    transition: all 0.3s ease;
  }
  
  /* Loading and Empty States */
  #loadingIndicator, #errorContainer, #emptyContainer {
    padding: 3rem 0;
  }
  
  /* Filter Button Pulse Effect */
  .btn-primary-pulse {
    animation: pulse-primary 1.5s infinite;
  }
  
  @keyframes pulse-primary {
    0% {
      box-shadow: 0 0 0 0 rgba(var(--tblr-primary-rgb), 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(var(--tblr-primary-rgb), 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(var(--tblr-primary-rgb), 0);
    }
  }
</style>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Setup CSRF token untuk semua request AJAX
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Variabel untuk pagination
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 10;
    let totalItems = 0;
    let currentSort = 'created_at-desc';
    let currentFilters = {};
    
    // Fungsi untuk memuat data kategori
    function loadCategories(page = 1, filters = {}, sort = 'created_at-desc') {
      // Tampilkan loading indicator
      $('#loadingIndicator').removeClass('d-none');
      $('#categoriesContainer .category-item').remove();
      $('#errorContainer').addClass('d-none');
      $('#emptyContainer').addClass('d-none');
      
      // Persiapkan parameter
      const params = {
        page: page,
        per_page: perPage,
        ...filters
      };
      
      // Tambahkan parameter sort
      if (sort) {
        const [sortField, sortDirection] = sort.split('-');
        params.sort_by = sortField;
        params.sort_direction = sortDirection;
      }
      
      // Log untuk debugging
      console.log('Request parameters:', params);
      
      // Kirim request ke server
      $.ajax({
        url: '/api/product-categories',
        type: 'GET',
        data: params,
        dataType: 'json',
        success: function(response) {
          // Sembunyikan loading indicator
          $('#loadingIndicator').addClass('d-none');
          
          // Update variabel pagination
          currentPage = response.meta.current_page;
          totalPages = response.meta.last_page;
          totalItems = response.meta.total;
          
          // Update info pagination
          $('#fromItem').text(response.meta.from || 0);
          $('#toItem').text(response.meta.to || 0);
          $('#totalItems').text(response.meta.total);
          $('#currentPage').text(response.meta.current_page);
          $('#totalPages').text(response.meta.last_page);
          
          // Render pagination
          renderPagination();
          
          // Render data
          if (response.data && response.data.length > 0) {
            renderCategories(response.data);
          } else {
            $('#emptyContainer').removeClass('d-none');
          }
          
          // Highlight filter button jika ada filter aktif
          if (Object.keys(filters).length > 0) {
            $('#applyFilterBtn').addClass('btn-primary-pulse');
          } else {
            $('#applyFilterBtn').removeClass('btn-primary-pulse');
          }
        },
        error: function(xhr, status, error) {
          // Sembunyikan loading indicator
          $('#loadingIndicator').addClass('d-none');
          
          // Tampilkan pesan error
          $('#errorContainer').removeClass('d-none');
          $('#errorMessage').text('Gagal memuat data kategori. Silakan coba lagi.');
          
          console.error('Error loading categories:', error);
          console.error('Response:', xhr.responseText);
        }
      });
    }
    
    // Fungsi untuk render kategori
    function renderCategories(categories) {
      const container = $('#categoriesContainer');
      const template = document.getElementById('categoryCardTemplate');
      
      categories.forEach(category => {
        // Clone template
        const categoryCard = document.importNode(template.content, true);
        
        // Set data kategori
        $(categoryCard).find('.category-name').text(category.name);
        $(categoryCard).find('.category-slug').text('@' + (category.slug || ''));
        $(categoryCard).find('.category-description').text(category.description || 'Tidak ada deskripsi');
        $(categoryCard).find('.category-products-count').text(category.products_count || 0);
        $(categoryCard).find('.category-created-at').text(formatDate(category.created_at));
        
        // Set status dengan kontras warna yang lebih baik
        const statusBadge = $(categoryCard).find('.category-status');
        if (category.status === 'active') {
          statusBadge.addClass('bg-success').text('Aktif');
        } else {
          statusBadge.addClass('bg-danger').text('Nonaktif');
        }
        
        // Set parent category
        if (category.parent && category.parent.name) {
          $(categoryCard).find('.category-parent span').text(category.parent.name);
        } else {
          $(categoryCard).find('.category-parent').html('<i class="ti ti-category me-1"></i> Kategori Utama');
        }
        
        // Set image
        const imageContainer = $(categoryCard).find('.category-image');
        if (category.image) {
          imageContainer.css('background-image', `url('${category.image}')`);
          imageContainer.css('background-size', 'cover');
          imageContainer.css('background-position', 'center');
        } else {
          imageContainer.html('<i class="ti ti-category" style="font-size: 2rem;"></i>');
        }
        
        // Set action buttons
        $(categoryCard).find('.edit-category').attr('data-id', category.id);
        $(categoryCard).find('.delete-category').attr('data-id', category.id);
        
        // Append to container
        container.append(categoryCard);
      });
    }
    
    // Fungsi untuk render pagination
    function renderPagination() {
      const pagination = $('#pagination');
      pagination.empty();
      
      // Previous button
      const prevDisabled = currentPage === 1;
      pagination.append(`
        <li class="page-item ${prevDisabled ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      `);
      
      // Page numbers
      const startPage = Math.max(1, currentPage - 2);
      const endPage = Math.min(totalPages, startPage + 4);
      
      for (let i = startPage; i <= endPage; i++) {
        pagination.append(`
          <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
          </li>
        `);
      }
      
      // Next button
      const nextDisabled = currentPage === totalPages;
      pagination.append(`
        <li class="page-item ${nextDisabled ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      `);
    }
    
    // Event handler untuk pagination
    $(document).on('click', '#pagination .page-link', function(e) {
      e.preventDefault();
      
      const page = $(this).data('page');
      if (page && page !== currentPage && page >= 1 && page <= totalPages) {
        loadCategories(page, currentFilters, currentSort);
      }
    });
    
    // Event handler untuk perPage select
    $('#perPageSelect').on('change', function() {
      perPage = parseInt($(this).val());
      loadCategories(1, currentFilters, currentSort);
    });
    
    // Event handler untuk sort select
    $('#sortSelect').on('change', function() {
      currentSort = $(this).val();
      loadCategories(1, currentFilters, currentSort);
    });
    
    // Event handler untuk filter
    $('#applyFilterBtn').on('click', function() {
      const name = $('#filter-name').val();
      const status = $('#filter-status').val();
      const parentCategory = $('#filter-parent').val();
      
      currentFilters = {};
      
      if (name) currentFilters.name = name;
      if (status) currentFilters.status = status;
      if (parentCategory) currentFilters.parent_category = parentCategory;
      
      console.log('Applying filters:', currentFilters);
      loadCategories(1, currentFilters, currentSort);
    });
    
    // Event handler untuk perubahan filter status langsung
    $('#filter-status').on('change', function() {
      if ($('#filter-status').val()) {
        $('#applyFilterBtn').addClass('btn-primary-pulse');
      } else {
        $('#applyFilterBtn').removeClass('btn-primary-pulse');
      }
    });
    
    // Event handler untuk reset filter
    $('#resetFilterBtn').on('click', function() {
      $('#categoryFilterForm')[0].reset();
      currentFilters = {};
      loadCategories(1, {}, currentSort);
    });
    
    // Event handler untuk retry button
    $('#retryButton').on('click', function() {
      loadCategories(currentPage, currentFilters, currentSort);
    });
    
    // Fungsi untuk format tanggal
    function formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });
    }
    
    // Fungsi untuk memuat parent categories untuk filter
    function loadParentCategories() {
      $.ajax({
        url: '/api/product-categories',
        type: 'GET',
        data: { per_page: 100 },
        dataType: 'json',
        success: function(response) {
          const select = $('#filter-parent');
          
          if (response.data && response.data.length > 0) {
            response.data.forEach(category => {
              select.append(`<option value="${category.id}">${category.name}</option>`);
            });
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading parent categories:', error);
        }
      });
    }
    
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
    
    // Event handler untuk tombol Tambah Kategori
    $('#saveCategoryBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('addCategoryForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Persiapkan data form
      const formData = new FormData(form);
      
      // Kirim data ke server
      $.ajax({
        url: '/api/product-categories',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          Swal.fire({
            title: 'Berhasil!',
            text: 'Kategori baru telah ditambahkan',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              $('#addCategoryModal').modal('hide');
              $('#addCategoryForm').trigger('reset');
              
              // Refresh data
              loadCategories(1, currentFilters, currentSort);
              loadParentCategories();
            }
          });
        },
        error: function(xhr, status, error) {
          let errorMessage = 'Terjadi kesalahan saat menyimpan kategori.';
          
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            const errorList = Object.keys(errors).map(key => errors[key][0]);
            errorMessage = errorList.join('<br>');
          } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }
          
          Swal.fire({
            title: 'Error!',
            html: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
    
    // Event handler untuk tombol Edit Kategori
    $('#updateCategoryBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('editCategoryForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Ambil ID kategori
      const categoryId = $('#edit_category_id').val();
      
      // Persiapkan data form
      const formData = new FormData(form);
      
      // Kirim data ke server
      $.ajax({
        url: `/api/product-categories/${categoryId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(xhr) {
          xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT');
        },
        success: function(response) {
          Swal.fire({
            title: 'Berhasil!',
            text: 'Kategori telah diperbarui',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              $('#editCategoryModal').modal('hide');
              
              // Refresh data
              loadCategories(currentPage, currentFilters, currentSort);
              loadParentCategories();
            }
          });
        },
        error: function(xhr, status, error) {
          let errorMessage = 'Terjadi kesalahan saat memperbarui kategori.';
          
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            const errorList = Object.keys(errors).map(key => errors[key][0]);
            errorMessage = errorList.join('<br>');
          } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }
          
          Swal.fire({
            title: 'Error!',
            html: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
    
    // Event handler untuk tombol Hapus Kategori
    $(document).on('click', '.delete-category', function() {
      const categoryId = $(this).data('id');
      
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus kategori ini? Semua produk dalam kategori ini akan kehilangan kategorinya.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Kirim request hapus ke server
          $.ajax({
            url: `/api/product-categories/${categoryId}`,
            type: 'DELETE',
            success: function(response) {
              Swal.fire(
                'Terhapus!',
                'Kategori telah dihapus.',
                'success'
              );
              
              // Refresh data
              loadCategories(currentPage, currentFilters, currentSort);
              loadParentCategories();
            },
            error: function(xhr, status, error) {
              let errorMessage = 'Terjadi kesalahan saat menghapus kategori.';
              
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
              }
              
              Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        }
      });
    });
    
    // Event handler untuk modal Edit Kategori
    $(document).on('click', '.edit-category', function() {
      const categoryId = $(this).data('id');
      
      // Reset form
      $('#editCategoryForm').trigger('reset');
      
      // Ambil data kategori dari server
      $.ajax({
        url: `/api/product-categories/${categoryId}`,
        type: 'GET',
        success: function(response) {
          const category = response.data;
          
          // Mengisi data ke dalam form edit
          $('#edit_category_id').val(category.id);
          $('#edit_name').val(category.name);
          $('#edit_slug').val(category.slug);
          $('#edit_description').val(category.description);
          $('#edit_status').val(category.status || 'active');
          
          // Reset flag manually-changed untuk slug
          $('#edit_slug').data('manually-changed', true);
          
          // Tampilkan modal
          $('#editCategoryModal').modal('show');
        },
        error: function(xhr, status, error) {
          console.error('Error fetching category:', error);
          
          Swal.fire({
            title: 'Error!',
            text: 'Gagal memuat data kategori. Silakan coba lagi.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      });
    });
    
    // Reset form saat modal tambah ditutup
    $('#addCategoryModal').on('hidden.bs.modal', function() {
      $('#addCategoryForm').trigger('reset');
      $('input[name="slug"]').data('manually-changed', false);
    });
    
    // Muat data kategori saat halaman dimuat
    loadCategories();
    
    // Muat parent categories untuk filter
    loadParentCategories();
  });
</script>
@endsection