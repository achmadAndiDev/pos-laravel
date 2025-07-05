@extends('admin.layouts.app')

@section('title', 'Order')
@section('subtitle', 'Manajemen Data Order')

@section('styles')
<style>
  /* Card styling */
  .card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease-in-out;
  }
  
  .status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 50rem;
  }
  
  .filter-card {
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .filter-card.active {
    border-color: var(--tblr-primary);
    background-color: rgba(32, 107, 196, 0.03);
  }
  
  .filter-card .card-body {
    padding: 1rem;
  }
  
  .filter-card:hover {
    transform: translateY(-3px);
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addOrderModal">
    <i class="ti ti-plus"></i>
    Tambah Order
  </a>
  <a href="#" class="btn btn-primary d-sm-none" data-bs-toggle="modal" data-bs-target="#addOrderModal">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <!-- Filter Cards -->
  <div class="col-12 mb-3">
    <div class="row g-3">
      <!-- Semua Order -->
      <div class="col-md-4">
        <div class="card filter-card card-hover active" id="filter-all">
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
      
      <!-- Order On Hold -->
      <div class="col-md-4">
        <div class="card filter-card card-hover" id="filter-on-hold">
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
      
      <!-- Order Cancelled -->
      <div class="col-md-4">
        <div class="card filter-card card-hover" id="filter-cancelled">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-red text-white avatar">
                  <i class="ti ti-x"></i>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">
                  <span id="cancelled-orders">0</span> Order
                </div>
                <div class="text-muted">
                  Order Cancelled
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Order List Container -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Order</h3>
      </div>
      <div class="card-body">
        <div id="orders-table-container">
          <!-- Table will be loaded here -->
          <div class="text-center py-4" id="loading-indicator">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variables
    let currentFilter = 'all';
    
    // Load initial data
    loadOrderCounts();
    
    // Filter card click handlers
    document.querySelectorAll('.filter-card').forEach(card => {
      card.addEventListener('click', function() {
        // Remove active class from all cards
        document.querySelectorAll('.filter-card').forEach(c => {
          c.classList.remove('active');
        });
        
        // Add active class to clicked card
        this.classList.add('active');
        
        // Get filter type from card id
        const filterId = this.id;
        currentFilter = filterId.replace('filter-', '');
        
        // Load orders with the selected filter
        loadOrders(currentFilter);
      });
    });
    
    // Function to load order counts
    function loadOrderCounts() {
      fetch('/api/orders/counts')
        .then(response => response.json())
        .then(data => {
          document.getElementById('total-orders').textContent = data.total || 0;
          document.getElementById('on-hold-orders').textContent = data.onHold || 0;
          document.getElementById('cancelled-orders').textContent = data.cancelled || 0;
        })
        .catch(error => {
          console.error('Error loading order counts:', error);
          toastr.error('Gagal memuat jumlah order');
        });
    }
    
    // Function to load orders based on filter
    function loadOrders(filter = 'all') {
      // Show loading indicator
      document.getElementById('loading-indicator').classList.remove('d-none');
      
      // Fetch orders based on filter
      let url = '/api/orders';
      
      if (filter === 'on-hold') {
        url += '?filter=on-hold';
      } else if (filter === 'cancelled') {
        url += '?filter=cancelled';
      }
      
      // Load orders
      fetch(url)
        .then(response => response.json())
        .then(data => {
          // Hide loading indicator
          document.getElementById('loading-indicator').classList.add('d-none');
          
          // Render orders table
          // This will be implemented in the next phase
        })
        .catch(error => {
          console.error('Error loading orders:', error);
          document.getElementById('loading-indicator').classList.add('d-none');
          toastr.error('Gagal memuat data order');
        });
    }
    
    // Load initial orders
    loadOrders();
  });
</script>
@endsection