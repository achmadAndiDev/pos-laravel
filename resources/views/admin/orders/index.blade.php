@extends('admin.layouts.app')

@section('title', 'Order')
@section('subtitle', 'Manajemen Data Order')

@section('css')
<style>
  .filter-section {
    transition: all 0.3s ease;
  }
  
  /* Status Icon Styles */
  .status-icon-wrapper {
    text-align: center;
    position: relative;
  }
  
  .status-icon-wrapper:not(:last-child):after {
    content: '';
    position: absolute;
    top: 12px;
    right: -15px;
    width: 10px;
    height: 2px;
    background-color: #e6e7e9;
  }
  
  .status-icon-wrapper:nth-child(1):after,
  .status-icon-wrapper:nth-child(2):after,
  .status-icon-wrapper:nth-child(3):after {
    width: 20px;
    right: -20px;
  }
  
  .status-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #f5f7fb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
    border: 1px solid #e6e7e9;
  }
  
  .status-icon i {
    font-size: 16px;
  }
  
  .status-text {
    font-size: 10px;
    white-space: nowrap;
  }
  
  /* Active status */
  .status-icon-wrapper .status-icon i.text-primary {
    color: #206bc4 !important;
  }
  
  .status-icon-wrapper .status-text.text-primary {
    color: #206bc4 !important;
    font-weight: 600;
  }
  
  /* Order Stats Card Styles */
  .order-stats-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }
  
  .order-stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    background-color: rgba(248, 249, 250, 0.9);
  }
  
  .order-stats-card.active {
    border-color: #206bc4;
    /* background-color: rgba(32, 107, 196, 0.05); */
  }
  
  .order-stats-card.active .font-weight-medium {
    color: #206bc4;
    font-weight: 600;
  }
  
  .order-stats-card.active-yellow {
    border-color: #f59f00;
    /* background-color: rgba(245, 159, 0, 0.05); */
  }
  
  .order-stats-card.active-yellow .font-weight-medium {
    color: #f59f00;
    font-weight: 600;
  }
  
  .order-stats-card.active-red {
    border-color: #d63939;
    /* background-color: rgba(214, 57, 57, 0.05); */
  }
  
  .order-stats-card.active-red .font-weight-medium {
    color: #d63939;
    font-weight: 600;
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.orders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-plus"></i>
    Tambah Order
  </a>
  {{-- <a href="#" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-file-export"></i>
    Export Data
  </a> --}}
  <a href="{{ route('admin.orders.create') }}" class="btn btn-primary d-sm-none">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <!-- Statistik Order -->
  <div class="col-md-12 mb-3">
    <div class="row row-cards">
      <div class="col-sm-6 col-lg-4">
        <div class="card card-sm order-stats-card" id="all-orders-card" data-order-type="all">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-primary text-white avatar">
                  <i class="ti ti-shopping-cart"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="total-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Semua Order
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-4">
        <div class="card card-sm order-stats-card" id="on-hold-orders-card" data-order-type="on_hold">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-yellow text-white avatar">
                  <i class="ti ti-clock"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="on-hold-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Order On Hold
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-4">
        <div class="card card-sm order-stats-card" id="cancelled-orders-card" data-order-type="cancelled">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-red text-white avatar">
                  <i class="ti ti-cancel"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="cancelled-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Order Canceled
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-green text-white avatar">
                  <i class="ti ti-check"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="completed-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Selesai
                </div>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>

  <!-- Filter Order -->
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Filter Order</h3>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="toggle-filter">
          <i class="ti ti-filter me-1"></i> <i class="ti ti-chevron-down" id="toggle-filter-icon"></i>
        </button>
      </div>
      
      <!-- Quick Search Bar -->
      <div class="card-body border-bottom pb-3">
        <form id="quick-search-form" class="d-flex flex-wrap align-items-center gap-2">
          <div class="flex-grow-1 me-2">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ti ti-search"></i>
              </span>
              <select class="form-select flex-grow-0" style="min-width: 150px;" name="search_field" id="search_field">
                <option value="order_id">Order ID</option>
                <option value="customer_name">Nama Customer</option>
                <option value="tracking_number">No Resi</option>
                <option value="product_name">Nama Produk</option>
              </select>
              <input type="text" class="form-control" name="search_query" id="search_query" placeholder="Cari...">
            </div>
          </div>
          <div>
            <button type="button" id="quick-search-button" class="btn btn-primary">
              <i class="ti ti-search me-1"></i>
              Cari
            </button>
          </div>
        </form>
      </div>
      
      <!-- Advanced Filter (Collapsible) -->
      <div class="card-body pt-0" id="advanced-filter" style="display: none;">
        <form id="order-filter-form" class="mt-3">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Status Order</label>
              <select class="form-select" name="order_status" id="order_status">
                <option value="">Semua Status</option>
                <option value="UNPAID">Belum Dibayar</option>
                <option value="ACCEPTED">Diterima</option>
                <option value="REJECTED">Ditolak</option>
                <option value="CANCELLED">Dibatalkan</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status Pembayaran</label>
              <select class="form-select" name="payment_status" id="payment_status">
                <option value="">Semua Status</option>
                <option value="UNPAID">Belum Dibayar</option>
                <option value="INSTALLMENT">Sebagian Dibayar</option>
                <option value="PAID">Lunas</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Sumber Order</label>
              <select class="form-select" name="order_source_id" id="order_source_id">
                <option value="">Semua Sumber</option>
                <!-- Akan diisi dari API -->
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">ID Order</label>
              <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Cari ID Order">
            </div>
            <div class="col-md-3">
              <label class="form-label">Jenis Tanggal</label>
              <select class="form-select" name="date_type" id="date_type">
                <option value="order_date">Tanggal Order</option>
                <option value="payment_date">Tanggal Pembayaran</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" name="date_from" id="date_from">
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" name="date_to" id="date_to">
            </div>
            <div class="col-md-3">
              <label class="form-label">Customer</label>
              <input type="text" class="form-control" name="customer_id" id="customer_id" placeholder="ID Customer">
            </div>
            <div class="col-md-3">
              <label class="form-label">Gudang</label>
              <select class="form-select" name="warehouse_id" id="warehouse_id">
                <option value="">Semua Gudang</option>
                <!-- Akan diisi dari API -->
              </select>
            </div>

            <div class="col-md-12 text-end mt-3">
              <button type="button" id="reset-filter-button" class="btn btn-outline-secondary me-2">
                <i class="ti ti-refresh me-1"></i>
                Reset
              </button>
              <button type="button" id="filter-button" class="btn btn-primary">
                <i class="ti ti-filter me-1"></i>
                Filter
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Daftar Order (Card View) -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Order</h3>
        <div class="card-actions">
          <div class="btn-group">
            <button class="btn btn-primary btn-sm view-mode d-none" data-mode="card">
              {{-- <i class="ti ti-layout-grid me-1"></i>
              Card View
            </button>
            <button class="btn btn-outline-secondary btn-sm view-mode" data-mode="list">
              <i class="ti ti-layout-list me-1"></i>
              List View
            </button> --}}
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- Loading indicator -->
        <div id="loading-indicator" class="text-center py-5 d-none">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Memuat data order...</p>
        </div>
        
        <!-- Empty state -->
        <div id="empty-state" class="text-center py-5 d-none">
          <div class="empty">
            <div class="empty-icon">
              <i class="ti ti-shopping-cart text-muted" style="font-size: 3rem;"></i>
            </div>
            <p class="empty-title">Tidak ada order</p>
            <p class="empty-subtitle text-muted">
              Tidak ada order yang ditemukan dengan filter yang dipilih.
            </p>
            <div class="empty-action">
              <button class="btn btn-primary" id="reset-filter-empty">
                <i class="ti ti-refresh me-1"></i>
                Reset Filter
              </button>
            </div>
          </div>
        </div>
        
        <!-- Order list container -->
        <div id="order-list" class="row">
          <!-- Order cards will be dynamically inserted here -->
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted" id="pagination-info">
            Menampilkan 0 dari 0 order
          </div>
          <div class="d-flex align-items-center">
            <div class="me-3">
              <select class="form-select form-select-sm" name="per_page" id="per_page">
                <option value="10">10 </option>
                <option value="25">25 </option>
                <option value="50">50 </option>
              </select>
            </div>
            <ul class="pagination" id="pagination">
              <!-- Pagination will be dynamically inserted here -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Template untuk Card Order -->
<template id="order-card-template">
  <div class="col-12 mb-3">
    <div class="card">
      <div class="card-body">
        <!-- Baris 1: Nomor PO dan Status Order (Step Bar) -->
        <div class="row mb-3">
          <div class="col-md-4">
            <a href="#" class="po-number-link">
              <h3 class="mb-0 po-number"></h3>
            </a>
          </div>
          
          <div class="col-md-8">
            <div class="d-flex justify-content-end">
              <div class="order-status-icons d-flex align-items-center">
                <div class="status-icon-wrapper me-3" data-status="PENDING_PAYMENT">
                  <div class="status-icon">
                    <i class="ti ti-wallet text-muted"></i>
                  </div>
                  <div class="status-text small text-muted">Lunas</div>
                </div>
                <div class="status-icon-wrapper me-3" data-status="PROCESSING">
                  <div class="status-icon">
                    <i class="ti ti-package text-muted"></i>
                  </div>
                  <div class="status-text small text-muted">Diproses</div>
                </div>
                <div class="status-icon-wrapper me-3" data-status="SHIPPED">
                  <div class="status-icon">
                    <i class="ti ti-truck-delivery text-muted"></i>
                  </div>
                  <div class="status-text small text-muted">Dikirim</div>
                </div>
                <div class="status-icon-wrapper" data-status="DELIVERED">
                  <div class="status-icon">
                    <i class="ti ti-check text-muted"></i>
                  </div>
                  <div class="status-text small text-muted">Diterima</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Baris 2: Informasi Pemesan, Pembayaran, dan Produk -->
        <div class="row mb-3">
          <!-- Kiri: Informasi Pemesan dan Penerima -->
          <div class="col-md-4">
            <div class="d-flex flex-column">
              <span class="text-muted">Pemesan</span>
              <strong class="customer-name mb-2"></strong>
              
              <span class="text-muted">Dikirim Kepada</span>
              <strong class="receiver-name mb-2"></strong>
              
              <span class="text-muted d-none">Admin</span>
              <strong class="admin-name d-none"></strong>
            </div>
          </div>
          
          <!-- Tengah: Status Pembayaran dan Total -->
          <div class="col-md-4">
            <div class="d-flex flex-column h-100">
              <div class="mb-2">
                <span class="text-muted">Status Pembayaran</span>
                <div>
                  <span class="badge payment-status-badge me-1 text-white"></span>
                  <strong class="order-amount"></strong>
                </div>
              </div>
              <div class="mt-auto">
                <button class="btn btn-sm btn-outline-primary transaction-history-btn">
                  <i class="ti ti-history me-1"></i>
                  Lihat Riwayat Transaksi
                </button>
                <div class="mt-2 awb-number-container">
                  <small class="text-muted d-block">Nomor Resi:</small>
                  <span class="awb-number fw-bold"></span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Kanan: Produk yang Dibeli -->
          <div class="col-md-4">
            <div class="d-flex flex-column">
              <span class="text-muted">
                <i class="ti ti-package me-1"></i>
                <span class="product-count"></span> Produk
              </span>
              <div class="product-list mt-2">
                <!-- Produk akan ditampilkan di sini (max 3) -->
              </div>
              <div class="product-more mt-1 d-none">
                <small class="text-muted">+ <span class="more-product-count"></span> produk lainnya</small>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Baris 3: Tombol Aksi -->
        <div class="row">
          <div class="col-md-4">
            <button class="btn btn-outline-secondary print-order-btn">
              <i class="ti ti-printer me-1"></i>
              Print
            </button>
          </div>
          <div class="col-md-8">
            <div class="d-flex justify-content-end gap-2">
              <button class="btn btn-outline-primary update-tracking-btn">
                <i class="ti ti-truck-delivery me-1"></i>
                Update Resi
              </button>
              <button class="btn btn-outline-success mark-delivered-btn d-none">
                <i class="ti ti-check me-1"></i>
                Tandai Diterima
              </button>
              <button class="btn btn-outline-warning edit-order-btn d-none">
                <i class="ti ti-edit me-1"></i>
                Edit Order
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Template untuk List Order -->
<template id="order-list-template">
  <div class="col-12">
    <div class="table-responsive">
      <table class="table table-vcenter card-table">
        <thead>
          <tr>
            <th>ID Order</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Sumber</th>
            <th>Total</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th class="w-1"></th>
          </tr>
        </thead>
        <tbody id="order-table-body">
          <!-- Order rows will be dynamically inserted here -->
        </tbody>
      </table>
    </div>
  </div>
</template>

<!-- Template untuk Row Order -->
<template id="order-row-template">
  <tr>
    <td class="order-id-cell"></td>
    <td class="order-date-cell"></td>
    <td class="customer-name-cell"></td>
    <td class="order-source-cell"></td>
    <td class="order-amount-cell"></td>
    <td class="order-status-cell"></td>
    <td class="payment-status-cell"></td>
    <td>
      <div class="btn-list flex-nowrap">
        <a href="#" class="btn btn-sm btn-info order-detail-btn">
          <i class="ti ti-eye"></i>
        </a>
        <button class="btn btn-sm btn-warning order-edit-btn">
          <i class="ti ti-edit"></i>
        </button>
        <button class="btn btn-sm btn-danger order-delete-btn">
          <i class="ti ti-trash"></i>
        </button>
      </div>
    </td>
  </tr>
</template>

<!-- Modal Riwayat Pembayaran -->
<div class="modal modal-blur fade" id="payment-history-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Riwayat Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="order-info mb-3">
          <div class="row">
            <div class="col-md-6">
              <div><strong>Nomor PO:</strong> <span id="modal-po-number"></span></div>
              <div><strong>Tanggal Order:</strong> <span id="modal-order-date"></span></div>
              <div><strong>Customer:</strong> <span id="modal-customer-name"></span></div>
            </div>
            <div class="col-md-6 text-end">
              <div><strong>Total Order:</strong> <span id="modal-total-amount"></span></div>
              <div><strong>Status Pembayaran:</strong> <span id="modal-payment-status"></span></div>
            </div>
          </div>
        </div>
        
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Bank</th>
                <th>Status</th>
                <th class="text-end">Nominal</th>
              </tr>
            </thead>
            <tbody id="payment-history-table-body">
              <!-- Payment history rows will be dynamically inserted here -->
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Total Pembayaran:</th>
                <th class="text-end" id="modal-total-payment"></th>
              </tr>
              <tr>
                <th colspan="4" class="text-end">Sisa Pembayaran:</th>
                <th class="text-end" id="modal-remaining-payment"></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Update Resi Pengiriman -->
<div class="modal modal-blur fade" id="update-tracking-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Resi Pengiriman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="order-info mb-3">
          <div class="row">
            <div class="col-md-12">
              <div><strong>Nomor PO:</strong> <span id="tracking-modal-po-number"></span></div>
              <div><strong>Customer:</strong> <span id="tracking-modal-customer-name"></span></div>
            </div>
          </div>
        </div>
        
        <form id="update-tracking-form">
          <input type="hidden" id="tracking-order-id" name="order_id">
          
          <div class="mb-3">
            <label class="form-label required">Logistik Pengiriman</label>
            <select class="form-select" id="shipping-logistic" name="shipping_logistic" required>
              <option value="">Pilih Logistik</option>
              @foreach($expeditions as $key => $expedition)
                <option value="{{ $expedition['id'] }}">{{ $expedition['name'] }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Nomor Resi</label>
            <input type="text" class="form-control" id="awb-number" name="awb_number" placeholder="Masukkan nomor resi pengiriman" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="save-tracking-btn">Simpan</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Variabel global
    let currentPage = 1;
    let totalPages = 1;
    let currentViewMode = 'card';
    let orderSources = [];
    let warehouses = [];
    
    // Event handler untuk card-card order
    $('.order-stats-card').on('click', function() {
      // Hapus kelas active dari semua card
      $('.order-stats-card').removeClass('active active-yellow active-red');
      
      // Tambahkan kelas active ke card yang diklik
      const orderType = $(this).data('order-type');
      if (orderType === 'all') {
        $(this).addClass('active');
      } else if (orderType === 'on_hold') {
        $(this).addClass('active active-yellow');
      } else if (orderType === 'cancelled') {
        $(this).addClass('active active-red');
      }
      
      // Reset ke halaman pertama
      currentPage = 1;
      
      // Muat ulang data order dengan filter yang baru
      loadOrders();
    });
    let isAdvancedFilterVisible = false;
    
    // Event handler untuk tombol simpan pada modal update resi
    $('#save-tracking-btn').on('click', function() {
      saveTrackingNumber();
    });
    
    // Inisialisasi
    loadOrderSources();
    loadWarehouses();
    loadOrders();
    loadOrderStats();
    
    // Toggle filter event
    $('#toggle-filter').on('click', function() {
      isAdvancedFilterVisible = !isAdvancedFilterVisible;
      if (isAdvancedFilterVisible) {
        $('#advanced-filter').slideDown(300);
        $('#toggle-filter-icon').removeClass('ti-chevron-down').addClass('ti-chevron-up');
      } else {
        $('#advanced-filter').slideUp(300);
        $('#toggle-filter-icon').removeClass('ti-chevron-up').addClass('ti-chevron-down');
      }
    });
    
    // Quick search event
    $('#quick-search-button').on('click', function() {
      const searchField = $('#search_field').val();
      const searchQuery = $('#search_query').val();
      
      if (searchQuery.trim() !== '') {
        // Reset halaman ke 1 untuk pencarian baru
        currentPage = 1;
        
        // Bersihkan filter lain jika ada
        // resetFilters();
        
        // Set nilai pencarian ke filter yang sesuai
        switch (searchField) {
          case 'order_id':
            $('#order_id').val(searchQuery);
            break;
          case 'customer_name':
            $('#customer_id').val(searchQuery);
            break;
          case 'tracking_number':
            // Tambahkan field tracking_number jika belum ada di form filter
            if (!$('#tracking_number').length) {
              $('<input>').attr({
                type: 'hidden',
                id: 'tracking_number',
                name: 'tracking_number',
                value: searchQuery
              }).appendTo('#order-filter-form');
            } else {
              $('#tracking_number').val(searchQuery);
            }
            break;
          case 'product_name':
            // Tambahkan field product_name jika belum ada di form filter
            if (!$('#product_name').length) {
              $('<input>').attr({
                type: 'hidden',
                id: 'product_name',
                name: 'product_name',
                value: searchQuery
              }).appendTo('#order-filter-form');
            } else {
              $('#product_name').val(searchQuery);
            }
            break;
        }
        
        // Jalankan pencarian
        loadOrders();
      }
    });
    
    // Enter key pada input pencarian
    $('#search_query').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        $('#quick-search-button').click();
      }
    });
    
    // Event listeners untuk filter
    $('#filter-button').on('click', function() {
      currentPage = 1;
      loadOrders();
    });
    
    $('#reset-filter-button, #reset-filter-empty').on('click', function() {
      resetFilters();
      $('#search_query').val(''); // Reset juga input pencarian cepat
      loadOrders();
    });
    
    $('.view-mode').on('click', function() {
      const mode = $(this).data('mode');
      setViewMode(mode);
      loadOrders();
    });
    
    // Event listener untuk perubahan jumlah item per halaman
    $('#per_page').on('change', function() {
      currentPage = 1; // Reset ke halaman pertama saat mengubah jumlah per halaman
      loadOrders();
    });
    
    // Fungsi untuk memuat sumber order
    function loadOrderSources() {
      $.ajax({
        url: '/api/order-sources',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data) {
            orderSources = response.data;
            populateOrderSourcesDropdown(orderSources);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading order sources:', error);
        }
      });
    }
    
    // Fungsi untuk mengisi dropdown sumber order
    function populateOrderSourcesDropdown(sources) {
      const dropdown = $('#order_source_id');
      dropdown.find('option:not(:first)').remove();
      
      sources.forEach(function(source) {
        // Gunakan format name yang sudah dibuat di OrderSourceResource
        dropdown.append(`<option value="${source.id}">${source.name}</option>`);
      });
    }
    
    // Fungsi untuk memuat data gudang/warehouse
    function loadWarehouses() {
      $.ajax({
        url: '/api/warehouses',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.data) {
            warehouses = response.data;
            populateWarehousesDropdown(warehouses);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading warehouses:', error);
        }
      });
    }
    
    // Fungsi untuk mengisi dropdown gudang/warehouse
    function populateWarehousesDropdown(warehouses) {
      const dropdown = $('#warehouse_id');
      dropdown.find('option:not(:first)').remove();
      
      warehouses.forEach(function(warehouse) {
        dropdown.append(`<option value="${warehouse.id}">${warehouse.name}</option>`);
      });
    }
    
    // Fungsi untuk memuat statistik order
    function loadOrderStats() {
      $.ajax({
        url: '/api/orders/stats',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data) {
            $('#total-orders').text(response.data.total || 0);
            $('#on-hold-orders').text(response.data.on_hold || 0);
            $('#cancelled-orders').text(response.data.cancelled || 0);
            // $('#completed-orders').text(response.data.completed || 0);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading order stats:', error);
        }
      });
    }
    
    // Fungsi untuk memuat daftar order
    function loadOrders() {
      showLoading();
      
      const filters = getFilters();
      
      $.ajax({
        url: '/api/orders',
        type: 'GET',
        data: filters,
        dataType: 'json',
        success: function(response) {
          hideLoading();
          
          if (response.success && response.data) {
            const orders = response.data || [];
            const meta = response.meta || {};

            if (orders.length === 0) {
              showEmptyState();
            } else {
              // console.log(JSON.stringify([orders[8]], null, 2));

              hideEmptyState();
              renderOrders(orders);
              renderPagination(meta);
            }
          } else {
            showEmptyState();
          }
        },
        error: function(xhr, status, error) {
          hideLoading();
          showEmptyState();
          console.error('Error loading orders:', error);
        }
      });
    }
    
    // Fungsi untuk mendapatkan filter dari form
    function getFilters() {
      // Gabungkan data dari form filter dan form pencarian cepat
      const advancedFormData = $('#order-filter-form').serializeArray();
      const quickSearchData = $('#quick-search-form').serializeArray();
      
      // Dapatkan tipe order yang aktif
      const activeOrderType = $('.order-stats-card.active').data('order-type') || 'all';

      // console.log(activeOrderType);
      
      const filters = {
        page: currentPage,
        per_page: $('#per_page').val() || 10, // Ambil nilai per_page dari dropdown pagination
        order_type_list: activeOrderType, // Tambahkan filter berdasarkan tipe order yang aktif
      };
      
      // Proses data dari form filter lanjutan
      advancedFormData.forEach(function(item) {
        if (item.value && item.name !== 'per_page') { // Skip per_page dari form karena sudah diambil di atas
          filters[item.name] = item.value;
        }
      });
      
      // Proses data dari form pencarian cepat jika ada
      const searchField = $('#search_field').val();
      const searchQuery = $('#search_query').val();
      
      if (searchQuery && searchQuery.trim() !== '') {
        // Tambahkan parameter pencarian sesuai dengan field yang dipilih
        switch (searchField) {
          case 'order_id':
            filters.order_id = searchQuery;
            break;
          case 'customer_name':
            filters.customer_name = searchQuery;
            break;
          case 'tracking_number':
            filters.tracking_number = searchQuery;
            break;
          case 'product_name':
            filters.product_name = searchQuery;
            break;
        }
      }
      
      // Proses filter tanggal berdasarkan jenis tanggal yang dipilih
      if (filters.date_from || filters.date_to) {
        const dateType = filters.date_type || 'order_date';
        
        // Hapus properti date_type dari filters karena sudah diproses
        delete filters.date_type;
        
        // Tambahkan prefix jenis tanggal ke parameter date_from dan date_to
        if (filters.date_from) {
          filters[dateType + '_from'] = filters.date_from;
          delete filters.date_from;
        }
        
        if (filters.date_to) {
          filters[dateType + '_to'] = filters.date_to;
          delete filters.date_to;
        }
      }
      
      return filters;
    }
    
    // Fungsi untuk reset filter
    function resetFilters() {
      // Reset form filter lanjutan
      $('#order-filter-form')[0].reset();
      
      // Reset form pencarian cepat
      $('#quick-search-form')[0].reset();
      
      // Hapus input tersembunyi yang mungkin ditambahkan secara dinamis
      $('#tracking_number, #product_name').remove();
      
      // Reset halaman ke 1
      currentPage = 1;
    }
    

    function showToast(type, message) {
      Swal.fire({
          title: 'Berhasil!',
          text: message,
          icon: type,
      });
    }
          
    // Fungsi untuk menampilkan loading
    function showLoading() {
      $('#loading-indicator').removeClass('d-none');
      $('#order-list').addClass('d-none');
      $('#empty-state').addClass('d-none');
    }
    
    // Fungsi untuk menyembunyikan loading
    function hideLoading() {
      $('#loading-indicator').addClass('d-none');
      $('#order-list').removeClass('d-none');
    }
    
    // Fungsi untuk menampilkan empty state
    function showEmptyState() {
      $('#empty-state').removeClass('d-none');
      $('#order-list').addClass('d-none');
    }
    
    // Fungsi untuk menyembunyikan empty state
    function hideEmptyState() {
      $('#empty-state').addClass('d-none');
      $('#order-list').removeClass('d-none');
    }
    
    // Fungsi untuk mengatur mode tampilan
    function setViewMode(mode) {
      currentViewMode = mode;
      
      $('.view-mode').removeClass('btn-primary btn-outline-secondary').addClass('btn-outline-secondary');
      $(`.view-mode[data-mode="${mode}"]`).removeClass('btn-outline-secondary').addClass('btn-primary');
      
      if (mode === 'list') {
        const listTemplate = document.getElementById('order-list-template');
        const listContent = document.importNode(listTemplate.content, true);
        $('#order-list').empty().append(listContent);
      } else {
        $('#order-list').empty();
      }
    }
    
    // Fungsi untuk render daftar order
    function renderOrders(orders) {
      if (currentViewMode === 'card') {
        renderOrderCards(orders);
      } else {
        renderOrderList(orders);
      }
    }
    
    // Fungsi untuk render order dalam bentuk card
    function renderOrderCards(orders) {
      const container = $('#order-list');
      container.empty();
      
      orders.forEach(function(order) {
        // console.log(order);
        const template = document.getElementById('order-card-template');
        const card = document.importNode(template.content, true);
        
        // Baris 1: Nomor PO dan Status Order (Icons)
        const poNumber = order.po_number || `#${order.order_id}`;
        $(card).find('.po-number').text(poNumber);
        $(card).find('.po-number-link').attr('href', `/admin/orders/${order.id}`);
        
        // Set status icons based on payment_status and order_progress_status
        const paymentStatus = order.payment_status || 'UNPAID';
        const progressStatus = order.order_progress_status || 'PENDING';
        
        // Map order status to our status steps
        let currentStep = 0;
        
        if (paymentStatus === 'PAID') {
          currentStep = 1; // At least step 1 (Paid) 
        }
        if (progressStatus === 'ON PROCESS') {
          currentStep = 2; // Step 2 (Processing)
        } else if (progressStatus === 'SHIPPED' || progressStatus.includes('SHIP') || progressStatus.includes('SHIPING')) {
          currentStep = 3; // Step 3 (Shipped)
        } else if (progressStatus === 'DELIVERED' || order.order_status === 'DELIVERED' || order.order_status === 'COMPLETED') {
          currentStep = 4; // Step 4 (Delivered)
        }
        
        // Apply active status to icons
        $(card).find('.status-icon-wrapper').each(function(index) {
          if (index < currentStep) {
            $(this).find('.status-icon i').removeClass('text-muted').addClass('text-primary');
            $(this).find('.status-text').removeClass('text-muted').addClass('text-primary');
          }
        });
        
        // Baris 2: Informasi Pemesan, Pembayaran, dan Produk
        // Kiri: Informasi Pemesan dan Penerima
        $(card).find('.customer-name').text(order.customer?.name || 'N/A');
        $(card).find('.receiver-name').text(order.receiver?.name || order.customer?.name || 'N/A');
        
        // Admin yang update
        const adminName = order.updated_by?.name || order.created_by?.name || 'N/A';
        $(card).find('.admin-name').text(adminName);
        
        // Tengah: Status Pembayaran dan Total
        const paymentStatusColor = getPaymentStatusColor(order.payment_status);
        $(card).find('.payment-status-badge')
          .text(getPaymentStatusLabel(order.payment_status))
          .addClass(paymentStatusColor);
        $(card).find('.order-amount').text(formatCurrency(order.total_amount));
        
        // Set data untuk modal riwayat transaksi
        $(card).find('.transaction-history-btn')
          .attr('data-id', order.id)
          .attr('data-order', JSON.stringify(order));
          
        // Tampilkan nomor resi jika ada
        if (order.awb_number) {
          // console.log(order);
          const awbContainer = $(card).find('.awb-number-container');
          $(card).find('.awb-number').text(order.shipping_logistic + ' - ' + order.awb_number);
          awbContainer.removeClass('d-none');
        } else {
          $(card).find('.awb-number-container').addClass('d-none');
        }
        
        // Kanan: Produk yang Dibeli
        const productCount = order.products?.length || 0;
        $(card).find('.product-count').text(productCount);
        
        // Tampilkan maksimal 3 produk
        const productList = $(card).find('.product-list');
        if (order.products && order.products.length > 0) {
          const maxDisplay = Math.min(3, order.products.length);
          
          for (let i = 0; i < maxDisplay; i++) {
            const product = order.products[i];
            const productItem = $('<div class="d-flex align-items-center mb-1"></div>');
            
            productItem.append(`
              <div class="me-2">
                <span class="badge bg-blue-lt">${product.quantity || 1}x</span>
              </div>
              <div class="text-truncate">
                ${product.name || 'Produk'}
              </div>
            `);
            
            productList.append(productItem);
          }
          
          // Jika ada lebih dari 3 produk, tampilkan "++ produk lainnya"
          if (order.products.length > 3) {
            $(card).find('.product-more').removeClass('d-none');
            $(card).find('.more-product-count').text(order.products.length - 3);
          }
        }
        
        // Baris 3: Tombol Aksi
        $(card).find('.print-order-btn').attr('data-id', order.id);
        
        // Ubah warna tombol print berdasarkan status is_printed
        if (order.is_printed) {
          $(card).find('.print-order-btn')
            .removeClass('btn-outline-info')
            .addClass('btn-outline-secondary');
        } else {
          $(card).find('.print-order-btn')
            .removeClass('btn-outline-secondary')
            .addClass('btn-outline-info');
        }
        
        $(card).find('.update-tracking-btn').attr('data-id', order.id);
        
        // Tampilkan tombol "Tandai Diterima" jika status pembayaran PAID dan status order UNPAID
        if (order.payment_status === 'PAID' && 
            (progressStatus === 'SHIPPED' || progressStatus.includes('SHIP')) && 
            order.order_status !== 'DELIVERED' && 
            order.order_status !== 'COMPLETED') {
          $(card).find('.mark-delivered-btn')
            .removeClass('d-none')
            .attr('data-id', order.id);
        }
        
        // Tampilkan tombol "Edit Order" jika status order UNPAID
        if (order.order_status === 'UNPAID') {
          $(card).find('.edit-order-btn')
            .removeClass('d-none')
            .attr('data-id', order.id);
        }
        
        container.append(card);
      });
      
      // Attach event listeners to buttons
      attachOrderActionListeners();
    }
    
    // Fungsi untuk render order dalam bentuk list
    function renderOrderList(orders) {
      const tbody = $('#order-table-body');
      tbody.empty();
      
      orders.forEach(function(order) {
        const template = document.getElementById('order-row-template');
        const row = document.importNode(template.content, true);
        
        // Set order details
        $(row).find('.order-id-cell').text('#' + order.order_id);
        $(row).find('.order-date-cell').text(formatDate(order.order_date));
        $(row).find('.customer-name-cell').text(order.customer?.name || 'N/A');
        
        const sourceName = getOrderSourceName(order.order_source_id);
        $(row).find('.order-source-cell').text(sourceName);
        
        $(row).find('.order-amount-cell').text(formatCurrency(order.total_amount));
        
        const statusColor = getStatusColor(order.order_status);
        const statusBadge = `<span class="badge ${statusColor.badge}">${getStatusLabel(order.order_status)}</span>`;
        $(row).find('.order-status-cell').html(statusBadge);
        
        const paymentStatusBadge = `<span class="badge ${getPaymentStatusColor(order.payment_status)}">${getPaymentStatusLabel(order.payment_status)}</span>`;
        $(row).find('.payment-status-cell').html(paymentStatusBadge);
        
        // Set action buttons
        $(row).find('.order-detail-btn').attr('href', `/admin/orders/${order.id}`);
        $(row).find('.order-edit-btn').attr('data-id', order.id);
        $(row).find('.order-delete-btn').attr('data-id', order.id);
        
        tbody.append(row);
      });
      
      // Attach event listeners to buttons
      attachOrderActionListeners();
    }
    
    // Fungsi untuk menambahkan event listener ke tombol aksi
    function attachOrderActionListeners() {
      // Edit order button
      $('.edit-order-btn').on('click', function() {
        const orderId = $(this).data('id');
        window.location.href = `/admin/orders/${orderId}/edit`;
      });
      
      // Print order button
      $('.print-order-btn').on('click', function() {
        const orderId = $(this).data('id');
        const printButton = $(this);
        
        // Buka halaman print di tab baru
        window.open(`/admin/orders/${orderId}/print`, '_blank');
      });
      
      // Update tracking button
      $('.update-tracking-btn').on('click', function() {
        const orderId = $(this).data('id');
        showUpdateTrackingModal(orderId);
      });
      
      // Mark as delivered button
      $('.mark-delivered-btn').on('click', function() {
        const orderId = $(this).data('id');
        confirmMarkAsDelivered(orderId);
      });
      
      // Transaction history button
      $('.transaction-history-btn').on('click', function() {
        const orderId = $(this).data('id');
        showTransactionHistoryModal(orderId);
      });
    }
    
    // Fungsi untuk menampilkan modal update resi
    function showUpdateTrackingModal(orderId) {
      // Tampilkan loading
      showLoading();
      
      // Ambil data order
      $.ajax({
        url: `/api/orders/${orderId}`,
        method: 'GET',
        success: function(response) {
          // Sembunyikan loading
          hideLoading();
          
          if (response.success) {
            const order = response.data;
            
            // Isi data order ke modal
            $('#tracking-modal-po-number').text(order.po_number);
            $('#tracking-modal-customer-name').text(order.customer.name);
            $('#tracking-order-id').val(order.id);
            
            // Isi data resi jika sudah ada
            if (order.awb_number) {
              $('#awb-number').val(order.awb_number);
            } else {
              $('#awb-number').val('');
            }
            
            // console.log(order);
            if (order.shipping_id) {
              $('#shipping-logistic').val(order.shipping_id).trigger('change');
            } else {
              $('#shipping-logistic').val('');
            }
            
            // Tampilkan modal
            $('#update-tracking-modal').modal('show');
          } else {
            showToast('error', 'Gagal mengambil data order');
          }
        },
        error: function(xhr) {
          hideLoading();
          showToast('error', 'Terjadi kesalahan saat mengambil data order');
          console.error(xhr);
        }
      });
    }
    
    // Fungsi untuk konfirmasi tandai sebagai diterima
    function confirmMarkAsDelivered(orderId) {
      if (confirm('Apakah Anda yakin ingin menandai order ini sebagai diterima?')) {
        updateOrderStatus(orderId, 'ACCEPTED');
      }
    }
    
    // Fungsi untuk menyimpan nomor resi
    function saveTrackingNumber() {
      // Validasi form
      const form = document.getElementById('update-tracking-form');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Ambil data form
      const orderId = $('#tracking-order-id').val();
      const awbNumber = $('#awb-number').val();
      const shippingLogistic = $('#shipping-logistic').val();
      
      // Tampilkan loading
      showLoading();
      
      // Kirim data ke server
      $.ajax({
        url: `/api/orders/${orderId}/tracking`,
        method: 'POST',
        data: {
          awb_number: awbNumber,
          shipping_logistic: shippingLogistic,
          _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          // Sembunyikan loading
          hideLoading();
          
          if (response.success) {
            // Tutup modal
            $('#update-tracking-modal').modal('hide');
            
            // Tampilkan pesan sukses
            showToast('success', 'Nomor resi berhasil diperbarui');
            
            // Reload data order
            loadOrders();
          } else {
            showToast('error', response.message || 'Gagal memperbarui nomor resi');
          }
        },
        error: function(xhr) {
          hideLoading();
          showToast('error', 'Terjadi kesalahan saat memperbarui nomor resi');
          console.error(xhr);
        }
      });
    }
    
    // Fungsi untuk update status order
    function updateOrderStatus(orderId, status) {
      $.ajax({
        url: `/api/orders/${orderId}/status`,
        type: 'PUT',
        data: { status: status },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert('Status order berhasil diperbarui');
            loadOrders();
            loadOrderStats();
          } else {
            alert('Gagal memperbarui status order: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          alert('Terjadi kesalahan saat memperbarui status order');
          console.error('Error updating order status:', error);
        }
      });
    }
    
    // Fungsi untuk menampilkan modal riwayat transaksi
    function showTransactionHistoryModal(orderId) {
      const btn = $(`.transaction-history-btn[data-id="${orderId}"]`);
      const orderData = JSON.parse(btn.attr('data-order'));
      
      if (!orderData) {
        alert('Data order tidak ditemukan');
        return;
      }
      
      // Set informasi order pada modal
      $('#modal-po-number').text(orderData.po_number || `#${orderData.order_id}`);
      $('#modal-order-date').text(formatDate(orderData.order_date));
      $('#modal-customer-name').text(orderData.customer?.name || 'N/A');
      $('#modal-total-amount').text(formatCurrency(orderData.total_amount));
      
      const paymentStatusColor = getPaymentStatusColor(orderData.payment_status);
      $('#modal-payment-status')
        .text(getPaymentStatusLabel(orderData.payment_status))
        .removeClass()
        .addClass(`badge text-white ${paymentStatusColor}`);
      
      // Tampilkan riwayat pembayaran
      const tableBody = $('#payment-history-table-body');
      tableBody.empty();
      
      let totalPayment = 0;
      
      if (orderData.payment_histories && orderData.payment_histories.length > 0) {
        orderData.payment_histories.forEach(function(payment) {
          const row = $('<tr></tr>');
          
          // Format tanggal pembayaran
          const paymentDate = formatDate(payment.paid_at);
          
          // Bank tujuan
          const bankName = payment.bank?.destination?.bank_name || 'N/A';
          
          // Nominal pembayaran
          const nominal = parseFloat(payment.nominal) || 0;
          
          // Status pembayaran
          let statusHtml = '';
          // console.log(payment);
          if (payment.is_rejected) {
            statusHtml = '<span class="badge text-white bg-danger">Ditolak</span>';
          } else if (payment.is_confirmed) {
            totalPayment += nominal;
            statusHtml = '<span class="badge text-white bg-success">Dikonfirmasi</span>';
          } else {
            statusHtml = '<span class="badge text-white bg-warning">Menunggu</span>';
          }

          row.append(`
            <td>${paymentDate}</td>
            <td>${payment.description || 'Pembayaran'}</td>
            <td>${bankName}</td>
            <td>${statusHtml}</td>
            <td class="text-end">${formatCurrency(nominal)}</td>
          `);
          
          tableBody.append(row);
        });
      } else {
        tableBody.append(`
          <tr>
            <td colspan="5" class="text-center">Belum ada riwayat pembayaran</td>
          </tr>
        `);
      }
      
      // Set total pembayaran
      $('#modal-total-payment').text(formatCurrency(totalPayment));
      
      // Hitung sisa pembayaran
      const totalAmount = parseFloat(orderData.total_amount) || 0;
      const remainingPayment = totalAmount - totalPayment;
      $('#modal-remaining-payment').text(formatCurrency(remainingPayment));
      
      // Tampilkan modal menggunakan jQuery
      $('#payment-history-modal').modal('show');
    }
    
    // Fungsi untuk konfirmasi hapus order
    function confirmDeleteOrder(orderId) {
      if (confirm('Apakah Anda yakin ingin menghapus order ini?')) {
        deleteOrder(orderId);
      }
    }
    
    // Fungsi untuk menghapus order
    function deleteOrder(orderId) {
      $.ajax({
        url: `/api/orders/${orderId}`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert('Order berhasil dihapus');
            loadOrders();
            loadOrderStats();
          } else {
            alert('Gagal menghapus order: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          alert('Terjadi kesalahan saat menghapus order');
          console.error('Error deleting order:', error);
        }
      });
    }
    
    // Fungsi untuk render pagination
    function renderPagination(meta) {

      console.log(meta);

      const container = $('#pagination');
      container.empty();
      
      if (!meta || !meta.links) {
        return;
      }
      
      totalPages = meta.last_page || 1;
      currentPage = meta.current_page || 1;
      
      // Info pagination
      $('#pagination-info').text(`Menampilkan ${meta.from || 0} - ${meta.to || 0} dari ${meta.total || 0} order`);
      
      // Previous button
      if (currentPage > 1) {
        container.append(`
          <li class="page-item">
            <a class="page-link" href="#" data-page="${currentPage - 1}">
              <i class="ti ti-chevron-left"></i>
            </a>
          </li>
        `);
      } else {
        container.append(`
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
              <i class="ti ti-chevron-left"></i>
            </a>
          </li>
        `);
      }
      
      // Page numbers
      const startPage = Math.max(1, currentPage - 2);
      const endPage = Math.min(totalPages, startPage + 4);
      
      for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
          container.append(`
            <li class="page-item active">
              <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
          `);
        } else {
          container.append(`
            <li class="page-item">
              <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
          `);
        }
      }
      
      // Next button
      if (currentPage < totalPages) {
        container.append(`
          <li class="page-item">
            <a class="page-link" href="#" data-page="${currentPage + 1}">
              <i class="ti ti-chevron-right"></i>
            </a>
          </li>
        `);
      } else {
        container.append(`
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
              <i class="ti ti-chevron-right"></i>
            </a>
          </li>
        `);
      }
      
      // Attach click event to pagination links
      $('.page-link').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
          currentPage = page;
          loadOrders();
        }
      });
    }
    
    // Fungsi untuk mendapatkan nama sumber order
    function getOrderSourceName(sourceId) {
      const source = orderSources.find(s => s.id === sourceId);
      return source ? source.name : 'N/A';
    }
    
    // Fungsi untuk mendapatkan warna status
    function getStatusColor(status) {
      switch (status) {
        case 'UNPAID':
          return { bg: 'bg-yellow-lt', text: 'text-yellow', badge: 'bg-yellow' };
        case 'ACCEPTED':
          return { bg: 'bg-green-lt', text: 'text-green', badge: 'bg-green' };
        case 'REJECTED':
          return { bg: 'bg-red-lt', text: 'text-red', badge: 'bg-red' };
        case 'CANCELLED':
          return { bg: 'bg-orange-lt', text: 'text-orange', badge: 'bg-orange' };
        default:
          return { bg: 'bg-gray-lt', text: 'text-gray', badge: 'bg-gray' };
      }
    }
    
    // Fungsi untuk mendapatkan label status
    function getStatusLabel(status) {
      switch (status) {
        case 'UNPAID':
          return 'Belum Dibayar';
        case 'ACCEPTED':
          return 'Diterima';
        case 'REJECTED':
          return 'Ditolak';
        case 'CANCELLED':
          return 'Dibatalkan';
        default:
          return status || 'N/A';
      }
    }
    
    // Fungsi untuk mendapatkan warna status pembayaran
    function getPaymentStatusColor(status) {
      switch (status) {
        case 'UNPAID':
          return 'bg-danger';
        case 'INSTALLMENT':
          return 'bg-warning';
        case 'PAID':
          return 'bg-success';
        case 'REFUNDED':
          return 'bg-info';
        default:
          return 'bg-secondary';
      }
    }
    
    // Fungsi untuk mendapatkan label status pembayaran
    function getPaymentStatusLabel(status) {
      switch (status) {
        case 'UNPAID':
          return 'Belum Dibayar';
        case 'INSTALLMENT':
          return 'Sebagian Dibayar';
        case 'PAID':
          return 'Lunas';
        case 'REFUNDED':
          return 'Dikembalikan';
        default:
          return status || 'N/A';
      }
    }
    
    // Fungsi untuk format tanggal
    function formatDate(dateString) {
      if (!dateString) return 'N/A';
      
      const date = new Date(dateString);
      const day = date.getDate().toString().padStart(2, '0');
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear();
      
      return `${day}/${month}/${year}`;
    }
    
    // Fungsi untuk format mata uang
    function formatCurrency(amount) {
      if (amount === null || amount === undefined) return 'Rp 0';
      
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount);
    }
  });
</script>
@endsection