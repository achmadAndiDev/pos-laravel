@extends('admin.layouts.app')

@section('title', 'Tambah Order Baru')
@section('subtitle', 'Tambah Order Baru')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<style>
  /* Product Card Styles */
  .product-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    cursor: pointer;
  }
  
  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-color: #206bc4;
  }
  
  .product-card .card-title {
    font-size: 0.9rem;
    font-weight: 500;
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .product-card .card-img-top {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background-color: #f8f9fa;
  }
  
  /* Selected Product Styles */
  #selected-product-section {
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    padding: 1rem;
    margin-top: 1rem;
  }
  
  /* Stock Info Styles */
  #stock-info {
    margin-bottom: 0;
  }
  
  /* Product Table Styles */
  .product-row .product-name {
    border: none;
    background: transparent;
    padding: 0;
    font-weight: 500;
    height: auto;
  }
  
  .product-row .product-price,
  .product-row .product-quantity {
    width: 100px;
    margin: 0 auto;
  }
  
  /* Select2 Styles */
  .select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }
  
  .select2-container--default .select2-selection--single.is-invalid {
    border-color: #dc3545 !important;
  }
  
  .select2-container.select2-is-readonly .select2-selection--single,
  .select2-container.select2-is-readonly .select2-selection--multiple {
    pointer-events: none;
    background-color: #e9ecef;
  }
  
  .select2-container.select2-is-readonly .select2-selection__arrow b {
    display: none;
  }
  
  .required:after {
    content: " *";
    color: red;
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali ke Daftar Order
  </a>
  <button type="button" id="saveOrderBtn" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-device-floppy"></i>
    Simpan Order
  </button>
</div>
@endsection

@section('content')
<form id="orderForm">
  @csrf
  <div class="row row-cards">
    <!-- Status Card -->
    <div class="col-12 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <span class="avatar avatar-lg bg-yellow text-white">
                    <i class="ti ti-clock"></i>
                  </span>
                </div>
                <div>
                  <h4 class="mb-1">Buat Order Baru</h4>
                  <div class="text-muted">
                    Dibuat pada {{ date('d M Y, H:i') }}
                    oleh {{ auth()->user()->name }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row g-2">
                <div class="col-4">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="avatar bg-yellow text-white">
                            <i class="ti ti-clock"></i>
                          </span>
                        </div>
                        <div class="col">
                          <div class="text-muted">Status Order</div>
                          <div class="font-weight-medium">
                            <select class="form-select" id="order_status" name="order_status">
                              <option value="UNPAID">UNPAID</option>
                              <option value="INSTALLMENT">INSTALLMENT</option>
                              {{-- <option value="ACCEPTED" {{ $order->order_status === 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED</option> --}}
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="avatar bg-yellow text-white">
                            <i class="ti ti-clock"></i>
                          </span>
                        </div>
                        <div class="col">
                          <div class="text-muted">Pembayaran</div>
                          <div class="font-weight-medium">
                            <select class="form-select" id="payment_status" name="payment_status">
                              <option value="UNPAID">Belum Dibayar</option>
                              <option value="INSTALLMENT">Sebagian</option>
                              <option value="PAID">Lunas</option>
                              <option value="REFUNDED">Dikembalikan</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="avatar bg-blue text-white">
                            <i class="ti ti-package"></i>
                          </span>
                        </div>
                        <div class="col">
                          <div class="text-muted">Progress Status</div>
                          <div class="font-weight-medium">
                            <select class="form-select" id="order_progress_status" name="order_progress_status">
                              <option value="NEW">Baru</option>
                              <option value="UNPROCESSED">Belum Diproses</option>
                              <option value="ON PROCESS">Sedang Diproses</option>
                              <option value="PICKREQ">Permintaan Pengambilan</option>
                              <option value="NO AWB">Belum Ada Nomor Resi</option>
                              <option value="DELIVERED">Terkirim</option>
                              <option value="UNPAID">Menunggu Pembayaran</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Informasi Utama -->
    <div class="col-lg-8">
      <!-- Detail Order -->
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ti ti-shopping-cart me-2 text-primary"></i>
            Detail Order
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">ID Order</label>
                <input type="text" class="form-control" id="order_id_display" value="{{ 'ORD-' . date('YmdHis') }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanggal Order</label>
                <input type="text" class="form-control" value="{{ date('d M Y, H:i') }}" readonly>
                <input type="hidden" name="order_date" value="{{ date('Y-m-d H:i:s') }}">
              </div>
              <div class="mb-3">
                <label class="form-label">Sumber Order</label>
                <select class="form-select" id="order_source_id" name="order_source_id">
                  <option value="">Website</option>
                  @foreach(\App\Models\OrderSource::all() as $source)
                  <option value="{{ $source->id }}">{{ $source->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">ID External</label>
                <input type="text" class="form-control" id="external_id" name="external_id" placeholder="ID dari marketplace/sumber eksternal">
              </div>
            </div>
            
            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Total Order</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="text" class="form-control" id="total_amount_display" value="0" readonly>
                  <input type="hidden" id="total_amount" name="total_amount" value="0">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Ongkos Kirim</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" value="0" min="0">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Diskon</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="0" min="0">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Berat (Gram)</label>
                <input type="number" class="form-control" id="weight" name="weight" value="0" min="0" step="0.1">
              </div>
            </div>
          </div>
          
          <div class="mt-3 pt-3 border-top">
            <label class="form-label">Catatan Order</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
          </div>
        </div>
      </div>
      
      <!-- Detail Produk -->
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ti ti-box me-2 text-primary"></i>
            Produk yang Dibeli
          </h3>
          <div class="card-actions">
            <button type="button" class="btn btn-sm btn-primary" id="addProductBtn" title="Pilih gudang dan customer terlebih dahulu">
              <i class="ti ti-plus"></i> Tambah Produk
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-vcenter card-table" id="products-table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center">Qty</th>
                  <th class="text-end">Subtotal</th>
                  <th class="text-center" style="width: 80px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr id="no-products-row">
                  <td colspan="5" class="text-center">Tidak ada produk</td>
                </tr>
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="3" class="text-end">Subtotal:</td>
                  <td class="text-end" id="products-subtotal">Rp 0</td>
                  <td></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end">Ongkos Kirim:</td>
                  <td class="text-end" id="shipping-cost-display">Rp 0</td>
                  <td></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end">Diskon:</td>
                  <td class="text-end" id="discount-amount-display">Rp 0</td>
                  <td></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end fw-bold">Total:</td>
                  <td class="text-end fw-bold" id="total-amount-display">Rp 0</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Sidebar Kanan -->
    <div class="col-lg-4">


      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ti ti-building-warehouse me-2 text-primary"></i>
            Informasi Gudang
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Gudang</label>
            <select class="form-select" id="warehouse_id" name="warehouse_id" required>
              <option value="">Pilih Gudang</option>
              @foreach(\App\Models\Warehouse::all() as $warehouse)
              <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      
      <!-- Informasi Customer -->
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ti ti-user me-2 text-primary"></i>
            Informasi Customer
          </h3>
          <div class="card-actions">
            <button type="button" class="btn btn-sm btn-primary" id="selectCustomerBtn">
              <i class="ti ti-user-plus"></i> Pilih Customer
            </button>
          </div>
        </div>
        <div class="card-body">
          <div id="customer-info-placeholder">
            <div class="alert alert-info">
              Silakan pilih customer terlebih dahulu.
            </div>
          </div>
          
          <div id="customer-info" style="display: none;">
            <input type="hidden" id="customer_id" name="customer_id">
            <div class="mb-3">
              <label class="form-label">Nama Customer</label>
              <div class="form-control-plaintext" id="customer_name_display">-</div>
              <input type="hidden" id="customer_name" name="customer[name]" value="">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <div class="form-control-plaintext" id="customer_email_display">-</div>
              <input type="hidden" id="customer_email" name="customer[email]" value="">
            </div>
            <div class="mb-3">
              <label class="form-label">Telepon</label>
              <div class="form-control-plaintext" id="customer_phone_display">-</div>
              <input type="hidden" id="customer_phone" name="customer[phone]" value="">
            </div>
            <div class="mb-3">
              <label class="form-label">Tipe</label>
              <div class="form-control-plaintext" id="customer_type_display">-</div>
              <input type="hidden" id="customer_type" name="customer[type]" value="">
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <div class="form-control-plaintext" id="customer_address_display">-</div>
              <input type="hidden" id="customer_address" name="customer[address]" value="">
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Provinsi</label>
                <div class="form-control-plaintext" id="customer_province_display">-</div>
                <input type="hidden" id="customer_province" name="customer[province]" value="">
                <input type="hidden" id="customer_province_id" name="customer[province_id]" value="">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Kota/Kabupaten</label>
                <div class="form-control-plaintext" id="customer_city_display">-</div>
                <input type="hidden" id="customer_city" name="customer[city]" value="">
                <input type="hidden" id="customer_city_id" name="customer[city_id]" value="">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Kecamatan</label>
                <div class="form-control-plaintext" id="customer_district_display">-</div>
                <input type="hidden" id="customer_district" name="customer[district]" value="">
                <input type="hidden" id="customer_district_id" name="customer[district_id]" value="">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Kode Pos</label>
                <div class="form-control-plaintext" id="customer_postal_code_display">-</div>
                <input type="hidden" id="customer_postal_code" name="customer[postal_code]" value="">
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Informasi Pengiriman -->
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ti ti-truck me-2 text-primary"></i>
            Informasi Pengiriman
          </h3>
        </div>
        <div class="card-body">
          <div class="mb-3" id="shipping-address-buttons" style="display: none;">
            <button type="button" class="btn btn-info btn-sm" id="use-customer-address">
              <i class="ti ti-copy"></i> Gunakan Alamat Customer
            </button>
            <button type="button" class="btn btn-danger btn-sm" id="use-other-address" style="display:none;">
              <i class="ti ti-pencil"></i> Gunakan Alamat Lain
            </button>
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Nama Penerima</label>
            <input type="text" class="form-control" id="shipping_name" name="receiver[name]" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Telepon</label>
            <input type="text" class="form-control" id="shipping_phone" name="receiver[phone]" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="shipping_email" name="receiver[email]">
          </div>
          <div class="mb-3">
            <label class="form-label required">Alamat</label>
            <textarea class="form-control" id="shipping_address" name="receiver[address]" rows="2" required></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Provinsi</label>
              <select class="form-select select2-ajax" id="province_id" name="receiver[province_id]" data-url="{{ route('admin.provinces.index') }}" required>
                <option value="">Pilih Provinsi</option>
              </select>
              <input type="hidden" id="province_name" name="receiver[province]">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kota/Kabupaten</label>
              <select class="form-select select2-ajax" id="city_id" name="receiver[city_id]" data-url="{{ route('admin.cities.by-province') }}" data-depends-on="province_id" required>
                <option value="">Pilih Kota/Kabupaten</option>
              </select>
              <input type="hidden" id="city_name" name="receiver[city]">
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kecamatan</label>
              <select class="form-select select2-ajax" id="subdistrict_id" name="receiver[district_id]" data-url="{{ route('admin.subdistricts.by-city') }}" data-depends-on="city_id" required>
                <option value="">Pilih Kecamatan</option>
              </select>
              <input type="hidden" id="subdistrict_name" name="receiver[district]">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kode Pos</label>
              <select class="form-select" id="postal_code_select" name="receiver[postal_code]" required>
                <option value="">Pilih Kode Pos</option>
              </select>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Ekspedisi</label>
            <select class="form-select" id="shipping_id" name="shipping_id">
              <option value="">Pilih Ekspedisi</option>
              @foreach(\App\Models\CustomExpedition::all() as $expedition)
              <option value="{{ $expedition->id }}">{{ $expedition->name }}</option>
              @endforeach
            </select>
            <input type="hidden" id="shipping_logistic" name="shipping_logistic">
          </div>
          
          <div class="mb-3">
            <label class="form-label">No. Resi</label>
            <input type="text" class="form-control" id="awb_number" name="awb_number">
          </div>
        </div>
      </div>
      
      <!-- Informasi Gudang -->
    </div>
  </div>
</form>

<!-- Modal Tambah Produk -->
<div class="modal modal-blur fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Search and Filter Section -->
        <div class="row mb-3">
          <div class="col-md-8">
            <div class="input-group">
              <span class="input-group-text">
                <i class="ti ti-search"></i>
              </span>
              <input type="text" class="form-control" id="product-search" placeholder="Cari produk...">
              <button class="btn btn-primary" id="search-product-btn">Cari</button>
            </div>
          </div>
          <div class="col-md-4">
            <select class="form-select" id="product-category-filter">
              <option value="">Semua Kategori</option>
              @php
                // Get categories with product counts (only those with products)
                $categoryService = app(\App\Services\ProductCategoryService::class);
                $categories = $categoryService->getAllCategoriesWithProductCount(true);
              @endphp
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->products_count }})</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Product List Section -->
        <div class="row" id="product-list-container">
          <div class="col-12">
            <div class="alert alert-info">
              Silakan cari produk untuk menampilkan daftar produk
            </div>
          </div>
        </div>

        <!-- Selected Product Section (initially hidden) -->
        <div id="selected-product-section" style="display: none;">
          <hr>
          <h4>Produk yang Dipilih</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="me-3">
                      <img id="selected-product-image" src="" alt="Product Image" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div>
                      <h5 id="selected-product-name" class="mb-1"></h5>
                      <div class="text-muted small" id="selected-product-sku"></div>
                      <div class="text-primary" id="selected-product-price"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <form id="product-form">
                <input type="hidden" id="product_id" name="product_id">
                <input type="hidden" id="product_meta_id" name="product_meta_id">
                <input type="hidden" id="product_name" name="product_name">
                <input type="hidden" id="product_image" name="product_image">
                
                <!-- Variant Selection -->
                <div class="mb-3" id="variant-selection-container" style="display: none;">
                  <label class="form-label">Pilih Varian</label>
                  <select class="form-select" id="product_variant" name="variant_id">
                    <option value="">Pilih Varian</option>
                  </select>
                </div>
                
                <!-- Warehouse Selection -->
                <div class="mb-3">
                  <label class="form-label">Pilih Gudang</label>
                  <select class="form-select" id="product_warehouse" name="warehouse_id" required>
                    <option value="">Pilih Gudang</option>
                    @foreach(\App\Models\Warehouse::all() as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Harga</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="product_price" name="price" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Jumlah</label>
                      <input type="number" class="form-control" id="product_quantity" name="quantity" value="1" min="1" max="1" required>
                    </div>
                  </div>
                </div>
                
                <div class="mb-0">
                  <div class="alert alert-info" id="stock-info">
                    Pilih gudang untuk melihat stok tersedia
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="button" class="btn btn-primary" id="add-product-btn" disabled>
          Tambah Produk
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Pilih Customer -->
<div class="modal modal-blur fade" id="customerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <div class="input-group">
            <input type="text" class="form-control" id="customer-search" placeholder="Cari customer...">
            <button class="btn btn-outline-secondary" type="button" id="search-customer-btn">
              <i class="ti ti-search"></i>
            </button>
          </div>
        </div>
        
        <div class="table-responsive">
          <table class="table table-vcenter card-table" id="customers-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Tipe</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Customer data will be loaded here -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
  $(document).ready(function() {
    // Variables for product selection
    let selectedProduct = null;
    let productVariants = [];
    let productWarehouses = [];
    
    // Función para verificar si se pueden agregar productos
    function checkAddProductAvailability() {
      const warehouseSelected = $('#warehouse_id').val() !== '';
      const customerSelected = $('#customer_id').val() !== '';
      
      // Actualizar el título del botón según el estado
      if (!warehouseSelected && !customerSelected) {
        $('#addProductBtn').attr('title', 'Pilih gudang dan customer terlebih dahulu');
      } else if (!warehouseSelected) {
        $('#addProductBtn').attr('title', 'Pilih gudang terlebih dahulu');
      } else if (!customerSelected) {
        $('#addProductBtn').attr('title', 'Pilih customer terlebih dahulu');
      } else {
        $('#addProductBtn').attr('title', 'Tambah produk ke order');
      }
    }
    // Inisialisasi Select2
    $('.select2-ajax').select2({
      placeholder: "Pilih...",
      allowClear: true,
      width: '100%'
    });
    
    // Load provinces
    function loadProvinces() {
      $.ajax({
        url: $('#province_id').data('url'),
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          // Clear and add default option
          $('#province_id').empty().append('<option value="">Pilih Provinsi</option>');
          
          // Add new options
          $.each(data, function(index, province) {
            $('#province_id').append('<option value="' + province.id + '">' + province.text + '</option>');
          });
        },
        error: function(xhr, status, error) {
          console.error('Error loading provinces:', error);
        }
      });
    }
    
    // Load cities based on selected province
    function loadCities(provinceId) {
      if (!provinceId) return;
      
      $.ajax({
        url: $('#city_id').data('url'),
        type: 'GET',
        data: { province_id: provinceId },
        dataType: 'json',
        success: function(data) {
          // Clear and add default option
          $('#city_id').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
          
          // Add new options
          $.each(data, function(index, city) {
            $('#city_id').append('<option value="' + city.id + '">' + city.text + '</option>');
          });
          
          // Enable the select
          $('#city_id').prop('disabled', false);
        },
        error: function(xhr, status, error) {
          console.error('Error loading cities:', error);
        }
      });
    }
    
    // Load subdistricts based on selected city
    function loadSubdistricts(cityId) {
      if (!cityId) return;
      
      $.ajax({
        url: $('#subdistrict_id').data('url'),
        type: 'GET',
        data: { city_id: cityId },
        dataType: 'json',
        success: function(data) {
          // Clear and add default option
          $('#subdistrict_id').empty().append('<option value="">Pilih Kecamatan</option>');
          
          // Add new options
          $.each(data, function(index, subdistrict) {
            $('#subdistrict_id').append('<option value="' + subdistrict.id + '">' + subdistrict.text + '</option>');
          });
          
          // Enable the select
          $('#subdistrict_id').prop('disabled', false);
        },
        error: function(xhr, status, error) {
          console.error('Error loading subdistricts:', error);
        }
      });
    }
    
    // Load postal codes based on selected subdistrict
    function loadPostalCodes(subdistrictId) {
      if (!subdistrictId) return;
      
      $.ajax({
        url: "{{ route('admin.subdistricts.postal-codes') }}",
        type: 'GET',
        data: { subdistrict_id: subdistrictId },
        dataType: 'json',
        success: function(data) {
          // Clear and add default option
          $('#postal_code_select').empty().append('<option value="">Pilih Kode Pos</option>');
          
          // Add new options
          $.each(data, function(index, postalCode) {
            $('#postal_code_select').append('<option value="' + postalCode + '">' + postalCode + '</option>');
          });
        },
        error: function(xhr, status, error) {
          console.error('Error loading postal codes:', error);
        }
      });
    }
    
    // Event listener for province change
    $('#province_id').on('change', function() {
      const provinceId = $(this).val();
      const provinceName = $(this).find('option:selected').text();
      
      // Set province name to hidden input
      $('#province_name').val(provinceName);
      
      // Reset dependent fields
      $('#city_id').val('');
      $('#city_name').val('');
      $('#subdistrict_id').val('');
      $('#subdistrict_name').val('');
      $('#postal_code_select').val('');
      
      // Load cities for the selected province
      if (provinceId) {
        loadCities(provinceId);
      } else {
        // Clear dependent dropdowns
        $('#city_id').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
        $('#subdistrict_id').empty().append('<option value="">Pilih Kecamatan</option>');
        $('#postal_code_select').empty().append('<option value="">Pilih Kode Pos</option>');
      }
    });
    
    // Event listener for city change
    $('#city_id').on('change', function() {
      const cityId = $(this).val();
      const cityName = $(this).find('option:selected').text();
      
      // Update hidden field
      $('#city_name').val(cityName);
      
      // Reset dependent fields
      $('#subdistrict_id').val('');
      $('#subdistrict_name').val('');
      $('#postal_code_select').val('');
      
      // Load subdistricts for the selected city
      if (cityId) {
        loadSubdistricts(cityId);
      } else {
        // Clear dependent dropdowns
        $('#subdistrict_id').empty().append('<option value="">Pilih Kecamatan</option>');
        $('#postal_code_select').empty().append('<option value="">Pilih Kode Pos</option>');
      }
    });
    
    // Event listener for subdistrict change
    $('#subdistrict_id').on('change', function() {
      const subdistrictId = $(this).val();
      const subdistrictName = $(this).find('option:selected').text();
      
      // Update hidden field
      $('#subdistrict_name').val(subdistrictName);
      
      // Reset dependent fields
      $('#postal_code_select').val('');
      
      // Load postal codes for the selected subdistrict
      if (subdistrictId) {
        loadPostalCodes(subdistrictId);
      } else {
        // Clear dependent dropdown
        $('#postal_code_select').empty().append('<option value="">Pilih Kode Pos</option>');
      }
    });
    
    // Event listener for shipping expedition change
    $('#shipping_id').on('change', function() {
      const shippingName = $(this).find('option:selected').text();
      $('#shipping_logistic').val(shippingName);
    });
    
    // Event listener para cambios en warehouse_id
    $('#warehouse_id').on('change', function() {
      // Guardar el valor anterior antes de cualquier cambio
      const previousValue = $(this).data('previous-value') || '';
      const newValue = $(this).val();
      
      // Verificar si ya hay productos agregados
      if ($('.product-row').length > 0) {
        // Mostrar modal de confirmación
        Swal.fire({
          title: 'Konfirmasi',
          text: 'Mengganti gudang atau customer akan reset data product, karena menyesuaikan Gudang dan Kategori customer',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Ganti',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Eliminar todos los productos
            $('.product-row').remove();
            
            // Verificar si existe la fila "no-products-row", si no, crearla
            if ($('#no-products-row').length === 0) {
              $('#products-table tbody').html('<tr id="no-products-row"><td colspan="5" class="text-center">Tidak ada produk</td></tr>');
            } else {
              $('#no-products-row').show();
            }
            
            // Recalcular totales
            calculateTotals();
            
            // Guardar el nuevo valor como el valor anterior para futuras comparaciones
            $(this).data('previous-value', newValue);
            
            // Actualizar la disponibilidad del botón de agregar productos
            checkAddProductAvailability();
          } else {
            // Restaurar el valor anterior
            $(this).val(previousValue);
          }
        });
      } else {
        // Si no hay productos, simplemente guardar el nuevo valor como el valor anterior
        $(this).data('previous-value', newValue);
        
        // Actualizar la disponibilidad del botón de agregar productos
        checkAddProductAvailability();
      }
    });
    
    // Load initial data
    loadProvinces();
    
    // Verificar inicialmente si se pueden agregar productos
    checkAddProductAvailability();
    
    // Format rupiah
    function formatRupiah(angka) {
      return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }
    
    // Calculate totals
    function calculateTotals() {
      let subtotal = 0;
      
      // Calculate subtotal from products
      $('.product-row').each(function() {
        const price = parseFloat($(this).find('.product-price').val()) || 0;
        const quantity = parseInt($(this).find('.product-quantity').val()) || 0;
        const itemSubtotal = price * quantity;
        
        $(this).find('.product-subtotal').text(formatRupiah(itemSubtotal));
        subtotal += itemSubtotal;
      });
      
      // Update subtotal display
      $('#products-subtotal').text(formatRupiah(subtotal));
      
      // Get shipping cost and discount
      const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
      const discountAmount = parseFloat($('#discount_amount').val()) || 0;
      
      // Calculate total
      const total = subtotal + shippingCost - discountAmount;
      
      // Update displays
      $('#shipping-cost-display').text(formatRupiah(shippingCost));
      $('#discount-amount-display').text(formatRupiah(discountAmount));
      $('#total-amount-display').text(formatRupiah(total));
      
      // Update hidden input
      $('#total_amount').val(total);
    }
    
    // Event listeners for shipping cost and discount changes
    $('#shipping_cost, #discount_amount').on('input', calculateTotals);
    
    // Event listener for product quantity changes
    $(document).on('input', '.product-quantity', calculateTotals);
    
    // Event listener for remove product button
    $(document).on('click', '.remove-product', function() {
      $(this).closest('tr').remove();
      
      // Show "no products" row if no products left
      if ($('.product-row').length === 0) {
        $('#no-products-row').show();
      }
      
      // Recalculate totals
      calculateTotals();
    });
    
    // Event listener for add product button
    $('#addProductBtn').on('click', function() {
      // Verificar si se ha seleccionado un warehouse y un customer
      if (!$('#warehouse_id').val()) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan pilih gudang terlebih dahulu',
          showConfirmButton: true
        });
        return;
      }
      
      if (!$('#customer_id').val()) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan pilih customer terlebih dahulu',
          showConfirmButton: true
        });
        return;
      }
      
      // Reset modal
      resetProductModal();
      
      // Show modal
      $('#productModal').modal('show');
    });
    
    // Fungsi untuk reset modal produk
    function resetProductModal() {
      $('#product-search').val('');
      $('#product-category-filter').val('');
      $('#product-list-container').html('<div class="col-12"><div class="alert alert-info">Silakan cari produk untuk menampilkan daftar produk</div></div>');
      $('#selected-product-section').hide();
      
        var form = $('#product-form')[0];
        if (form) {
            form.reset();
        }
      $('#add-product-btn').prop('disabled', true);
      selectedProduct = null;
      productVariants = [];
      productWarehouses = [];
    }
    
    // Event listener for search product button
    $('#search-product-btn').on('click', function() {
      searchProducts();
    });
    
    // Event listener for enter key pada input pencarian
    $('#product-search').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        searchProducts();
      }
    });
    
    // Fungsi untuk mencari produk
    function searchProducts() {
      const searchQuery = $('#product-search').val();
      const categoryId = $('#product-category-filter').val();
      const customerCategory = $('#customer_type').val() || 'Customer'; // Get customer category from order
      
      // Tampilkan loading
      $('#product-list-container').html('<div class="col-12 text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Mencari produk...</div></div>');
      
      // Kirim request ke API
      $.ajax({
        url: '/admin/products/data',
        method: 'GET',
        data: {
          search: searchQuery,
          category_id: categoryId,
          length: 12
        },
        success: function(response) {
          // Kosongkan container
          $('#product-list-container').empty();
          
          // Jika tidak ada produk, tampilkan pesan
          if (!response.data || response.data.length === 0) {
            $('#product-list-container').html('<div class="col-12"><div class="alert alert-warning">Tidak ada produk yang ditemukan</div></div>');
            return;
          }
          
          // Tampilkan produk
          response.data.forEach(function(product) {
            // Get first variation if available
            let displayPrice = product.price;
            let imageUrl = product.image_path ? '/storage/' + product.image_path : '/client/img/product/product-default.jpg';
            let totalStock = 0;
            
            // If product has variations, use the first variation for price and image
            if (product.variations && product.variations.length > 0) {
              const variation = product.variations[0];
              console.log(variation);
              // Set image from variation if available
              if (variation.image) {
                imageUrl = variation.image;
              }
              
              // Calculate total stock from warehouse stocks
              if (variation.warehouse_stocks) {
                totalStock = variation.warehouse_stocks.reduce((sum, stock) => sum + stock.stock, 0);
              } else {
                totalStock = variation.stock || 0;
              }
              
              // Adjust price based on customer category
              switch (customerCategory) {
                case 'Dropshipper':
                  displayPrice = variation.price_reseller > 0 ? variation.price_reseller : variation.price;
                  break;
                case 'Super Dropshipper':
                  displayPrice = variation.price1 > 0 ? variation.price1 : variation.price;
                  break;
                case 'Dropshipper Standar':
                  displayPrice = variation.price2 > 0 ? variation.price2 : variation.price;
                  break;
                case 'Grosir':
                  displayPrice = variation.price3 > 0 ? variation.price3 : variation.price;
                  break;
                default:
                  // For 'Customer' or any other category, use regular price
                  displayPrice = variation.price;
                  break;
              }
            } else {
              // If no variations, use product's total stock
              totalStock = product.total_stock || 0;
            }
            
            const productCard = `
              <div class="col-md-3 mb-3">
                <div class="card product-card h-100" data-id="${product.id}" data-name="${product.name}" data-price="${displayPrice}" data-sku="${product.sku || ''}" data-image="${product.image_path || ''}">
                  <div class="card-img-top text-center pt-3">
                    <img src="${imageUrl}" alt="${product.name}" class="img-fluid" style="height: 120px; object-fit: contain;">
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <div class="text-muted small">SKU: ${product.sku || 'N/A'}</div>
                    <div class="text-primary mt-2">${formatRupiah(displayPrice || 0)}</div>
                    <div class="text-muted small">Stok: ${totalStock}</div>
                  </div>
                  <div class="card-footer bg-transparent">
                    <button type="button" class="btn btn-primary btn-sm w-100 select-product">Pilih</button>
                  </div>
                </div>
              </div>
            `;
            
            $('#product-list-container').append(productCard);
          });
        },
        error: function() {
          $('#product-list-container').html('<div class="col-12"><div class="alert alert-danger">Gagal memuat data produk</div></div>');
        }
      });
    }
    
    // Fungsi untuk memuat varian produk
    function loadProductVariants(productId) {
      const customerCategory = $('#customer_type').val() || 'Customer'; // Get customer category from order
      
      $.ajax({
        url: `/admin/products/${productId}/variations`,
        method: 'GET',
        success: function(response) {
          if (!response.success) {
            $('#variant-selection-container').hide();
            return;
          }
          
          productVariants = response.data || [];
          
          // Jika ada varian, tampilkan container varian
          if (productVariants.length > 0) {
            // Kosongkan select
            $('#product_variant').empty();
            $('#product_variant').append('<option value="">Pilih Varian</option>');

            // Tambahkan opsi varian
            productVariants.forEach(function(variant) {
              let variantName = '';
              if (variant.size && variant.color) {
                variantName = `${variant.size} - ${variant.color}`;
              } else if (variant.size) {
                variantName = variant.size;
              } else if (variant.color) {
                variantName = variant.color;
              } else {
                variantName = 'Utama';
              }
              
              // Determine price based on customer category
              let variantPrice = variant.price;
              
              switch (customerCategory) {
                case 'Dropshipper':
                  variantPrice = variant.price_reseller > 0 ? variant.price_reseller : variant.price;
                  break;
                case 'Super Dropshipper':
                  variantPrice = variant.price1 > 0 ? variant.price1 : variant.price;
                  break;
                case 'Dropshipper Standar':
                  variantPrice = variant.price2 > 0 ? variant.price2 : variant.price;
                  break;
                case 'Grosir':
                  variantPrice = variant.price3 > 0 ? variant.price3 : variant.price;
                  break;
                default:
                  // For 'Customer' or any other category, use regular price
                  variantPrice = variant.price;
                  break;
              }
              
              // Calculate total stock from warehouse stocks
              let totalStock = 0;
              if (variant.warehouse_stocks && variant.warehouse_stocks.length > 0) {
                totalStock = variant.warehouse_stocks.reduce((sum, stock) => sum + stock.stock, 0);
              } else {
                totalStock = variant.stock_quantity || 0;
              }
              
              // Store warehouse stocks data as a JSON string in a data attribute
              const warehouseStocksData = JSON.stringify(variant.warehouse_stocks || []);
              
              $('#product_variant').append(`<option value="${variant.id}" 
                data-price="${variantPrice || selectedProduct.price}" 
                data-warehouse-stocks='${warehouseStocksData}'
                data-stock="${totalStock}">${variantName} (Stok: ${totalStock})</option>`);
            });
            
            // Tampilkan container varian
            $('#variant-selection-container').show();
          } else {
            // Sembunyikan container varian
            $('#variant-selection-container').hide();
          }
        },
        error: function() {
          $('#variant-selection-container').hide();
        }
      });
    }
    
    // Fungsi untuk update informasi stok
    function updateStockInfo() {
      const warehouseId = $('#product_warehouse').val();
      const variantId = $('#product_variant').val() || null;
      const productId = $('#product_id').val();
      
      if (!warehouseId) {
        $('#stock-info').html('Pilih gudang untuk melihat stok tersedia');
        $('#product_quantity').prop('max', 1);
        $('#add-product-btn').prop('disabled', true);
        return;
      }
      
      // Check if we can get stock directly from the selected warehouse option
      const selectedWarehouseOption = $('#product_warehouse option:selected');
      if (selectedWarehouseOption.length > 0 && selectedWarehouseOption.data('stock') !== undefined) {
        const stock = selectedWarehouseOption.data('stock') || 0;
        updateStockDisplay(stock);
        return;
      }
      
      // If we can't get stock directly, make an API call
      // Tampilkan loading
      $('#stock-info').html('<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Mengecek stok...</div>');
      
      // Kirim request ke API
      $.ajax({
        url: `/admin/products/${productId}/stock`,
        method: 'GET',
        data: {
          warehouse_id: warehouseId,
          variation_id: variantId
        },
        success: function(response) {
          if (!response.success) {
            $('#stock-info').html('<div class="text-danger"><i class="ti ti-alert-circle"></i> Gagal mengecek stok</div>');
            $('#add-product-btn').prop('disabled', true);
            return;
          }
          
          const stock = response.stock || 0;
          updateStockDisplay(stock);
        },
        error: function() {
          $('#stock-info').html('<div class="text-danger"><i class="ti ti-alert-circle"></i> Gagal mengecek stok</div>');
          $('#add-product-btn').prop('disabled', true);
        }
      });
    }
    
    // Helper function to update stock display
    function updateStockDisplay(stock) {
      // Update informasi stok
      if (stock > 0) {
        $('#stock-info').html(`<div class="text-success"><i class="ti ti-check-circle"></i> Stok tersedia: ${stock} unit</div>`);
        $('#product_quantity').prop('max', stock);
        $('#product_quantity').val(1);
        $('#add-product-btn').prop('disabled', false);
      } else {
        $('#stock-info').html('<div class="text-danger"><i class="ti ti-x-circle"></i> Stok tidak tersedia</div>');
        $('#product_quantity').prop('max', 0);
        $('#add-product-btn').prop('disabled', true);
      }
    }
    
    // Event listener for select product button
    $(document).on('click', '.select-product', function() {
      
      const productCard = $(this).closest('.product-card');
      const productId = productCard.data('id');
      const customerCategory = $('#customer_type').val() || 'Customer'; // Get customer category from order
      
      // Tampilkan loading
      $('#selected-product-section').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Memuat detail produk...</div></div>').show();
      
      // Scroll ke bagian bawah modal
      
      // Load detail produk dari API
      $.ajax({
        url: `/admin/products/${productId}/details`,
        method: 'GET',
        success: function(response) {
          if (!response.success) {
            $('#selected-product-section').html('<div class="alert alert-danger">Gagal memuat detail produk</div>');
            return;
          }
          
          const product = response.data;
          
          // Determine price based on customer category and variations
          let displayPrice = product.display_price || product.price;
          let discountedPrice = product.discounted_price;
          let imageUrl = product.image_path ? '/storage/' + product.image_path : '/client/img/product/product-default.jpg';
          
          // If product has variations, use the first variation for price
          if (product.has_variation && product.variations && product.variations.length > 0) {
            const variation = product.variations[0];

            console.log(variation);
            
            // Set image from variation if available
            if (variation.image) {
              imageUrl = variation.image;
            }
            
            // Adjust price based on customer category
            switch (customerCategory) {
              case 'Dropshipper':
                displayPrice = variation.price_reseller > 0 ? variation.price_reseller : variation.price;
                break;
              case 'Super Dropshipper':
                displayPrice = variation.price1 > 0 ? variation.price1 : variation.price;
                break;
              case 'Dropshipper Standar':
                displayPrice = variation.price2 > 0 ? variation.price2 : variation.price;
                break;
              case 'Grosir':
                displayPrice = variation.price3 > 0 ? variation.price3 : variation.price;
                break;
              default:
                // For 'Customer' or any other category, use regular price
                displayPrice = variation.price;
                break;
            }
            
            // Apply discount if applicable
            discountedPrice = product.discount > 0 ? displayPrice * (1 - product.discount/100) : displayPrice;
          }
          
          // Simpan produk yang dipilih
          selectedProduct = {
            id: product.id,
            name: product.name,
            price: displayPrice,
            discounted_price: discountedPrice,
            sku: product.sku || '',
            image: product.image_path || '',
            thumbnail: product.thumbnail_path || '',
            has_variation: product.has_variation,
            total_stock: product.total_stock,
            warehouses: product.warehouses || []
          };
          
          // Reset form
          var form = $('#product-form')[0];
          if (form) {
              form.reset();
          }
          
          // Tampilkan detail produk yang dipilih
          $('#selected-product-section').html(`
            <hr>
            <h4>Produk yang Dipilih</h4>
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <img id="selected-product-image" src="${imageUrl}" alt="${product.name}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                      </div>
                      <div>
                        <h5 id="selected-product-name" class="mb-1">${product.name}</h5>
                        <div class="text-muted small" id="selected-product-sku">SKU: ${product.sku || 'N/A'}</div>
                        <div class="text-primary" id="selected-product-price">
                          ${product.discount > 0 ? 
                            `<span class="text-decoration-line-through text-muted me-2">${formatRupiah(displayPrice)}</span> ${formatRupiah(discountedPrice)}` : 
                            formatRupiah(displayPrice)}
                        </div>
                        ${product.categories && product.categories.length > 0 ? 
                          `<div class="text-muted small mt-1">Kategori: ${product.categories.map(c => c.name).join(', ')}</div>` : 
                          ''}
                        <div class="text-muted small">Kategori Pelanggan: ${customerCategory}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <form id="product-form">
                  <input type="hidden" id="product_id" name="product_id" value="${product.id}">
                  <input type="hidden" id="product_meta_id" name="product_meta_id" value="${product.sku || ''}">
                  <input type="hidden" id="product_name" name="product_name" value="${product.name}">
                  <input type="hidden" id="product_image" name="product_image" value="${product.image || ''}">
                  
                  <!-- Variant Selection -->
                  <div class="mb-3" id="variant-selection-container" style="display: ${product.has_variation ? 'block' : 'none'}">
                    <label class="form-label">Pilih Varian</label>
                    <select class="form-select" id="product_variant" name="variant_id">
                      <option value="">Pilih Varian</option>
                    </select>
                  </div>
                  
                  <!-- Warehouse Selection -->
                  <div class="mb-3">
                    <label class="form-label">Pilih Gudang</label>
                    <select class="form-select" id="product_warehouse" name="warehouse_id" required>
                      <option value="">Pilih Gudang</option>
                      ${product.warehouses && product.warehouses.length > 0 ? 
                        product.warehouses.map(w => {
                          // Find stock for this warehouse
                          let warehouseStock = 0;
                          if (product.variations && product.variations.length > 0) {
                            const firstVariation = product.variations[0];
                            if (firstVariation.warehouse_stocks) {
                              const stockInfo = firstVariation.warehouse_stocks.find(s => s.warehouse_id === w.id);
                              if (stockInfo) {
                                warehouseStock = stockInfo.stock;
                              }
                            }
                          }
                          return `<option value="${w.id}" data-stock="${warehouseStock}">${w.name} ${warehouseStock > 0 ? `(Stok: ${warehouseStock})` : ''}</option>`;
                        }).join('') : 
                        ''}
                    </select>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" id="product_price" name="price" value="${discountedPrice || displayPrice}" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="product_quantity" name="quantity" value="1" min="1" max="1" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="mb-0">
                    <div class="alert alert-info" id="stock-info">
                      Pilih gudang untuk melihat stok tersedia
                    </div>
                  </div>
                </form>
              </div>
            </div>
          `);
          
          // Jika produk memiliki varian, load varian
          if (product.has_variation) {
            loadProductVariants(product.id);
          }

          $('#add-product-btn').prop('disabled', true);
          
          // Set warehouse to match the order's warehouse if selected
          const orderWarehouseId = $('#warehouse_id').val();
          if (orderWarehouseId) {
            $('#product_warehouse').val(orderWarehouseId);
            $('#product_warehouse').trigger('change');
          }
          
          // Scroll ke bagian bawah modal lagi setelah konten dimuat

          setTimeout(function() {
            const modalBody = $('.modal-body');
            modalBody.animate({ scrollTop: modalBody.prop('scrollHeight') }, 500);
          }, 300);

          $('#add-product-btn').prop('disabled', true);
        },
        error: function() {
          $('#selected-product-section').html('<div class="alert alert-danger">Gagal memuat detail produk</div>');
        }
      });
      
    });
    
    // Event listener untuk perubahan varian yang dipilih
    $(document).on('change', '#product_variant', function() {
      const selectedOption = $(this).find('option:selected');
      const variantId = $(this).val();
      
      if (variantId) {
        // Update price based on selected variant
        const price = selectedOption.data('price') || selectedProduct.price;
        $('#product_price').val(price);
        
        // Get warehouse stocks from data attribute
        try {
          const warehouseStocks = selectedOption.data('warehouse-stocks') || [];
          
          // Update warehouse dropdown with warehouses that have stock for this variant
          $('#product_warehouse').empty();
          $('#product_warehouse').append('<option value="">Pilih Gudang</option>');
          
          if (warehouseStocks.length > 0) {
            // Sort warehouses by stock quantity (highest first)
            warehouseStocks.sort((a, b) => b.stock - a.stock);
            
            warehouseStocks.forEach(function(stock) {
              if (stock.stock > 0) {
                $('#product_warehouse').append(`<option value="${stock.warehouse_id}" data-stock="${stock.stock}">${stock.warehouse_name} (Stok: ${stock.stock})</option>`);
              }
            });
          }
        } catch (e) {
          console.error('Error parsing warehouse stocks data:', e);
        }
        
        // Update stock info
        updateStockInfo();
      }
    });
    
    // Event listener untuk perubahan gudang yang dipilih
    $(document).on('change', '#product_warehouse', function() {
      updateStockInfo();
    });
    
    // Event listener for add product button in modal
    $(document).on('click', '#add-product-btn', function() {
      // Hapus pesan "tidak ada produk" jika ada
      $('#no-products-row').remove();
      
      // Ambil data produk
      const productId = $('#product_id').val();
      const productImage = $('#product_image').val();
      const productName = $('#product_name').val();
      const productSku = $('#product_meta_id').val();
      const productPrice = parseFloat($('#product_price').val()) || 0;
      const productQuantity = parseInt($('#product_quantity').val()) || 1;
      const variantId = $('#product_variant').val() || null;
      const warehouseId = $('#product_warehouse').val();
      
      // Jika tidak ada produk yang dipilih, tampilkan pesan error
      if (!productId) {
        alert('Silakan pilih produk terlebih dahulu');
        return;
      }
      
      // Jika tidak ada gudang yang dipilih, tampilkan pesan error
      if (!warehouseId) {
        alert('Silakan pilih gudang terlebih dahulu');
        return;
      }
      
      // Validasi gudang produk harus sama dengan gudang yang dipilih di informasi gudang
      const orderWarehouseId = $('#warehouse_id').val();
      if (orderWarehouseId && warehouseId !== orderWarehouseId) {
        // Tampilkan pesan error dengan SweetAlert
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Gudang tidak sesuai',
          text: 'Pilihan Gudang Produk harus sama dengan Pilihan Informasi Gudang',
          showConfirmButton: false,
          timer: 3000
        });
        return;
      }
      
      // Tambahkan baris produk baru
      const productCounter = $('.product-row').length;
      const newRow = `
        <tr class="product-row" data-id="new-${productCounter}">
          <td>
            <div class="d-flex align-items-center">
              <span class="avatar me-2 bg-muted" style="background-image:url('${productImage}')">
                <i class="ti ti-box"></i>
              </span>
              <div>
                <input type="hidden" name="products[${productCounter}][id]" value="">
                <input type="hidden" name="products[${productCounter}][product_id]" value="${productId}">
                <input type="hidden" name="products[${productCounter}][variant_id]" value="${variantId || ''}">
                <input type="hidden" name="products[${productCounter}][warehouse_id]" value="${warehouseId}">
                <input type="text" class="form-control product-name" name="products[${productCounter}][name]" value="${productName}" readonly>
                <div class="text-muted small">SKU: ${productSku}</div>
              </div>
            </div>
          </td>
          <td>
            <input type="number" class="form-control text-center product-price" name="products[${productCounter}][price]" value="${productPrice}" min="0" readonly>
          </td>
          <td>
            <input type="number" class="form-control text-center product-quantity" name="products[${productCounter}][quantity]" value="${productQuantity}" min="1" max="${$('#product_quantity').prop('max')}">
          </td>
          <td class="text-end product-subtotal">${formatRupiah(productPrice * productQuantity)}</td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-product">
              <i class="ti ti-trash"></i>
            </button>
          </td>
        </tr>
      `;
      
      $('#products-table tbody').append(newRow);
      
      // Hitung ulang total
      calculateTotals();
      
      // Tutup modal
      $('#productModal').modal('hide');
      
      // Reset form
      resetProductModal();
    });
    
    // Event listener for select customer button
    $('#selectCustomerBtn').on('click', function() {
      // Reset customer search
      $('#customer-search').val('');
      $('#customers-table tbody').empty();
      
      // Show modal
      $('#customerModal').modal('show');
      
      // Load customers
      loadCustomers();
    });
    
    // Load customers
    function loadCustomers(searchTerm = '', page = 1) {
      // Show loading
      $('#customers-table tbody').html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Memuat data customer...</div></td></tr>');
      
      // Load customers from API
      $.ajax({
        url: '/admin/customers/data',
        method: 'GET',
        data: { 
          search: searchTerm,
          page: page,
          per_page: 10
        },
        dataType: 'json',
        success: function(response) {
          if (!response.success) {
            $('#customers-table tbody').html('<tr><td colspan="5" class="text-center">Gagal memuat data customer</td></tr>');
            return;
          }
          
          const customers = response.data.customers;
          
          // Clear table
          $('#customers-table tbody').empty();
          
          if (customers.data.length === 0) {
            $('#customers-table tbody').html('<tr><td colspan="5" class="text-center">Tidak ada customer yang ditemukan</td></tr>');
            return;
          }
          
          // Add customers to table
          customers.data.forEach(function(customer) {
            const categoryName = customer.customer_category ? customer.customer_category.category_name : 'Umum';
            const statusBadge = customer.is_active === 'Y' 
              ? '<span class="badge bg-success">Aktif</span>' 
              : '<span class="badge bg-danger">Nonaktif</span>';
            
            // Simpan data customer dalam format JSON di atribut data
            const customerData = JSON.stringify(customer);
              
            const row = `
              <tr class="customer-row">
                <td>
                  <div class="d-flex align-items-center">
                    <span class="avatar me-2 ${customer.is_active === 'Y' ? 'bg-primary-lt' : 'bg-muted-lt'}">
                      ${customer.name.charAt(0).toUpperCase()}
                    </span>
                    <div>
                      <div class="font-weight-medium">${customer.name}</div>
                      <div class="text-muted">ID: ${customer.id}</div>
                    </div>
                  </div>
                </td>
                <td>${customer.email || '-'}</td>
                <td>${customer.phone || '-'}</td>
                <td>
                  <span class="badge bg-azure-lt">${categoryName}</span>
                </td>
                <td>
                  <button type="button" class="btn btn-sm btn-primary select-customer" 
                          data-id="${customer.id}" 
                          data-customer='${customerData}'>
                    <i class="ti ti-check me-1"></i> Pilih
                  </button>
                </td>
              </tr>
            `;
            
            $('#customers-table tbody').append(row);
          });
          
          // Add pagination if needed
          if (customers.last_page > 1) {
            let paginationHtml = '<tr><td colspan="5"><div class="d-flex justify-content-center mt-3"><ul class="pagination">';
            
            // Previous button
            paginationHtml += `
              <li class="page-item ${customers.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link customer-page-link" href="#" data-page="${customers.current_page - 1}">
                  <i class="ti ti-chevron-left"></i>
                </a>
              </li>
            `;
            
            // Page numbers
            for (let i = 1; i <= customers.last_page; i++) {
              if (
                i === 1 || 
                i === customers.last_page || 
                (i >= customers.current_page - 1 && i <= customers.current_page + 1)
              ) {
                paginationHtml += `
                  <li class="page-item ${i === customers.current_page ? 'active' : ''}">
                    <a class="page-link customer-page-link" href="#" data-page="${i}">${i}</a>
                  </li>
                `;
              } else if (
                i === customers.current_page - 2 || 
                i === customers.current_page + 2
              ) {
                paginationHtml += `
                  <li class="page-item disabled">
                    <span class="page-link">...</span>
                  </li>
                `;
              }
            }
            
            // Next button
            paginationHtml += `
              <li class="page-item ${customers.current_page === customers.last_page ? 'disabled' : ''}">
                <a class="page-link customer-page-link" href="#" data-page="${customers.current_page + 1}">
                  <i class="ti ti-chevron-right"></i>
                </a>
              </li>
            `;
            
            paginationHtml += '</ul></div></td></tr>';
            
            $('#customers-table tbody').append(paginationHtml);
          }
        },
        error: function() {
          $('#customers-table tbody').html('<tr><td colspan="5" class="text-center">Gagal memuat data customer</td></tr>');
        }
      });
    }
    
    // Event listener for pagination
    $(document).on('click', '.customer-page-link', function(e) {
      e.preventDefault();
      const page = $(this).data('page');
      const searchTerm = $('#customer-search').val();
      loadCustomers(searchTerm, page);
    });
    
    // Event listener for search customer button
    $('#search-customer-btn').on('click', function() {
      const searchTerm = $('#customer-search').val();
      loadCustomers(searchTerm, 1);
    });
    
    // Event listener for enter key pada input pencarian customer
    $('#customer-search').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        const searchTerm = $(this).val();
        loadCustomers(searchTerm, 1);
      }
    });
    
    // Event listener for select customer button
    $(document).on('click', '.select-customer', function() {
      // Ambil data customer dari atribut data
      const customerData = $(this).data('customer');
      
      // Verificar si ya hay productos agregados y si se está cambiando el customer
      if ($('.product-row').length > 0 && $('#customer_id').val() && $('#customer_id').val() !== customerData.id) {
        // Mostrar modal de confirmación
        Swal.fire({
          title: 'Konfirmasi',
          text: 'Mengganti gudang atau customer akan reset data product, karena menyesuaikan Gudang dan Kategori customer',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Ganti',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Eliminar todos los productos
            $('.product-row').remove();
            
            // Verificar si existe la fila "no-products-row", si no, crearla
            if ($('#no-products-row').length === 0) {
              $('#products-table tbody').html('<tr id="no-products-row"><td colspan="5" class="text-center">Tidak ada produk</td></tr>');
            } else {
              $('#no-products-row').show();
            }
            
            // Recalcular totales
            calculateTotals();
            
            // Continuar con la selección del customer
            processCustomerSelection(customerData);
          }
        });
      } else {
        // Si no hay productos o es la primera selección, procesar directamente
        processCustomerSelection(customerData);
      }
    });
    
    // Función para procesar la selección del customer
    function processCustomerSelection(customerData) {
      // Show loading
      $('#customer-info').hide();
      $('#customer-info-placeholder').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Memuat data customer...</div></div>').show();
      
      // Tutup modal
      $('#customerModal').modal('hide');
      
      // Proses data customer
      if (!customerData) {
        $('#customer-info-placeholder').html('<div class="alert alert-danger">Gagal memuat data customer</div>');
        return;
      }
      
      const customer = customerData;
          
          // Set customer ID
          $('#customer_id').val(customer.id);
          
          // Set customer details
          $('#customer_name').val(customer.name);
          $('#customer_email').val(customer.email || '');
          $('#customer_phone').val(customer.phone || '');
          
          // Ambil kategori customer
          let categoryName = 'Customer';
          if (customer.customer_category) {
            categoryName = customer.customer_category.category_name;
          }
          $('#customer_type').val(categoryName);
          
          $('#customer_address').val(customer.address || '');
          
          // Ambil data provinsi, kota, dan kecamatan
          let provinceName = '';
          let districtName = '';
          let subdistrictName = '';
          
          if (customer.province) {
            provinceName = customer.province.name;
          }
          
          if (customer.district) {
            districtName = customer.district.name;
          }
          
          if (customer.subdistrict) {
            subdistrictName = customer.subdistrict.name;
          } else if (response.data.subdistrict_name) {
            subdistrictName = response.data.subdistrict_name;
          }
          
          $('#customer_province').val(provinceName);
          $('#customer_province_id').val(customer.province_id || '');
          $('#customer_city').val(districtName);
          $('#customer_city_id').val(customer.district_id || '');
          $('#customer_district').val(subdistrictName);
          $('#customer_district_id').val(customer.subdistrict_id || '');
          $('#customer_postal_code').val(customer.postal_code || '');
          
          // Set display values
          $('#customer_name_display').text(customer.name);
          $('#customer_email_display').text(customer.email || '-');
          $('#customer_phone_display').text(customer.phone || '-');
          $('#customer_type_display').text(categoryName);
          $('#customer_address_display').text(customer.address || '-');
          $('#customer_province_display').text(provinceName || '-');
          $('#customer_city_display').text(districtName || '-');
          $('#customer_district_display').text(subdistrictName || '-');
          $('#customer_postal_code_display').text(customer.postal_code || '-');
          
          // Show customer info and hide placeholder
          $('#customer-info-placeholder').hide();
          $('#customer-info').show();
          
          // Show shipping address buttons
          $('#shipping-address-buttons').show();
          
          // Actualizar la disponibilidad del botón de agregar productos
          checkAddProductAvailability();
          
          // Close modal
          $('#customerModal').modal('hide');
        }
    
    // Event listener for use customer address button
    $('#use-customer-address').on('click', function() {
      // Copy customer address to shipping address
      $('#shipping_name').val($('#customer_name').val());
      $('#shipping_phone').val($('#customer_phone').val());
      $('#shipping_email').val($('#customer_email').val());
      $('#shipping_address').val($('#customer_address').val());
      
      // Set province, city, district
      const provinceId = $('#customer_province_id').val();
      const cityId = $('#customer_city_id').val();
      const districtId = $('#customer_district_id').val();
      
      if (provinceId) {
        $('#province_id').val(provinceId).trigger('change');
        
        // Wait for cities to load
        setTimeout(function() {
          if (cityId) {
            $('#city_id').val(cityId).trigger('change');
            
            // Wait for subdistricts to load
            setTimeout(function() {
              if (districtId) {
                $('#district_id').val(districtId).trigger('change');
              }
            }, 500);
          }
        }, 500);
      }
      
      $('#postal_code').val($('#customer_postal_code').val());
      
      // Show use other address button and hide use customer address button
      $(this).hide();
      $('#use-other-address').show();
    });
    
    // Event listener for use other address button
    $('#use-other-address').on('click', function() {
      // Clear shipping address
      $('#shipping_name').val('');
      $('#shipping_phone').val('');
      $('#shipping_email').val('');
      $('#shipping_address').val('');
      $('#province_id').val('').trigger('change');
      $('#city_id').val('').prop('disabled', true);
      $('#district_id').val('').prop('disabled', true);
      $('#postal_code').val('');
      
      // Show use customer address button and hide use other address button
      $(this).hide();
      $('#use-customer-address').show();
    });
    
    // Event listener for save order button
    $('#saveOrderBtn').on('click', function() {
      // Validate form
      if (!validateOrderForm()) {
        return;
      }
      
      // Tampilkan loading
      Swal.fire({
        title: 'Menyimpan Order',
        text: 'Mohon tunggu...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      // Kumpulkan data form
      const formData = new FormData($('#orderForm')[0]);
      
      // Konversi FormData ke objek untuk manipulasi
      const formObject = {};
      formData.forEach((value, key) => {
        // Jika key mengandung [] (array), tangani secara khusus
        if (key.includes('[') && key.includes(']')) {
          const matches = key.match(/([^\[]+)\[([^\]]*)\](.+)?/);
          if (matches) {
            const mainKey = matches[1];
            const subKey = matches[2];
            const restKey = matches[3] || '';
            
            if (!formObject[mainKey]) {
              formObject[mainKey] = {};
            }
            
            if (subKey === '') {
              // Handle array without index like products[]
              if (!Array.isArray(formObject[mainKey])) {
                formObject[mainKey] = [];
              }
              formObject[mainKey].push(value);
            } else if (restKey) {
              // Handle nested objects like products[0][name]
              const nestedMatches = restKey.match(/\[([^\]]*)\]/g);
              if (nestedMatches) {
                if (!formObject[mainKey][subKey]) {
                  formObject[mainKey][subKey] = {};
                }
                
                let currentObj = formObject[mainKey][subKey];
                let lastKey = '';
                
                nestedMatches.forEach((match, index) => {
                  const nestedKey = match.replace(/[\[\]]/g, '');
                  
                  if (index === nestedMatches.length - 1) {
                    lastKey = nestedKey;
                  } else {
                    if (!currentObj[nestedKey]) {
                      currentObj[nestedKey] = {};
                    }
                    currentObj = currentObj[nestedKey];
                  }
                });
                
                currentObj[lastKey] = value;
              }
            } else {
              // Handle simple objects like customer[name]
              formObject[mainKey][subKey] = value;
            }
          }
        } else {
          formObject[key] = value;
        }
      });
      
      // Kirim data dengan AJAX
      $.ajax({
        url: "{{ route('admin.orders.store') }}",
        type: 'POST',
        data: JSON.stringify(formObject),
        processData: false,
        contentType: 'application/json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: response.message || 'Order berhasil dibuat',
              showConfirmButton: true,
              confirmButtonText: 'Lihat Detail Order'
            }).then((result) => {
              if (result.isConfirmed) {
                // Redirect ke halaman detail order
                window.location.href = response.redirect_url;
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: response.message || 'Terjadi kesalahan saat menyimpan order',
              showConfirmButton: true
            });
          }
        },
        error: function(xhr) {
          let errorMessage = 'Terjadi kesalahan saat menyimpan order';
          
          // Cek apakah ada pesan error dari server
          if (xhr.responseJSON) {
            if (xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }
            
            // Jika ada error validasi, tampilkan pesan error pertama
            if (xhr.responseJSON.errors) {
              // Cek apakah ada error stok khusus
              if (xhr.responseJSON.errors.stock) {
                // Tampilkan semua error stok dalam format list
                const stockErrors = xhr.responseJSON.errors.stock;
                let stockErrorHtml = '<ul class="text-left mb-0">';
                stockErrors.forEach(error => {
                  stockErrorHtml += `<li>${error}</li>`;
                });
                stockErrorHtml += '</ul>';
                
                Swal.fire({
                  icon: 'error',
                  title: 'Stok Tidak Mencukupi',
                  html: stockErrorHtml,
                  showConfirmButton: true
                });
                return;
              } else {
                // Tampilkan error validasi lainnya
                const firstError = Object.values(xhr.responseJSON.errors)[0];
                if (firstError && firstError.length > 0) {
                  errorMessage = firstError[0];
                }
              }
            }
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage,
            showConfirmButton: true
          });
        }
      });
    });
    
    // Validate order form
    function validateOrderForm() {
      // Check if customer is selected
      if (!$('#customer_id').val()) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan pilih customer terlebih dahulu',
          showConfirmButton: true
        });
        return false;
      }
      
      // Check if products are added
      if ($('.product-row').length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan tambahkan minimal satu produk',
          showConfirmButton: true
        });
        return false;
      }
      
      // Check if warehouse is selected
      if (!$('#warehouse_id').val()) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan pilih gudang',
          showConfirmButton: true
        });
        return false;
      }
      
      // Check shipping information
      if (!$('#shipping_name').val()) {
        Swal.fire({
          icon: 'warning',
          title: 'Perhatian!',
          text: 'Silakan lengkapi informasi pengiriman',
          showConfirmButton: true
        });
        return false;
      }
      
      return true;
    }
  });
</script>
@endsection