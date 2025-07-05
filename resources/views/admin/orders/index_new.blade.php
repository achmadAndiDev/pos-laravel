@extends('admin.layouts.app')

@section('title', 'Order')
@section('subtitle', 'Manajemen Data Order')

@section('right-header')
<div class="btn-list">
  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addOrderModal">
    <i class="ti ti-plus"></i>
    Tambah Order
  </a>
  <a href="#" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-file-export"></i>
    Export Data
  </a>
  <a href="#" class="btn btn-primary d-sm-none" data-bs-toggle="modal" data-bs-target="#addOrderModal">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <!-- Statistik Order -->
  <div class="col-md-12 mb-3">
    <div class="row row-cards">
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
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
                  Total Order
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-yellow text-white avatar">
                  <i class="ti ti-clock"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="pending-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Menunggu Pembayaran
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-azure text-white avatar">
                  <i class="ti ti-package"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="processing-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Dalam Proses
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
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
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Order -->
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Filter Order</h3>
      </div>
      <div class="card-body">
        <form id="order-filter-form">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Status Order</label>
              <select class="form-select" name="order_status" id="order_status">
                <option value="">Semua Status</option>
                <option value="PENDING_PAYMENT">Menunggu Pembayaran</option>
                <option value="PROCESSING">Diproses</option>
                <option value="SHIPPED">Dikirim</option>
                <option value="DELIVERED">Diterima</option>
                <option value="COMPLETED">Selesai</option>
                <option value="CANCELLED">Dibatalkan</option>
                <option value="REFUNDED">Dikembalikan</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status Pembayaran</label>
              <select class="form-select" name="payment_status" id="payment_status">
                <option value="">Semua Status</option>
                <option value="UNPAID">Belum Dibayar</option>
                <option value="INSTALLMENT">Sebagian Dibayar</option>
                <option value="PAID">Lunas</option>
                <option value="REFUNDED">Dikembalikan</option>
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
              <label class="form-label">Jumlah Per Halaman</label>
              <select class="form-select" name="per_page" id="per_page">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <div class="col-md-12 text-end">
              <button type="button" id="filter-button" class="btn btn-primary">
                <i class="ti ti-filter me-1"></i>
                Filter
              </button>
              <button type="button" id="reset-filter-button" class="btn btn-outline-secondary">
                <i class="ti ti-refresh me-1"></i>
                Reset
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
            <button class="btn btn-primary btn-sm view-mode" data-mode="card">
              <i class="ti ti-layout-grid me-1"></i>
              Card View
            </button>
            <button class="btn btn-outline-secondary btn-sm view-mode" data-mode="list">
              <i class="ti ti-layout-list me-1"></i>
              List View
            </button>
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
          <ul class="pagination" id="pagination">
            <!-- Pagination will be dynamically inserted here -->
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Template untuk Card Order -->
<template id="order-card-template">
  <div class="col-12 mb-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="avatar avatar-md bg-status-color">
              <i class="ti ti-shopping-cart text-status-icon"></i>
            </div>
          </div>
          <div class="col-md-4">
            <div class="text-truncate">
              <strong class="order-id"></strong>
              <span class="badge ms-2 order-status-badge"></span>
            </div>
            <div class="d-flex align-items-center mt-1">
              <span class="text-muted me-2"><i class="ti ti-calendar-event me-1"></i> <span class="order-date"></span></span>
              <span class="text-muted"><i class="ti ti-user me-1"></i> <span class="customer-name"></span></span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex flex-column">
              <div class="text-muted">
                <i class="ti ti-map-pin me-1"></i> <span class="customer-location"></span>
              </div>
              <div class="text-muted">
                <i class="ti ti-building-store me-1"></i> <span class="order-source"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="d-flex flex-column">
              <div>
                <strong class="order-amount"></strong>
              </div>
              <div class="text-muted">
                <i class="ti ti-credit-card me-1"></i> <span class="payment-status"></span>
              </div>
              <div class="text-muted">
                <i class="ti ti-package me-1"></i> <span class="product-count"></span> produk
              </div>
            </div>
          </div>
          <div class="col-md-auto ms-auto">
            <div class="btn-list">
              <a href="#" class="btn btn-sm btn-info order-detail-btn">
                <i class="ti ti-eye me-1"></i>
                Detail
              </a>
              <button class="btn btn-sm btn-warning order-edit-btn">
                <i class="ti ti-edit me-1"></i>
                Edit
              </button>
              <button class="btn btn-sm btn-danger order-delete-btn">
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

@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Variabel global
    let currentPage = 1;
    let totalPages = 1;
    let currentViewMode = 'card';
    let orderSources = [];
    
    // Inisialisasi
    loadOrderSources();
    loadOrders();
    loadOrderStats();
    
    // Event listeners
    $('#filter-button').on('click', function() {
      currentPage = 1;
      loadOrders();
    });
    
    $('#reset-filter-button, #reset-filter-empty').on('click', function() {
      resetFilters();
      loadOrders();
    });
    
    $('.view-mode').on('click', function() {
      const mode = $(this).data('mode');
      setViewMode(mode);
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
        dropdown.append(`<option value="${source.id}">${source.name}</option>`);
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
            $('#pending-orders').text(response.data.pending || 0);
            $('#processing-orders').text(response.data.processing || 0);
            $('#completed-orders').text(response.data.completed || 0);
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
            const orders = response.data.data || [];
            const meta = response.data.meta || {};
            
            if (orders.length === 0) {
              showEmptyState();
            } else {
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
      const formData = $('#order-filter-form').serializeArray();
      const filters = {
        page: currentPage,
      };
      
      formData.forEach(function(item) {
        if (item.value) {
          filters[item.name] = item.value;
        }
      });
      
      return filters;
    }
    
    // Fungsi untuk reset filter
    function resetFilters() {
      $('#order-filter-form')[0].reset();
      currentPage = 1;
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
        const template = document.getElementById('order-card-template');
        const card = document.importNode(template.content, true);
        
        // Set status color
        const statusColor = getStatusColor(order.order_status);
        $(card).find('.avatar').removeClass('bg-status-color').addClass(statusColor.bg);
        $(card).find('.ti-shopping-cart').removeClass('text-status-icon').addClass(statusColor.text);
        
        // Set order details
        $(card).find('.order-id').text('#' + order.order_id);
        $(card).find('.order-status-badge')
          .text(getStatusLabel(order.order_status))
          .addClass(statusColor.badge);
        
        $(card).find('.order-date').text(formatDate(order.order_date));
        $(card).find('.customer-name').text(order.customer?.name || 'N/A');
        
        const location = order.receiver?.address?.city || 'N/A';
        $(card).find('.customer-location').text(location);
        
        const sourceName = getOrderSourceName(order.order_source_id);
        $(card).find('.order-source').text(sourceName);
        
        $(card).find('.order-amount').text(formatCurrency(order.total_amount));
        $(card).find('.payment-status').text(getPaymentStatusLabel(order.payment_status));
        $(card).find('.product-count').text(order.products?.length || 0);
        
        // Set action buttons
        $(card).find('.order-detail-btn').attr('href', `/admin/orders/${order.id}`);
        $(card).find('.order-edit-btn').attr('data-id', order.id);
        $(card).find('.order-delete-btn').attr('data-id', order.id);
        
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
      $('.order-edit-btn').on('click', function() {
        const orderId = $(this).data('id');
        window.location.href = `/admin/orders/${orderId}/edit`;
      });
      
      $('.order-delete-btn').on('click', function() {
        const orderId = $(this).data('id');
        confirmDeleteOrder(orderId);
      });
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
      const container = $('#pagination');
      container.empty();
      
      if (!meta || !meta.links || meta.links.length <= 3) {
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
        case 'PENDING_PAYMENT':
          return { bg: 'bg-yellow-lt', text: 'text-yellow', badge: 'bg-yellow' };
        case 'PROCESSING':
          return { bg: 'bg-azure-lt', text: 'text-azure', badge: 'bg-azure' };
        case 'SHIPPED':
          return { bg: 'bg-blue-lt', text: 'text-blue', badge: 'bg-blue' };
        case 'DELIVERED':
          return { bg: 'bg-indigo-lt', text: 'text-indigo', badge: 'bg-indigo' };
        case 'COMPLETED':
          return { bg: 'bg-green-lt', text: 'text-green', badge: 'bg-green' };
        case 'CANCELLED':
          return { bg: 'bg-red-lt', text: 'text-red', badge: 'bg-red' };
        case 'REFUNDED':
          return { bg: 'bg-orange-lt', text: 'text-orange', badge: 'bg-orange' };
        default:
          return { bg: 'bg-gray-lt', text: 'text-gray', badge: 'bg-gray' };
      }
    }
    
    // Fungsi untuk mendapatkan label status
    function getStatusLabel(status) {
      switch (status) {
        case 'PENDING_PAYMENT':
          return 'Menunggu Pembayaran';
        case 'PROCESSING':
          return 'Diproses';
        case 'SHIPPED':
          return 'Dikirim';
        case 'DELIVERED':
          return 'Diterima';
        case 'COMPLETED':
          return 'Selesai';
        case 'CANCELLED':
          return 'Dibatalkan';
        case 'REFUNDED':
          return 'Dikembalikan';
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
@endpush