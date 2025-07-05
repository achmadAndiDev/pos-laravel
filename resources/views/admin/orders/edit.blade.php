@extends('admin.layouts.app')

@section('title', 'Edit Order')
@section('subtitle', 'Edit Order #' . $order->order_id)

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.css" rel="stylesheet">
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
  
  /* Product image styles */
  .cursor-pointer {
    cursor: pointer;
  }
  .avatar[style*="background-image"] {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }
  .avatar[style*="background-image"]:hover {
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali ke Daftar Order
  </a>
  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
    <i class="ti ti-eye"></i>
    Lihat Order
  </a>
  <button type="button" id="saveOrderBtn" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-device-floppy"></i>
    Simpan Perubahan
  </button>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <!-- Status Card -->
  <div class="col-12 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="d-flex align-items-center">
              <div class="me-3">
                @php
                  $statusClass = 'bg-yellow';
                  $statusIcon = 'ti-clock';
                  
                  if ($order->order_status === 'COMPLETED') {
                      $statusClass = 'bg-success';
                      $statusIcon = 'ti-check';
                  } elseif ($order->order_status === 'CANCELLED') {
                      $statusClass = 'bg-danger';
                      $statusIcon = 'ti-x';
                  } elseif ($order->order_status === 'PROCESSING') {
                      $statusClass = 'bg-info';
                      $statusIcon = 'ti-package';
                  } elseif ($order->order_status === 'SHIPPED') {
                      $statusClass = 'bg-primary';
                      $statusIcon = 'ti-truck';
                  }
                  
                  $paymentStatusClass = 'bg-yellow';
                  $paymentStatusIcon = 'ti-clock';
                  
                  if ($order->payment_status === 'PAID') {
                      $paymentStatusClass = 'bg-success';
                      $paymentStatusIcon = 'ti-check';
                  } elseif ($order->payment_status === 'INSTALLMENT') {
                      $paymentStatusClass = 'bg-info';
                      $paymentStatusIcon = 'ti-cash';
                  } elseif ($order->payment_status === 'CANCELLED') {
                      $paymentStatusClass = 'bg-danger';
                      $paymentStatusIcon = 'ti-x';
                  }
                @endphp
                <span class="avatar avatar-lg {{ $statusClass }} text-white">
                  <i class="ti {{ $statusIcon }}"></i>
                </span>
              </div>
              <div>
                <div class="input-group mb-2">
                  <span class="input-group-text">Order ID</span>
                  <input type="text" class="form-control" value="{{ $order->order_id }}" readonly>
                  <input type="hidden" id="order_id" name="order_id" value="{{ $order->order_id }}">
                </div>
                <div class="text-muted">
                  Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}
                  @if($order->created_by_name)
                  oleh {{ $order->created_by_name }}
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mt-3 mt-md-0">
            <div class="row g-2">
              <div class="col-6">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="avatar {{ $statusClass }} text-white">
                          <i class="ti {{ $statusIcon }}"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="text-muted">Status Order</div>
                        <div class="font-weight-medium">
                          <div class="form-control-plaintext">
                            @if ($order->order_status === 'PENDING_PAYMENT')
                              Menunggu Pembayaran
                            @elseif($order->order_status === 'PROCESSING')
                              Diproses
                            @elseif($order->order_status === 'SHIPPED')
                              Dikirim
                            @elseif($order->order_status === 'DELIVERED')
                              Diterima
                            @elseif($order->order_status === 'COMPLETED')
                              Selesai
                            @elseif($order->order_status === 'CANCELLED')
                              Dibatalkan
                            @else
                              {{ $order->order_status }}
                            @endif
                          </div>
                          <input type="hidden" id="order_status" name="order_status" value="{{ $order->order_status }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="avatar {{ $paymentStatusClass }} text-white">
                          <i class="ti {{ $paymentStatusIcon }}"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="text-muted">Pembayaran</div>
                        <div class="font-weight-medium">
                          <div class="form-control-plaintext">
                            @if ($order->payment_status === 'UNPAID')
                              Belum Dibayar
                            @elseif($order->payment_status === 'INSTALLMENT')
                              Sebagian
                            @elseif($order->payment_status === 'PAID')
                              Lunas
                            @elseif($order->payment_status === 'REFUNDED')
                              Dikembalikan
                            @else
                              {{ $order->payment_status }}
                            @endif
                          </div>
                          <input type="hidden" id="payment_status" name="payment_status" value="{{ $order->payment_status }}">
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
              <input type="text" class="form-control" id="order_id_display" value="{{ $order->order_id }}" readonly>
              <input type="hidden" id="order_id" name="order_id" value="{{ $order->order_id }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Order</label>
              <input type="text" class="form-control" value="{{ $order->order_date->format('d M Y, H:i') }}" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Sumber Order</label>
              <input type="text" class="form-control" value="{{ $order->orderSource ? $order->orderSource->name : 'Website' }}" readonly>
              <input type="hidden" id="order_source_id" name="order_source_id" value="{{ $order->orderSource ? $order->orderSource->id : '' }}">
            </div>
            <div class="mb-3">
              <label class="form-label">ID External</label>
              <input type="text" class="form-control" value="{{ $order->external_id }}" readonly>
              <input type="hidden" id="external_id" name="external_id" value="{{ $order->external_id }}">
            </div>
          </div>
          
          <!-- Kolom Kanan -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Total Order</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" value="{{ number_format($order->total_amount, 0, ',', '.') }}" readonly>
                <input type="hidden" id="total_amount" name="total_amount" value="{{ $order->total_amount }}">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Ongkos Kirim</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" value="{{ number_format($order->shipping_cost, 0, ',', '.') }}" readonly>
                <input type="hidden" id="shipping_cost" name="shipping_cost" value="{{ $order->shipping_cost }}">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Diskon</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" value="{{ number_format($order->discount_amount, 0, ',', '.') }}" readonly>
                <input type="hidden" id="discount_amount" name="discount_amount" value="{{ $order->discount_amount }}">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Berat (Gram)</label>
              <input type="text" class="form-control" value="{{ $order->weight }}" readonly>
              <input type="hidden" id="weight" name="weight" value="{{ $order->weight }}">
            </div>
          </div>
        </div>
        
        <div class="mt-3 pt-3 border-top">
          <label class="form-label">Catatan Order</label>
          <textarea class="form-control" id="note" name="note" rows="3">{{ $order->note }}</textarea>
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
          <button type="button" class="btn btn-sm btn-primary" id="addProductBtn">
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
              @php
                $subtotal = 0;
              @endphp
              @forelse($order->products as $index => $product)
                @php
                  $productSubtotal = $product->order_price * $product->quantity;
                  $subtotal += $productSubtotal;
                @endphp
                <tr class="product-row" data-id="{{ $product->id }}">
                  <td>
                    <div class="d-flex align-items-center">
                      @php
                        $variation = $product->variation;
                        $thumbnailPath = $variation ? $variation->thumbnail_path : null;
                        $imagePath = $variation ? $variation->image_path : null;
                        $displayImage = $thumbnailPath ?? $imagePath ?? $product->image_path;
                      @endphp
                      
                      @if($displayImage)
                        <a href="{{ asset('storage/' . ($variation && $variation->image_path ? $variation->image_path : $displayImage)) }}" 
                           data-fslightbox="product-images" 
                           class="cursor-pointer">
                            <span class="avatar me-2" style="background-image:url('{{ asset('storage/' . $displayImage) }}')"></span>
                        </a>
                      @else
                        <span class="avatar me-2 bg-muted">
                          <i class="ti ti-box"></i>
                        </span>
                      @endif
                      
                      <div>
                        <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                        <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->product_id }}">
                        <input type="hidden" name="products[{{ $index }}][variant_id]" value="{{ $product->product_variation_id }}">
                        <input type="hidden" name="products[{{ $index }}][warehouse_id]" value="{{ $product->warehouse_id }}">
                        <input type="text" class="form-control product-name" name="products[{{ $index }}][name]" value="{{ $product->name }}" readonly>
                        <div class="text-muted small">
                          SKU: {{ $product->product_meta_id }}
                          @if($variation && ($variation->size || $variation->color))
                            <br>
                            @if($variation->size) <span class="badge bg-blue-lt">Size: {{ $variation->size }}</span> @endif
                            @if($variation->color) <span class="badge bg-purple-lt">Color: {{ $variation->color }}</span> @endif
                          @endif
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <input type="number" class="form-control text-center product-price" name="products[{{ $index }}][price]" value="{{ $product->order_price }}" min="0" readonly>
                  </td>
                  <td>
                    <input type="number" class="form-control text-center product-quantity" name="products[{{ $index }}][quantity]" value="{{ $product->quantity }}" min="1">
                  </td>
                  <td class="text-end product-subtotal">Rp {{ number_format($productSubtotal, 0, ',', '.') }}</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                      <i class="ti ti-trash"></i>
                    </button>
                  </td>
                </tr>
              @empty
                <tr id="no-products-row">
                  <td colspan="5" class="text-center">Tidak ada produk</td>
                </tr>
              @endforelse
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="3" class="text-end">Subtotal:</td>
                <td class="text-end" id="products-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end">Ongkos Kirim:</td>
                <td class="text-end" id="shipping-cost-display">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end">Diskon:</td>
                <td class="text-end" id="discount-amount-display">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total:</td>
                <td class="text-end fw-bold" id="total-amount-display">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
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
    <!-- Informasi Customer -->
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title">
          <i class="ti ti-user me-2 text-primary"></i>
          Informasi Customer
        </h3>
      </div>
      <div class="card-body">
        @php
          $customer = isset($order->customer) && is_string($order->customer) ? json_decode($order->customer, true) : (is_array($order->customer) ? $order->customer : null);
        @endphp
        
        @if($customer)
          <div class="mb-3">
            <label class="form-label">Nama Customer</label>
            <div class="form-control-plaintext">{{ $customer['name'] ?? '-' }}</div>
            <input type="hidden" id="customer_name" name="customer[name]" value="{{ $customer['name'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="form-control-plaintext">{{ $customer['email'] ?? '-' }}</div>
            <input type="hidden" id="customer_email" name="customer[email]" value="{{ $customer['email'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Telepon</label>
            <div class="form-control-plaintext">{{ $customer['phone'] ?? '-' }}</div>
            <input type="hidden" id="customer_phone" name="customer[phone]" value="{{ $customer['phone'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe</label>
            <div class="form-control-plaintext">{{ $customer['type'] ?? '-' }}</div>
            <input type="hidden" id="customer_type" name="customer[type]" value="{{ $customer['type'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <div class="form-control-plaintext">{{ $customer['address'] ?? '-' }}</div>
            <input type="hidden" id="customer_address" name="customer[address]" value="{{ $customer['address'] ?? '' }}">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Provinsi</label>
              <div class="form-control-plaintext">{{ $customer['province'] ?? '-' }}</div>
              <input type="hidden" id="customer_province" name="customer[province]" value="{{ $customer['province'] ?? '' }}">
              <input type="hidden" name="customer[province_id]" value="{{ $customer['province_id'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Kota/Kabupaten</label>
              <div class="form-control-plaintext">{{ $customer['city'] ?? '-' }}</div>
              <input type="hidden" id="customer_city" name="customer[city]" value="{{ $customer['city'] ?? '' }}">
              <input type="hidden" name="customer[city_id]" value="{{ $customer['city_id'] ?? '' }}">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Kecamatan</label>
              <div class="form-control-plaintext">{{ $customer['district'] ?? '-' }}</div>
              <input type="hidden" id="customer_district" name="customer[district]" value="{{ $customer['district'] ?? '' }}">
              <input type="hidden" name="customer[district_id]" value="{{ $customer['district_id'] ?? '' }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Kode Pos</label>
              <div class="form-control-plaintext">{{ $customer['postal_code'] ?? '-' }}</div>
              <input type="hidden" id="customer_postal_code" name="customer[postal_code]" value="{{ $customer['postal_code'] ?? '' }}">
            </div>
          </div>
        @else
          <div class="alert alert-warning">
            Data customer tidak tersedia atau tidak dalam format yang benar.
          </div>
        @endif
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
        @php
          $receiver = isset($order->receiver) && is_string($order->receiver) ? json_decode($order->receiver, true) : (is_array($order->receiver) ? $order->receiver : (isset($order->shipping_address) && is_array($order->shipping_address) ? $order->shipping_address : null));
          $customer = isset($order->customer) && is_string($order->customer) ? json_decode($order->customer, true) : (is_array($order->customer) ? $order->customer : null);
        @endphp
        
        @if($customer)
        <div class="mb-3">
          <button type="button" class="btn btn-info btn-sm" id="use-customer-address">
            <i class="ti ti-copy"></i> Gunakan Alamat Customer
          </button>
          <button type="button" class="btn btn-danger btn-sm" id="use-other-address" style="display:none;">
            <i class="ti ti-pencil"></i> Gunakan Alamat Lain
          </button>
        </div>
        @endif
        
        @if($receiver)
          <div class="mb-3">
            <label class="form-label">Nama Penerima</label>
            <input type="text" class="form-control" id="shipping_name" name="receiver[name]" value="{{ $receiver['name'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" class="form-control" id="shipping_phone" name="receiver[phone]" value="{{ $receiver['phone'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="shipping_email" name="receiver[email]" value="{{ $receiver['email'] ?? '' }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" id="shipping_address" name="receiver[address]" rows="2">{{ $receiver['address'] ?? '' }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Provinsi</label>
              <select class="form-select select2-ajax" id="province_id" name="receiver[province_id]" data-url="{{ route('admin.provinces.index') }}" required>
                <option value="">Pilih Provinsi</option>
                @if(isset($receiver['province_id']) && isset($receiver['province']))
                  <option value="{{ $receiver['province_id'] }}" selected>{{ $receiver['province'] }}</option>
                @endif
              </select>
              <input type="hidden" id="province_name" name="receiver[province]" value="{{ $receiver['province'] ?? '' }}">
              @error('receiver.province_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kota/Kabupaten</label>
              <select class="form-select select2-ajax" id="city_id" name="receiver[city_id]" data-url="{{ route('admin.cities.by-province') }}" data-depends-on="province_id" required>
                <option value="">Pilih Kota/Kabupaten</option>
                @if(isset($receiver['city_id']) && isset($receiver['city']))
                  <option value="{{ $receiver['city_id'] }}" selected>{{ $receiver['city'] }}</option>
                @endif
              </select>
              <input type="hidden" id="city_name" name="receiver[city]" value="{{ $receiver['city'] ?? '' }}">
              @error('receiver.city_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kecamatan</label>
              <select class="form-select select2-ajax" id="subdistrict_id" name="receiver[district_id]" data-url="{{ route('admin.subdistricts.by-city') }}" data-depends-on="city_id" required>
                <option value="">Pilih Kecamatan</option>
                @if(isset($receiver['district_id']) && isset($receiver['district']))
                  <option value="{{ $receiver['district_id'] }}" selected>{{ $receiver['district'] }}</option>
                @endif
              </select>
              <input type="hidden" id="subdistrict_name" name="receiver[district]" value="{{ $receiver['district'] ?? '' }}">
              @error('receiver.district_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kode Pos</label>
              <select class="form-select" id="postal_code_select" name="receiver[postal_code]" required>
                <option value="">Pilih Kode Pos</option>
                @if(isset($receiver['postal_code']) && $receiver['postal_code'])
                  <option value="{{ $receiver['postal_code'] }}" selected>{{ $receiver['postal_code'] }}</option>
                @endif
              </select>
              @error('receiver.postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="mt-3">
            <button type="button" class="btn btn-primary" id="updateReceiverBtn">
              <i class="ti ti-user-check"></i> Update Informasi Penerima
            </button>
          </div>
        @else
          <div class="alert alert-warning">
            Data alamat pengiriman tidak tersedia atau tidak dalam format yang benar.
          </div>
        @endif
        
        <div class="border-top pt-3 mt-3">
          <div class="mb-3">
            <label class="form-label">Kurir / Logistik</label>
            <select class="form-select" id="shipping_logistic" name="shipping_logistic">
              <option value="">Pilih Kurir</option>
              @php
                // Get expeditions from the database
                $expeditions = \App\Models\CustomExpedition::orderBy('name')->get();
              @endphp
              
              @foreach ($expeditions as $expedition)
                  <option value="{{ $expedition->id }}" {{ $order->shipping_id == $expedition->id ? 'selected' : '' }}>
                      {{ $expedition->name }}
                  </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Resi</label>
            <div class="input-group">
              <input type="text" class="form-control" id="awb_number" name="awb_number" value="{{ $order->awb_number }}">
              <button type="button" class="btn btn-primary" id="updateTrackingBtn">
                <i class="ti ti-truck-delivery"></i> Update Resi
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Riwayat Pembayaran -->
    <div class="card d-none">
      <div class="card-header">
        <h3 class="card-title">
          <i class="ti ti-cash me-2 text-primary"></i>
          Riwayat Pembayaran
        </h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @php
                $totalPaid = 0;
              @endphp
              @forelse($order->paymentHistories as $payment)
                @php
                  if ($payment->is_confirmed) {
                    $totalPaid += $payment->nominal;
                  }
                @endphp
                <tr>
                  <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : $payment->created_at->format('d/m/Y') }}</td>
                  <td>Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
                  <td>{{ $payment->description }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center">Belum ada pembayaran</td>
                </tr>
              @endforelse
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td class="fw-bold">Total Dibayar</td>
                <td class="fw-bold" colspan="2">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
              </tr>
              <tr>
                <td class="fw-bold">Sisa</td>
                <td class="fw-bold" colspan="2">Rp {{ number_format(max(0, $order->total_amount - $totalPaid), 0, ',', '.') }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Modal Tambah Produk -->
<div class="modal modal-blur fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
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
          // Keep the selected option
          const selectedValue = $('#province_id').val();
          const selectedOption = $('#province_id option:selected').clone();
          
          // Clear and add default option
          $('#province_id').empty().append('<option value="">Pilih Provinsi</option>');
          
          // Add the previously selected option if it exists
          if (selectedOption.val()) {
            $('#province_id').append(selectedOption);
          }
          
          // Add new options
          $.each(data, function(index, province) {
            // Skip if already selected
            if (province.id != selectedValue) {
              $('#province_id').append('<option value="' + province.id + '">' + province.text + '</option>');
            }
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
          // Keep the selected option if it belongs to this province
          const selectedValue = $('#city_id').val();
          const selectedOption = $('#city_id option:selected').clone();
          
          // Clear and add default option
          $('#city_id').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
          
          // Add the previously selected option if it exists
          if (selectedOption.val()) {
            $('#city_id').append(selectedOption);
          }
          
          // Add new options
          $.each(data, function(index, city) {
            // Skip if already selected
            if (city.id != selectedValue) {
              $('#city_id').append('<option value="' + city.id + '">' + city.text + '</option>');
            }
          });
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
          // Keep the selected option if it belongs to this city
          const selectedValue = $('#subdistrict_id').val();
          const selectedOption = $('#subdistrict_id option:selected').clone();
          
          // Clear and add default option
          $('#subdistrict_id').empty().append('<option value="">Pilih Kecamatan</option>');
          
          // Add the previously selected option if it exists
          if (selectedOption.val()) {
            $('#subdistrict_id').append(selectedOption);
          }
          
          // Add new options
          $.each(data, function(index, subdistrict) {
            // Skip if already selected
            if (subdistrict.id != selectedValue) {
              $('#subdistrict_id').append('<option value="' + subdistrict.id + '">' + subdistrict.text + '</option>');
            }
          });
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
          // Keep the selected option
          const selectedValue = $('#postal_code_select').val();
          
          // Clear and add default option
          $('#postal_code_select').empty().append('<option value="">Pilih Kode Pos</option>');
          
          // Add new options
          if (data && data.length > 0) {
            $.each(data, function(index, postalCode) {
              const selected = (postalCode === selectedValue) ? 'selected' : '';
              $('#postal_code_select').append('<option value="' + postalCode + '" ' + selected + '>' + postalCode + '</option>');
            });
          }
        }
      });
    }
    
    // Event handlers for select changes
    $('#province_id').on('change', function() {
      const provinceId = $(this).val();
      const provinceName = $(this).find('option:selected').text();
      
      // Update hidden field
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
    
    // Load initial data
    loadProvinces();
    
    // If province_id is already set, load cities
    const provinceId = $('#province_id').val();
    if (provinceId) {
      loadCities(provinceId);
      
      // If city_id is already set, load subdistricts
      const cityId = $('#city_id').val();
      if (cityId) {
        loadSubdistricts(cityId);
        
        // If subdistrict_id is already set, load postal codes
        const subdistrictId = $('#subdistrict_id').val();
        if (subdistrictId) {
          loadPostalCodes(subdistrictId);
        }
      }
    }
    
    // Inisialisasi
    let orderId = {{ $order->id }};
    let productCounter = {{ count($order->products) }};
    let selectedProduct = null;
    let productVariants = [];
    let productWarehouses = [];
    
    // Fungsi untuk memformat angka ke format Rupiah
    function formatRupiah(angka) {
      return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }
    
    // Fungsi untuk menghitung ulang total
    function recalculateTotal() {
      // Hitung subtotal produk
      let subtotal = 0;
      $('.product-row').each(function() {
        const price = parseFloat($(this).find('.product-price').val()) || 0;
        const quantity = parseInt($(this).find('.product-quantity').val()) || 0;
        const rowSubtotal = price * quantity;
        subtotal += rowSubtotal;
        
        // Update subtotal di baris
        $(this).find('.product-subtotal').text(formatRupiah(rowSubtotal));
      });
      
      // Update subtotal di tabel
      $('#products-subtotal').text(formatRupiah(subtotal));
      
      // Ambil nilai ongkir dan diskon dari hidden fields
      const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
      const discountAmount = parseFloat($('#discount_amount').val()) || 0;
      
      // Hitung total
      const total = subtotal + shippingCost - discountAmount;
      
      // Update total di tabel
      $('#total-amount-display').text(formatRupiah(total));
      
      // Total amount akan dihitung di server berdasarkan produk yang dipilih
      // Tidak perlu update hidden field untuk total
    }
    
    // Event listener untuk perubahan harga atau jumlah produk
    $(document).on('change', '.product-price, .product-quantity', function() {
      recalculateTotal();
    });
    
    // Event listener untuk tombol hapus produk
    $(document).on('click', '.remove-product', function() {
      if (confirm('Apakah Anda yakin ingin menghapus produk ini dari order?')) {
        $(this).closest('tr').remove();
        
        // Jika tidak ada produk, tampilkan pesan
        if ($('.product-row').length === 0) {
          $('#products-table tbody').append('<tr id="no-products-row"><td colspan="5" class="text-center">Tidak ada produk</td></tr>');
        }
        
        // Hitung ulang total
        recalculateTotal();
      }
    });
    
    // Event listener untuk tombol tambah produk
    $('#addProductBtn').on('click', function() {
      // Reset modal
      resetProductModal();
      
      // Tampilkan modal
      $('#addProductModal').modal('show');
    });
    
    // Fungsi untuk reset modal produk
    function resetProductModal() {
      $('#product-search').val('');
      $('#product-category-filter').val('');
      $('#product-list-container').html('<div class="col-12"><div class="alert alert-info">Silakan cari produk untuk menampilkan daftar produk</div></div>');
      $('#selected-product-section').hide();
      $('#product-form')[0].reset();
      $('#add-product-btn').prop('disabled', true);
      selectedProduct = null;
      productVariants = [];
      productWarehouses = [];
    }
    
    // Event listener untuk tombol cari produk
    $('#search-product-btn').on('click', function() {
      searchProducts();
    });
    
    // Event listener untuk enter key pada input pencarian
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
      const customerCategory = '{{ $order->customer->category ?? "Customer" }}'; // Get customer category from order
      
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
    
    // Event listener untuk tombol pilih produk
    $(document).on('click', '.select-product', function() {
      const productCard = $(this).closest('.product-card');
      const productId = productCard.data('id');
      const customerCategory = '{{ $order->customer->category ?? "Customer" }}'; // Get customer category from order
      
      // Tampilkan loading
      $('#selected-product-section').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Memuat detail produk...</div></div>').show();
      
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
            
            // Set image from variation if available
            if (variation.image_path) {
              imageUrl = '/storage/' + variation.image_path;
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
        },
        error: function() {
          $('#selected-product-section').html('<div class="alert alert-danger">Gagal memuat detail produk</div>');
        }
      });
    });
    
    // Fungsi untuk memuat varian produk
    function loadProductVariants(productId) {
      const customerCategory = '{{ $order->customer->category ?? "Customer" }}'; // Get customer category from order
      
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
    
    // Fungsi untuk memuat gudang sudah tidak diperlukan karena gudang diambil dari detail produk
    
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
    
    // Event listener untuk tombol tambah produk
    $('#add-product-btn').on('click', function() {
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
        Swal.fire({
          title: 'Perhatian!',
          text: 'Silakan pilih produk terlebih dahulu',
          icon: 'warning',
          confirmButtonText: 'OK'
        });
        return;
      }
      
      // Jika tidak ada gudang yang dipilih, tampilkan pesan error
      if (!warehouseId) {
        Swal.fire({
          title: 'Perhatian!',
          text: 'Silakan pilih gudang terlebih dahulu',
          icon: 'warning',
          confirmButtonText: 'OK'
        });
        return;
      }
      
      // Tambahkan baris produk baru
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
            <input type="number" class="form-control text-center product-price " name="products[${productCounter}][price]" value="${productPrice}" min="0" readonly>
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
      productCounter++;
      
      // Hitung ulang total
      recalculateTotal();
      
      // Tutup modal
      $('#addProductModal').modal('hide');
      
      // Reset form
      resetProductModal();
    });
    
    // Event listener untuk tombol simpan perubahan
    $('#saveOrderBtn').on('click', function() {
      // Kumpulkan data order
      const orderData = {
        order_id: $('#order_id').val(),
        order_status: $('#order_status').val(), // Sekarang menggunakan hidden input
        payment_status: $('#payment_status').val(), // Sekarang menggunakan hidden input
        note: $('#note').val(),
        shipping_logistic: $('#shipping_logistic').val(),
        shipping_cost: parseFloat($('#shipping_cost').val()) || 0,
        discount_amount: parseFloat($('#discount_amount').val()) || 0,
        awb_number: $('#awb_number').val()
      };
      
      // Kumpulkan data customer
      const customerData = {};
      $('input[name^="customer["], textarea[name^="customer["]').each(function() {
        const key = $(this).attr('name').match(/customer\[(.*?)\]/)[1];
        customerData[key] = $(this).val();
      });
      
      // Kumpulkan data receiver
      const receiverData = {};
      $('input[name^="receiver["], textarea[name^="receiver["]').each(function() {
        const key = $(this).attr('name').match(/receiver\[(.*?)\]/)[1];
        receiverData[key] = $(this).val();
      });
      
      // Kumpulkan data produk
      const productsData = [];
      $('.product-row').each(function() {
        const productId = $(this).find('input[name$="[id]"]').val();
        const productName = $(this).find('input[name$="[name]"]').val();
        const productPrice = parseFloat($(this).find('input[name$="[price]"]').val()) || 0;
        const productQuantity = parseInt($(this).find('input[name$="[quantity]"]').val()) || 1;
        const variantId = $(this).find('input[name$="[variant_id]"]').val() || null;
        const warehouseId = $(this).find('input[name$="[warehouse_id]"]').val() || null;
        const productId2 = $(this).find('input[name$="[product_id]"]').val() || null;
        
        productsData.push({
          id: productId,
          name: productName,
          price: productPrice,
          quantity: productQuantity,
          variant_id: variantId,
          warehouse_id: warehouseId,
          product_id: productId2
        });
      });
      
      // Gabungkan semua data
      const data = {
        ...orderData,
        customer: JSON.stringify(customerData),
        receiver: JSON.stringify(receiverData),
        products: productsData
      };
      
      // Tampilkan loading
      $('#saveOrderBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i> Menyimpan...');
      
      // Kirim data ke server
      $.ajax({
        url: `/admin/orders/${orderId}`,
        method: 'PUT',
        data: data,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.success) {
            // Tampilkan pesan sukses dengan SweetAlert
            Swal.fire({
              title: 'Berhasil!',
              text: 'Order berhasil diperbarui',
              icon: 'success',
              confirmButtonText: 'OK'
            }).then((result) => {
              // Redirect ke halaman detail order
              window.location.href = `/admin/orders/${orderId}`;
            });
          } else {
            // Tampilkan pesan error dengan SweetAlert
            Swal.fire({
              title: 'Gagal!',
              text: 'Gagal memperbarui order: ' + response.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
            $('#saveOrderBtn').prop('disabled', false).html('<i class="ti ti-device-floppy"></i> Simpan Perubahan');
          }
        },
        error: function(xhr) {
          const response = xhr.responseJSON;
          // Tampilkan pesan error dengan SweetAlert
          Swal.fire({
            title: 'Gagal!',
            text: 'Gagal memperbarui order: ' + (response?.message || 'Terjadi kesalahan'),
            icon: 'error',
            confirmButtonText: 'OK'
          });
          $('#saveOrderBtn').prop('disabled', false).html('<i class="ti ti-device-floppy"></i> Simpan Perubahan');
        }
      });
    });
    
    // Inisialisasi perhitungan total
    recalculateTotal();
    
    // Event listener untuk tombol update tracking
    $('#updateTrackingBtn').on('click', function() {
      const awbNumber = $('#awb_number').val();
      const shippingLogistic = $('#shipping_logistic').val();
      
      // Validasi input
      if (!awbNumber) {
        Swal.fire({
          title: 'Perhatian!',
          text: 'Nomor resi tidak boleh kosong',
          icon: 'warning',
          confirmButtonText: 'OK'
        });
        return;
      }
      
      if (!shippingLogistic) {
        Swal.fire({
          title: 'Perhatian!',
          text: 'Silakan pilih kurir/logistik terlebih dahulu',
          icon: 'warning',
          confirmButtonText: 'OK'
        });
        return;
      }
      
      // Tampilkan loading
      $(this).prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i>');
      
      // Kirim data ke server
      $.ajax({
        url: `/admin/orders/${orderId}/update-tracking`,
        method: 'POST',
        data: {
          awb_number: awbNumber,
          shipping_logistic: shippingLogistic
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.success) {
            // Tampilkan pesan sukses dengan SweetAlert
            Swal.fire({
              title: 'Berhasil!',
              text: 'Informasi pengiriman berhasil diperbarui',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          } else {
            // Tampilkan pesan error dengan SweetAlert
            Swal.fire({
              title: 'Gagal!',
              text: 'Gagal memperbarui informasi pengiriman: ' + response.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
          // Reset button
          $('#updateTrackingBtn').prop('disabled', false).html('<i class="ti ti-truck-delivery"></i> Update Resi');
        },
        error: function(xhr) {
          const response = xhr.responseJSON;
          // Tampilkan pesan error dengan SweetAlert
          Swal.fire({
            title: 'Gagal!',
            text: 'Gagal memperbarui informasi pengiriman: ' + (response?.message || 'Terjadi kesalahan'),
            icon: 'error',
            confirmButtonText: 'OK'
          });
          // Reset button
          $('#updateTrackingBtn').prop('disabled', false).html('<i class="ti ti-truck-delivery"></i> Update Resi');
        }
      });
    });
    
    // Event listener untuk tombol update receiver
    $('#updateReceiverBtn').on('click', function() {      
      // Kumpulkan data receiver
      const receiverData = {};
      $('input[name^="receiver["], textarea[name^="receiver["]').each(function() {
        const key = $(this).attr('name').match(/receiver\[(.*?)\]/)[1];
        receiverData[key] = $(this).val();
      });
      
      // Tampilkan loading
      $(this).prop('disabled', true).html('<i class="ti ti-loader animate-spin"></i>');
      
      // Kirim data ke server
      $.ajax({
        url: `/admin/orders/${orderId}/update-receiver`,
        method: 'POST',
        data: {
          receiver: JSON.stringify(receiverData)
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.success) {
            // Tampilkan pesan sukses dengan SweetAlert
            Swal.fire({
              title: 'Berhasil!',
              text: 'Informasi penerima berhasil diperbarui',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          } else {
            // Tampilkan pesan error dengan SweetAlert
            Swal.fire({
              title: 'Gagal!',
              text: 'Gagal memperbarui informasi penerima: ' + response.message,
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
          // Reset button
          $('#updateReceiverBtn').prop('disabled', false).html('<i class="ti ti-user-check"></i> Update Informasi Penerima');
        },
        error: function(xhr) {
          const response = xhr.responseJSON;
          // Tampilkan pesan error dengan SweetAlert
          Swal.fire({
            title: 'Gagal!',
            text: 'Gagal memperbarui informasi penerima: ' + (response?.message || 'Terjadi kesalahan'),
            icon: 'error',
            confirmButtonText: 'OK'
          });
          // Reset button
          $('#updateReceiverBtn').prop('disabled', false).html('<i class="ti ti-user-check"></i> Update Informasi Penerima');
        }
      });
    });
    
    // Fungsi untuk menggunakan alamat customer sebagai alamat pengiriman
    $('#use-customer-address').on('click', function() {
      // Simpan data asli dalam data attribute untuk kemungkinan restore
      $('#shipping_name').data('original', $('#shipping_name').val());
      $('#shipping_phone').data('original', $('#shipping_phone').val());
      $('#shipping_email').data('original', $('#shipping_email').val());
      $('#shipping_address').data('original', $('#shipping_address').val());
      $('#province_id').data('original', $('#province_id').val());
      $('#province_name').data('original', $('#province_name').val());
      $('#city_id').data('original', $('#city_id').val());
      $('#city_name').data('original', $('#city_name').val());
      $('#subdistrict_id').data('original', $('#subdistrict_id').val());
      $('#subdistrict_name').data('original', $('#subdistrict_name').val());
      $('#postal_code_select').data('original', $('#postal_code_select').val());
      
      // Simpan status disabled asli
      $('#shipping_name').data('disabled-original', $('#shipping_name').prop('disabled'));
      $('#shipping_phone').data('disabled-original', $('#shipping_phone').prop('disabled'));
      $('#shipping_email').data('disabled-original', $('#shipping_email').prop('disabled'));
      $('#shipping_address').data('disabled-original', $('#shipping_address').prop('disabled'));
      $('#province_id').data('disabled-original', $('#province_id').prop('disabled'));
      $('#city_id').data('disabled-original', $('#city_id').prop('disabled'));
      $('#subdistrict_id').data('disabled-original', $('#subdistrict_id').prop('disabled'));
      $('#postal_code_select').data('disabled-original', $('#postal_code_select').prop('disabled'));
      
      // Ambil data customer dari hidden inputs
      const customerName = $('#customer_name').val();
      const customerPhone = $('#customer_phone').val();
      const customerEmail = $('#customer_email').val();
      const customerAddress = $('#customer_address').val();
      const customerProvince = $('#customer_province').val();
      const customerProvinceId = $('input[name="customer[province_id]"]').val();
      const customerCity = $('#customer_city').val();
      const customerCityId = $('input[name="customer[city_id]"]').val();
      const customerDistrict = $('#customer_district').val();
      const customerDistrictId = $('input[name="customer[district_id]"]').val();
      const customerPostalCode = $('#customer_postal_code').val();
      
      // Isi form pengiriman dengan data customer
      $('#shipping_name').val(customerName);
      $('#shipping_phone').val(customerPhone);
      $('#shipping_email').val(customerEmail);
      $('#shipping_address').val(customerAddress);
      
      // Update province select and load cities
      if (customerProvinceId) {
        // Add province option if not exists
        if ($('#province_id option[value="' + customerProvinceId + '"]').length === 0) {
          $('#province_id').append('<option value="' + customerProvinceId + '">' + customerProvince + '</option>');
        }
        
        // Select province
        $('#province_id').val(customerProvinceId).trigger('change');
        $('#province_name').val(customerProvince);
        
        // Load cities and continue with city, subdistrict, and postal code
        setTimeout(function() {
          // Add city option if not exists
          if ($('#city_id option[value="' + customerCityId + '"]').length === 0) {
            $('#city_id').append('<option value="' + customerCityId + '">' + customerCity + '</option>');
          }
          
          // Select city
          $('#city_id').val(customerCityId).trigger('change');
          $('#city_name').val(customerCity);
          
          // Load subdistricts
          setTimeout(function() {
            // Add subdistrict option if not exists
            if ($('#subdistrict_id option[value="' + customerDistrictId + '"]').length === 0) {
              $('#subdistrict_id').append('<option value="' + customerDistrictId + '">' + customerDistrict + '</option>');
            }
            
            // Select subdistrict
            $('#subdistrict_id').val(customerDistrictId).trigger('change');
            $('#subdistrict_name').val(customerDistrict);
            
            // Load postal codes
            setTimeout(function() {
              // Add postal code option if not exists
              if ($('#postal_code_select option[value="' + customerPostalCode + '"]').length === 0) {
                $('#postal_code_select').append('<option value="' + customerPostalCode + '">' + customerPostalCode + '</option>');
              }
              
              // Select postal code
              $('#postal_code_select').val(customerPostalCode);
            }, 300);
          }, 300);
        }, 300);
      }
      
      // Buat semua field menjadi disabled
      $('#shipping_name').prop('disabled', true);
      $('#shipping_phone').prop('disabled', true);
      $('#shipping_email').prop('disabled', true);
      $('#shipping_address').prop('disabled', true);
      $('#province_id').prop('disabled', true);
      $('#city_id').prop('disabled', true);
      $('#subdistrict_id').prop('disabled', true);
      $('#postal_code_select').prop('disabled', true);
      
      // Toggle tombol
      $(this).hide();
      $('#use-other-address').show();
    });
    
    // Fungsi untuk kembali ke alamat pengiriman asli
    $('#use-other-address').on('click', function() {
      // Kembalikan nilai asli jika ada
      $('#shipping_name').val($('#shipping_name').data('original') || '');
      $('#shipping_phone').val($('#shipping_phone').data('original') || '');
      $('#shipping_email').val($('#shipping_email').data('original') || '');
      $('#shipping_address').val($('#shipping_address').data('original') || '');
      
      // Restore province, city, subdistrict, and postal code
      const originalProvinceId = $('#province_id').data('original');
      const originalProvinceName = $('#province_name').data('original');
      const originalCityId = $('#city_id').data('original');
      const originalCityName = $('#city_name').data('original');
      const originalSubdistrictId = $('#subdistrict_id').data('original');
      const originalSubdistrictName = $('#subdistrict_name').data('original');
      const originalPostalCode = $('#postal_code_select').data('original');
      
      // Restore province and load cities
      if (originalProvinceId) {
        // Select province
        $('#province_id').val(originalProvinceId).trigger('change');
        $('#province_name').val(originalProvinceName);
        
        // Load cities and continue with city, subdistrict, and postal code
        setTimeout(function() {
          if (originalCityId) {
            // Select city
            $('#city_id').val(originalCityId).trigger('change');
            $('#city_name').val(originalCityName);
            
            // Load subdistricts
            setTimeout(function() {
              if (originalSubdistrictId) {
                // Select subdistrict
                $('#subdistrict_id').val(originalSubdistrictId).trigger('change');
                $('#subdistrict_name').val(originalSubdistrictName);
                
                // Load postal codes
                setTimeout(function() {
                  // Select postal code
                  $('#postal_code_select').val(originalPostalCode);
                }, 300);
              }
            }, 300);
          }
        }, 300);
      } else {
        // Reset all dropdowns if no province was selected
        $('#province_id').val('').trigger('change');
        $('#province_name').val('');
        $('#city_id').val('').trigger('change');
        $('#city_name').val('');
        $('#subdistrict_id').val('').trigger('change');
        $('#subdistrict_name').val('');
        $('#postal_code_select').val('');
      }
      
      // Kembalikan status disabled ke nilai asli
      $('#shipping_name').prop('disabled', $('#shipping_name').data('disabled-original') || false);
      $('#shipping_phone').prop('disabled', $('#shipping_phone').data('disabled-original') || false);
      $('#shipping_email').prop('disabled', $('#shipping_email').data('disabled-original') || false);
      $('#shipping_address').prop('disabled', $('#shipping_address').data('disabled-original') || false);
      $('#province_id').prop('disabled', $('#province_id').data('disabled-original') || false);
      $('#city_id').prop('disabled', $('#city_id').data('disabled-original') || false);
      $('#subdistrict_id').prop('disabled', $('#subdistrict_id').data('disabled-original') || false);
      $('#postal_code_select').prop('disabled', $('#postal_code_select').data('disabled-original') || false);
      
      // Toggle tombol
      $(this).hide();
      $('#use-customer-address').show();
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.js"></script>
@endsection