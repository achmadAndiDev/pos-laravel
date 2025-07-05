@extends('admin.layouts.app')

@section('title', 'Produk')
@section('subtitle', 'Manajemen Data Produk')

@section('css')
    <style>
        /* Styles for product management */
        .product-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

/* 
            display: inline-block;
            width: 64px;
            height: 64px;
            background-size: cover;
            background-position: center; */
        }

        .product-stock-list {
            max-height: 100px;
            overflow-y: auto;
        }

        /* New styles for product list */
        #productListHeader .card {
            background-color: #f8fafc;
            border-left: 3px solid var(--tblr-primary);
        }

        .stock-detail {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .stock-detail-btn {
            transition: all 0.2s ease;
        }

        .stock-detail-btn:hover {
            transform: scale(1.05);
        }

        .product-stock-detail table {
            margin-bottom: 0;
            width: 100%;
        }

        .product-stock-detail th {
            font-weight: 600;
            color: var(--tblr-primary);
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
            padding: 0.3rem 0.5rem;
        }

        .product-stock-detail td {
            padding: 0.3rem 0.5rem;
            white-space: nowrap;
        }
        
        .product-stock-detail td:last-child {
            white-space: normal;
        }

        .product-item .card {
            transition: all 0.2s ease;
            /* border-left: 3px solid transparent; */
        }

        .product-item .card:hover {
            border-left: 3px solid var(--tblr-primary);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .badge.product-stock,
        .badge.product-variants {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
    </style>
@endsection

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('right-header')
    <div class="btn-list">
        <a class="btn btn-primary d-none d-sm-inline-block" href="{{ route('admin.products.create-v2') }}">
            <i class="ti ti-plus"></i>
            Tambah Produk
        </a>
        <div class="dropdown d-none d-sm-inline-block">
            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                <i class="ti ti-settings"></i>
                Pengaturan
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.products.categories.index') }}">
                    <i class="ti ti-category me-2"></i>
                    Kategori
                </a>
            </div>
        </div>
        <a class="btn btn-primary d-sm-none" href="{{ route('admin.products.create-v2') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Produk</h3>
            <div class="card-actions">
                {{-- <a class="btn btn-outline-primary btn-sm" id="exportProductsBtn" href="#">
                    <i class="ti ti-file-export me-1"></i>
                    Export
                </a> --}}
                <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.products.import') }}">
                    <i class="ti ti-file-import me-1"></i>
                    Import
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filter Produk</h4>
                        <div class="card-actions">
                            <a class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#filterCollapse" href="#">
                                <i class="ti ti-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body collapse show" id="filterCollapse">
                        <form id="productFilterForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input class="form-control" id="filter-name" name="name" type="text">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">SKU</label>
                                    <input class="form-control" id="filter-sku" name="sku" type="text">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kategori</label>
                                    <select class="form-select" id="filter-category" name="category_id">
                                        <option value="">Semua Kategori</option>
                                        <!-- Categories will be loaded dynamically -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gudang</label>
                                    <select class="form-select" id="filter-warehouse" name="warehouse_id">
                                        <option value="">Semua Gudang</option>
                                        <!-- Warehouses will be loaded dynamically -->
                                    </select>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button class="btn btn-outline-secondary" id="resetFilterBtn" type="button">
                                        <i class="ti ti-refresh me-1"></i>
                                        Reset
                                    </button>
                                    <button class="btn btn-primary" id="applyFilterBtn" type="button">
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
                    <!-- Pagination info is now shown at the bottom of the product list -->
                </div>
                <div class="d-flex align-items-center">
                    <label class="form-label me-2 mb-0">Sort By:</label>
                    <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                        <option value="created_at-desc">Terbaru</option>
                        <option value="created_at-asc">Terlama</option>
                        <option value="name-asc">Nama (A-Z)</option>
                        <option value="name-desc">Nama (Z-A)</option>
                        <option value="total_stock-desc">Stok (Tinggi-Rendah)</option>
                        <option value="total_stock-asc">Stok (Rendah-Tinggi)</option>
                    </select>
                </div>
            </div>

            <!-- Products Container -->
            <div class="row" id="productsContainer">
                <!-- Product List Header -->
                <div class="col-12 mb-3" id="productListHeader">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center fw-bold">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg" style="
                                        background: #ffffff00;
                                        box-shadow: none;">
                                        {{-- <i class="ti ti-photo text-primary"></i> --}}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <div>Produk & Harga</div>
                                    {{-- <div class="small text-muted">(Harga jual terendah)</div> --}}
                                </div>
                                <div class="col-md-2">
                                    <div>Stok</div>
                                    {{-- <div class="small text-muted">(Total)</div> --}}
                                </div>
                                <div class="col-md-2">
                                    <div>Varian</div>
                                    {{-- <div class="small text-muted">(Total)</div> --}}
                                </div>
                                <div class="col-md-1">
                                    <div>Kategori</div>
                                </div>
                                <div class="col-md-1">
                                    <div>Brand</div>
                                </div>
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-auto ms-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products will be loaded here via AJAX -->
                <div class="col-12 text-center py-5" id="loadingIndicator">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data produk...</p>
                </div>

                <!-- Error message -->
                <div class="col-12 text-center py-5 d-none" id="errorContainer">
                    <div class="empty">
                        <div class="empty-icon">
                            <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <p class="empty-title">Terjadi kesalahan</p>
                        <p class="empty-subtitle text-muted" id="errorMessage">
                            Gagal memuat data produk. Silakan coba lagi.
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
                            <i class="ti ti-box text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <p class="empty-title">Tidak ada produk</p>
                        <p class="empty-subtitle text-muted">
                            Belum ada produk yang tersedia atau sesuai dengan filter yang dipilih.
                        </p>
                        <div class="empty-action">
                            <a class="btn btn-primary" href="{{ route('admin.products.create') }}">
                                <i class="ti ti-plus me-1"></i>
                                Tambah Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted" id="paginationInfo">
                        Menampilkan <span id="fromItem">0</span>-<span id="toItem">0</span> dari <span id="totalItems">0</span> produk
                        (Halaman <span id="currentPage">0</span> dari <span id="totalPages">0</span>)
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm d-inline-block me-2" id="perPageSelect" style="width: auto;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <ul class="pagination m-0" id="pagination"></ul>
                </div>
            </div>

            <!-- Product Card Template (will be cloned by JS) -->
            <template id="productCardTemplate">
                <div class="col-12 mb-3 product-item">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-lg rounded product-image"></span>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-truncate">
                                        <strong class="product-name"></strong>
                                    </div>
                                    <div class="text-muted mt-1 product-sku"></div>
                                    <div class="h5 mb-0 mt-2 product-price"></div>
                                    <div class="text-muted small">Harga jual terendah</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <span class="badge bg-primary product-stock text-white"></span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary stock-detail-btn" type="button">
                                            <i class="ti ti-list-details"></i>
                                        </button>
                                    </div>
                                    <!-- Stock Detail Collapsible -->
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info product-variants text-white"></span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="text-muted product-categories"></div>
                                </div>
                                <div class="col-md-1">
                                    <div class="text-muted product-brand"></div>
                                </div>
                                {{-- <div class="col-md-2">
                                    <div class="text-truncate mb-1">
                                        <span class="text-muted product-description"></span>
                                    </div>
                                    <div class="text-muted product-dimensions">
                                        <i class="ti ti-ruler me-1"></i> <span></span>
                                    </div>
                                    <div class="text-muted product-weight">
                                        <i class="ti ti-weight me-1"></i> <span></span>
                                    </div>
                                </div> --}}
                                <div class="col-md-auto ms-auto">
                                    <div class="btn-list">
                                        {{-- <button class="btn btn-sm btn-info view-product">
                                            <i class="ti ti-eye"></i>
                                        </button> --}}
                                        <a class="btn btn-sm btn-warning edit-product" href="#">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-product">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                    <div class="collapse mt-2 stock-detail">
                                        <div class="card card-body p-2">
                                            <h6 class="mb-2 border-bottom pb-1">Detail Stok:</h6>
                                            <div class="product-stock-detail small"></div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <!-- Pagination is now handled by the pagination component above -->
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

        .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .thumbnail-container .img-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .thumbnail-container .img-thumbnail.active {
            border-color: var(--tblr-primary);
        }

        #product-carousel .carousel-item img {
            height: 300px;
            object-fit: contain;
        }
    </style>
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {

            // Thumbnail carousel
            $('.thumbnail-container .img-thumbnail').on('click', function() {
                $('.thumbnail-container .img-thumbnail').removeClass('active');
                $(this).addClass('active');
            });
            // Event handler untuk tombol Hapus Produk
            $(document).on('click', '.delete-product', function() {
                const productId = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus produk ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('api/products') }}/${productId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Tampilkan pesan sukses
                                toastr.success('Produk berhasil dihapus');
                                loadProducts();
                            },
                            error: function(xhr) {
                                let errorMessage = 'Terjadi kesalahan saat menghapus produk.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                toastr.error(errorMessage);
                            }
                        });
                    }
                });
            });

            // Event handler untuk tombol Import Produk
            $('#importProductsBtn').on('click', function() {
                // Validasi form
                const form = document.getElementById('importProductsForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Simulasi import data
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data produk telah diimport',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#importProductsModal').modal('hide');
                        $('#importProductsForm').trigger('reset');

                        // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
                        // productTable.ajax.reload();
                    }
                });
            });

            // Event handler untuk tombol Export Produk
            $('#exportProductsBtn').on('click', function() {
                // Simulasi export data
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data produk telah diekspor',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            // State variables
            let currentPage = 1;
            let perPage = 10;
            let totalProducts = 0;
            let totalPages = 0;
            let currentFilters = {};
            let currentSort = 'created_at-desc';
            let viewMode = 'card';

            // Initialize products loading
            loadProducts();

            // Event listeners for filtering
            $('#applyFilterBtn').on('click', function() {
                currentPage = 1;
                collectFilters();
                loadProducts();
            });

            $('#resetFilterBtn').on('click', function() {
                $('#productFilterForm')[0].reset();
                currentPage = 1;
                currentFilters = {};
                loadProducts();
            });

            // Event listener for sorting
            $('#sortSelect').on('change', function() {
                currentSort = $(this).val();
                currentPage = 1;
                loadProducts();
            });

            // Event listener for per page selection
            $('#perPageSelect').on('change', function() {
                perPage = parseInt($(this).val());
                currentPage = 1;
                loadProducts();
            });

            // Event listeners for view mode
            $('#cardViewBtn').on('click', function() {
                viewMode = 'card';
                $(this).addClass('active').removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                $('#listViewBtn').removeClass('active').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                // In a real implementation, you might change the display style here
            });

            $('#listViewBtn').on('click', function() {
                viewMode = 'list';
                $(this).addClass('active').removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                $('#cardViewBtn').removeClass('active').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                // In a real implementation, you might change the display style here
            });

            // Function to collect filter values
            function collectFilters() {
                currentFilters = {
                    name: $('#filter-name').val(),
                    sku: $('#filter-sku').val(),
                    category_id: $('#filter-category').val(),
                    warehouse_id: $('#filter-warehouse').val()
                };
            }

            // Function to load products via AJAX
            function loadProducts() {
                // Show loading indicator, hide error and empty containers
                $('#loadingIndicator').removeClass('d-none');
                $('#errorContainer').addClass('d-none');
                $('#emptyContainer').addClass('d-none');
                $('#productsContainer .product-item').remove();

                // Prepare query parameters
                const [sortField, sortDirection] = currentSort.split('-');
                const params = {
                    ...currentFilters,
                    page: currentPage,
                    per_page: perPage,
                    sort_by: sortField,
                    sort_direction: sortDirection
                };

                // Make AJAX request
                $.ajax({
                    url: '{{ route('api.products.index') }}',
                    type: 'GET',
                    data: params,
                    success: function(response) {
                        // Hide loading indicator
                        $('#loadingIndicator').addClass('d-none');

                        // Update pagination info
                        totalProducts = response.pagination.total;
                        totalPages = response.pagination.total_pages;
                        currentPage = response.pagination.current_page;

                        // Update pagination display
                        updatePaginationInfo(response.pagination);
                        renderPagination();

                        // Render products
                        if (response.data.length === 0) {
                            $('#emptyContainer').removeClass('d-none');
                        } else {
                            renderProducts(response.data);
                        }
                    },
                    error: function(xhr) {
                        // Hide loading indicator, show error
                        $('#loadingIndicator').addClass('d-none');
                        $('#errorContainer').removeClass('d-none');

                        let errorMessage = 'Gagal memuat data produk. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('#errorMessage').text(errorMessage);
                    }
                });
            }

            // Function to render products
            // function renderProducts(products) {
            //     if (products.length === 0) {
            //         $('#emptyContainer').removeClass('d-none');
            //         return;
            //     }

            //     // Render each product
            //     products.forEach(function(product) {
            //         const template = document.getElementById('productCardTemplate');
            //         const clone = document.importNode(template.content, true);

            //         // Set product data
            //         $(clone).find('.product-name').text(product.name);
            //         $(clone).find('.product-sku').text(product.sku);

            //         // Set product categories
            //         if (product.categories && product.categories.length > 0) {
            //             const categoryNames = product.categories.map(cat => cat.name).join(', ');
            //             $(clone).find('.product-categories').text(categoryNames);
            //         } else {
            //             $(clone).find('.product-categories').text('Tidak ada kategori');
            //         }

            //         // Set stock badge
            //         $(clone).find('.product-stock').text(`Stok: ${product.total_stock}`);

            //         // Set category
            //         if (product.categories && product.categories.length > 0) {
            //             $(clone).find('.product-category').text(product.categories[0].name);
            //         } else {
            //             $(clone).find('.product-category').text('Tanpa Kategori');
            //         }

            //         // Set description
            //         $(clone).find('.product-description').text(product.description || 'Tidak ada deskripsi');

            //         // Set weight
            //         $(clone).find('.product-weight span').text(`Berat: ${product.weight} gram`);

            //         // Set image
            //         if (product.thumbnail_path) {
            //             $(clone).find('.product-image').css('background-image', `url(${product.thumbnail_path})`);
            //         } else {
            //             $(clone).find('.product-image').css('background-image', 'url(/client/img/product/default-product.jpg)');
            //         }

            //         // Set product variations with warehouse info
            //         if (product.variations && product.variations.length > 0) {
            //             const variationsList = $(clone).find('.product-variations-list');
            //             variationsList.empty();

            //             product.variations.forEach(function(variation) {
            //                 let variationHtml = `
            //   <div class="mb-2">
            //     <div class="fw-bold">${variation.name}</div>
            //     <div class="text-muted">Total Stok: ${variation.stock_quantity}</div>
            // `;

            //                 // Add warehouse stocks if available
            //                 if (variation.warehouse_stocks && variation.warehouse_stocks.length > 0) {
            //                     variationHtml += '<div class="ms-2 mt-1">';
            //                     variation.warehouse_stocks.forEach(function(stock) {
            //                         variationHtml += `
            //       <div class="text-muted small">
            //         <i class="ti ti-building-warehouse me-1"></i>
            //         ${stock.warehouse.name}: ${stock.stock} 
            //         ${stock.rack ? `(Rak: ${stock.rack})` : ''}
            //       </div>
            //     `;
            //                     });
            //                     variationHtml += '</div>';
            //                 }

            //                 variationHtml += '</div>';
            //                 variationsList.append(variationHtml);
            //             });
            //         }

            //         // Set action buttons data attributes
            //         $(clone).find('.view-product').attr('data-id', product.id);
            //         $(clone).find('.edit-product').attr('data-id', product.id);
            //         $(clone).find('.delete-product').attr('data-id', product.id);

            //         // Add event listeners for buttons
            //         $(clone).find('.view-product').on('click', function() {
            //             const productId = $(this).data('id');
            //             // Open view modal
            //             $('#viewProductModal').modal('show');

            //             // Load product details via AJAX
            //             $.ajax({
            //                 url: `{{ url('api/products') }}/${productId}`,
            //                 type: 'GET',
            //                 beforeSend: function() {
            //                     // Show loading indicator
            //                     $('#viewProductModalBody').html(`
            //     <div class="text-center py-5">
            //       <div class="spinner-border text-primary" role="status">
            //         <span class="visually-hidden">Loading...</span>
            //       </div>
            //       <p class="mt-2">Memuat detail produk...</p>
            //     </div>
            //   `);
            //                 },
            //                 success: function(response) {
            //                     const product = response.data;

            //                     // Populate modal with product details
            //                     $('#view_product_name').text(product.name);
            //                     $('#view_category').text(product.categories && product.categories.length > 0 ?
            //                         product.categories[0].name : 'Tanpa Kategori');
            //                     $('#view_price').text(`Rp ${product.price || 0}`);
            //                     $('#view_short_description').text(product.description || 'Tidak ada deskripsi');
            //                     $('#view_weight').text(`${product.weight || 0} gram`);
            //                     $('#view_total_stock').text(product.total_stock || 0);
            //                     $('#view_sku').text(`SKU: ${product.sku || '-'}`);

            //                     // Set status badge
            //                     let statusBadge = '';
            //                     if (product.publish_status === 1) {
            //                         statusBadge = '<span class="badge bg-success">Aktif</span>';
            //                     } else {
            //                         statusBadge = '<span class="badge bg-secondary">Tidak Aktif</span>';
            //                     }
            //                     $('#view_status').html(statusBadge);
            //                 },
            //                 error: function(xhr) {
            //                     let errorMessage = 'Terjadi kesalahan saat memuat detail produk.';
            //                     if (xhr.responseJSON && xhr.responseJSON.message) {
            //                         errorMessage = xhr.responseJSON.message;
            //                     }

            //                     $('#viewProductModalBody').html(`
            //     <div class="alert alert-danger">
            //       <i class="ti ti-alert-circle me-2"></i> ${errorMessage}
            //     </div>
            //   `);
            //                 }
            //             });
            //         });

            //         $(clone).find('.edit-product').on('click', function() {
            //             const productId = $(this).data('id');
            //             window.location.href = `/admin/products/${productId}/edit`;
            //         });


            //         // Append to container
            //         $('#productsContainer').append(clone);
            //     });
            // }

            function renderProducts(products) {
                const container = document.getElementById('productsContainer');
                const template = document.getElementById('productCardTemplate');
                const headerElement = document.getElementById('productListHeader');

                // Clear previous content except templates and loading indicators
                const existingItems = container.querySelectorAll('.product-item');
                existingItems.forEach(item => item.remove());

                // Hide loading and error containers
                document.getElementById('loadingIndicator').classList.add('d-none');
                document.getElementById('errorContainer').classList.add('d-none');

                if (products.length === 0) {
                    document.getElementById('emptyContainer').classList.remove('d-none');
                    headerElement.classList.add('d-none');
                    return;
                }

                document.getElementById('emptyContainer').classList.add('d-none');
                headerElement.classList.remove('d-none');

                // Render each product
                products.forEach(product => {
                    console.log(product);
                    const productCard = template.content.cloneNode(true);

                    // Set product data
                    productCard.querySelector('.product-name').textContent = product.name;
                    productCard.querySelector('.product-sku').textContent = 'SKU: ' + (product.sku || 'N/A');
                    // productCard.querySelector('.product-description').textContent = product.description || 'Tidak ada deskripsi';

                    // Set categories
                    let categoriesText = '';
                    if (product.categories && Array.isArray(product.categories) && product.categories.length > 0) {
                        categoriesText = product.categories.map(category => category.name).join(', ');
                    } else {
                        categoriesText = '-';
                    }
                    productCard.querySelector('.product-categories').textContent = categoriesText;
                    
                    // Set brand
                    let brandText = '-';
                    if (product.brand && product.brand.name) {
                        brandText = product.brand.name;
                    }
                    productCard.querySelector('.product-brand').textContent = brandText;

                    // Set product image
                    // const imageElement = productCard.querySelector('.product-image');
                    
                    // if (product.primary_variation?.thumbnail_path) {
                    //     imageElement.style.backgroundImage = `url(/storage/${product.primary_variation?.thumbnail_path})`;
                    // } else {
                    //     imageElement.style.backgroundImage = 'url(/assets/img/no-image.png)';
                    // }

                    const imageElement = productCard.querySelector('.product-image');
                    const thumbnailPath = product.primary_variation?.thumbnail_path;

                    if (imageElement) {
                        if (thumbnailPath) {
                            const fullUrl = `/storage/${thumbnailPath}`;
                            // console.log('Setting background image to:', fullUrl);
                            imageElement.style.backgroundImage = `url('${fullUrl}')`; // gunakan kutip tunggal di dalam url()
                        } else {
                            imageElement.style.backgroundImage = 'url(/assets/img/no-image.png)';
                        }
                    }

                    // Set product stock
                    const stockText = (product.total_stock || 0) + ' stok';
                    productCard.querySelector('.product-stock').textContent = stockText;

                    // Set product variants count
                    const variantsCount = product.variations_count || 0;
                    productCard.querySelector('.product-variants').textContent = (product.variations.length) + ' varian';

                    // Set product price
                    const priceElement = productCard.querySelector('.product-price');
                    if (product.lowest_price) {
                        priceElement.textContent = formatCurrency(product.lowest_price);
                    } else {
                        priceElement.textContent = 'Tidak ada harga';
                    }

                    // Set stock details
                    const stockDetailElement = productCard.querySelector('.product-stock-detail');
                    if (product.variations && product.variations.length > 0) {
                        let stockDetailHtml = '<table class="table table-sm table-borderless mb-0">';
                        stockDetailHtml += `<thead>
                            <tr>
                                <th>Ukuran & Warna</th>
                                <th>Harga Normal</th>
                                <th>Harga Reseller</th>
                                <th>Super Dropshipper</th>
                                <th>Dropshipper Standar</th>
                                <th>Grosir</th>
                                <th>Stok</th>
                            </tr>
                        </thead><tbody>`;

                        product.variations.forEach(variation => {
                            const variationName = `${variation.size || '-'} / ${variation.color || '-'}`;
                            const variationPrice = variation.price ? formatCurrency(variation.price) : '-';
                            const variationPriceReseller = variation.price_reseller ? formatCurrency(variation.price_reseller) : '-';
                            const variationPrice1 = variation.price1 ? formatCurrency(variation.price1) : '-';
                            const variationPrice2 = variation.price2 ? formatCurrency(variation.price2) : '-';
                            const variationPrice3 = variation.price3 ? formatCurrency(variation.price3) : '-';
                            const variationStock = variation.stock || 0;

                            let warehouseStok = '';
                            variation.warehouses_data.forEach(warehouse => {
                                warehouseStok += `<p><strong>${warehouse.warehouse_name}</strong>: ${warehouse.stock}</p>`;
                            })

                            stockDetailHtml += `<tr>
                                <td>${variationName}</td>
                                <td>${variationPrice}</td>
                                <td>${variationPriceReseller}</td>
                                <td>${variationPrice1}</td>
                                <td>${variationPrice2}</td>
                                <td>${variationPrice3}</td>
                                <td>${warehouseStok}</td>
                            </tr>`;
                        });

                        stockDetailHtml += '</tbody></table>';
                        stockDetailElement.innerHTML = stockDetailHtml;
                    } else {
                        stockDetailElement.innerHTML = '<div class="text-center py-2">Tidak ada data varian</div>';
                    }

                    // Set dimensions and weight if available
                    // const dimensionsElement = productCard.querySelector('.product-dimensions span');
                    // if (product.length && product.width && product.height) {
                    //     dimensionsElement.textContent = `${product.length}x${product.width}x${product.height} cm`;
                    // } else {
                    //     dimensionsElement.textContent = 'Tidak tersedia';
                    // }

                    // const weightElement = productCard.querySelector('.product-weight span');
                    // if (product.weight) {
                    //     weightElement.textContent = product.weight + ' kg';
                    // } else {
                    //     weightElement.textContent = 'Tidak tersedia';
                    // }

                    // Set action buttons
                    // const viewButton = productCard.querySelector('.view-product');
                    // viewButton.setAttribute('data-id', product.id);
                    // viewButton.addEventListener('click', function() {
                    //     window.location.href = `{{ route('admin.products.show', '') }}/${product.id}`;
                    // });

                    const editButton = productCard.querySelector('.edit-product');
                    editButton.setAttribute('href', `/admin/products/${product.id}/edit`);

                    const deleteButton = productCard.querySelector('.delete-product');
                    deleteButton.setAttribute('data-id', product.id);

                    // Add event listener for stock detail button
                    const stockDetailBtn = productCard.querySelector('.stock-detail-btn');
                    const stockDetailContainer = productCard.querySelector('.stock-detail');


                    stockDetailBtn.addEventListener('click', function() {
                        // Toggle the collapse
                        if (stockDetailContainer.classList.contains('show')) {
                            stockDetailContainer.classList.remove('show');
                        } else {
                            stockDetailContainer.classList.add('show');
                        }
                    });

                    container.appendChild(productCard);
                });
            }

            // Function to update pagination info text
            function updatePaginationInfo(pagination) {
                const from = pagination.from || 0;
                const to = pagination.to || 0;
                const total = pagination.total || 0;
                const currentPage = pagination.current_page || 0;
                const totalPages = pagination.total_pages || 0;

                $('#fromItem').text(from);
                $('#toItem').text(to);
                $('#totalItems').text(total);
                $('#currentPage').text(currentPage);
                $('#totalPages').text(totalPages);
            }

            // Function to render pagination links
            function renderPagination() {
                const $pagination = $('#pagination');
                $pagination.empty();

                // Previous button
                $pagination.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage - 1}">
            <i class="ti ti-chevron-left"></i>
          </a>
        </li>
      `);

                // Page numbers
                const maxPages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
                let endPage = Math.min(totalPages, startPage + maxPages - 1);

                if (endPage - startPage + 1 < maxPages) {
                    startPage = Math.max(1, endPage - maxPages + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    $pagination.append(`
          <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
          </li>
        `);
                }

                // Next button
                $pagination.append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage + 1}">
            <i class="ti ti-chevron-right"></i>
          </a>
        </li>
      `);

                // Add event listeners to pagination links
                $pagination.find('.page-link').on('click', function(e) {
                    e.preventDefault();
                    if (!$(this).parent().hasClass('disabled')) {
                        currentPage = parseInt($(this).data('page'));
                        loadProducts();
                    }
                });
            }

            // Function to load categories
            function loadCategories() {
                $.ajax({
                    url: '{{ route('api.product-categories.index') }}',
                    type: 'GET',
                    data: {
                        per_page: 100,
                        sort_by: 'name',
                        sort_direction: 'asc'
                    },
                    success: function(response) {
                        const $categorySelect = $('#filter-category');
                        $categorySelect.empty().append('<option value="">Semua Kategori</option>');

                        response.data.forEach(function(category) {
                            $categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                        });

                        // Also update the category dropdown in the add product modal
                        const $modalCategorySelect = $('select[name="category_id"]');
                        $modalCategorySelect.empty().append('<option value="">Pilih Kategori</option>');

                        response.data.forEach(function(category) {
                            $modalCategorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading categories:', xhr.responseText);
                    }
                });
            }

            // Function to load warehouses
            function loadWarehouses() {
                $.ajax({
                    url: '{{ route('api.warehouses.index') }}',
                    type: 'GET',
                    data: {
                        per_page: 100,
                        sort_by: 'name',
                        sort_direction: 'asc'
                    },
                    success: function(response) {
                        const $warehouseSelect = $('#filter-warehouse');
                        $warehouseSelect.empty().append('<option value="">Semua Gudang</option>');

                        response.data.forEach(function(warehouse) {
                            $warehouseSelect.append(`<option value="${warehouse.id}">${warehouse.name}</option>`);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading warehouses:', xhr.responseText);
                    }
                });
            }

            // Add event listener for retry button
            $('#retryButton').on('click', function() {
                loadProducts();
            });

            // Initial load
            loadCategories();
            loadWarehouses();
            loadProducts();


            // Show SweetAlert message if session has success or error
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });

         function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
    </script>
@endsection
