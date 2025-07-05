@extends('admin.layouts.app')

@section('title', 'Cetak Order')
@section('subtitle', 'Cetak Dokumen Order #' . $order->order_id)

@section('css')
<style>
  .print-preview {
    border: 1px solid #e6e7e9;
    padding: 20px;
    min-height: 500px;
    background-color: #f8f9fa;
    overflow: auto;
  }
  
  .print-options label {
    display: block;
    margin-bottom: 8px;
  }
  
  .print-type-option {
    display: block;
    padding: 10px;
    border: 1px solid #e6e7e9;
    border-radius: 4px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .print-type-option:hover {
    background-color: #f8f9fa;
  }
  
  .print-type-option.active {
    background-color: #e7f2ff;
    border-color: #206bc4;
  }
  
  .print-type-option input {
    margin-right: 8px;
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali
  </a> 
  {{-- <button type="button" id="save-print-settings" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-device-floppy"></i>
    Simpan Pengaturan
  </button> --}}
  <button type="button" id="print-document" class="btn btn-primary d-none d-sm-inline-block" onclick="printDocument()">
    <i class="ti ti-printer"></i>
    Cetak
  </button>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <!-- Kolom Kiri: Pengaturan Cetak -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pengaturan Cetak</h3>
      </div>
      <div class="card-body">
        <form id="print-settings-form">
          <div class="mb-3">
            <label class="form-label">Jenis Dokumen</label>
            
            <div class="print-type-option active">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="shipping_label" checked>
                <span class="form-check-label">Shipping Label</span>
              </label>
            </div>
{{--             
            <div class="print-type-option">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="shipping_label_v2">
                <span class="form-check-label">Shipping Label (v2)</span>
              </label>
            </div>
            
            <div class="print-type-option">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="shipping_label_a6">
                <span class="form-check-label">Shipping Label A6</span>
              </label>
            </div>
            
            <div class="print-type-option">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="invoice">
                <span class="form-check-label">Invoice</span>
              </label>
            </div>
            
            <div class="print-type-option">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="invoice_thermal_80mm">
                <span class="form-check-label">Invoice Thermal (80mm)</span>
              </label>
            </div>
            
            <div class="print-type-option">
              <label class="form-check">
                <input type="radio" class="form-check-input" name="print_type" value="invoice_thermal_56mm">
                <span class="form-check-label">Invoice Thermal (56mm)</span>
              </label>
            </div> --}}
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Kolom Kanan: Pengaturan Atribut -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pengaturan</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Informasi Umum</label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_logo]" checked>
                <span class="form-check-label">Tampilkan Logo</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_order_id]" checked>
                <span class="form-check-label">Nomor Order</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_order_date]" checked>
                <span class="form-check-label">Tanggal Order</span>
              </label>
            
            </div>
            
            <div class="mb-3">
              <label class="form-label">Informasi Pengiriman</label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_shipping_method]" checked>
                <span class="form-check-label">Metode Pengiriman</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_tracking_number]" checked>
                <span class="form-check-label">Nomor Resi</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_shipping_address]" checked>
                <span class="form-check-label">Alamat Pengiriman</span>
              </label>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Informasi Customer</label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_customer_name]" checked>
                <span class="form-check-label">Nama Penerima</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_customer_phone]" checked>
                <span class="form-check-label">Nomor Telepon</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_customer_email]">
                <span class="form-check-label">Email</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_shipping_address]" checked>
                <span class="form-check-label">Alamat Pengiriman</span>
              </label>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Informasi Produk</label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_product_list]" checked>
                <span class="form-check-label">Daftar Produk</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_product_price]">
                <span class="form-check-label">Harga Produk</span>
              </label>
              
              {{-- <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_product_weight]">
                <span class="form-check-label">Berat Produk</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_product_sku]">
                <span class="form-check-label">SKU Produk</span>
              </label> --}}
            </div>
            
            <div class="mb-3">
              <label class="form-label">Informasi Tambahan</label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_barcode_po]" checked>
                <span class="form-check-label">Barcode PO</span>
              </label>

              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_barcode_resi]" checked>
                <span class="form-check-label">Barcode Resi</span>
              </label>
              
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="options[show_fragile_section]" checked>
                <span class="form-check-label">Bagian Fragile & Petunjuk</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards">
  <div class="col-md-12">

        
        <div class="mt-4">
          <label class="form-label">Preview</label>
          <div class="print-preview" id="print-preview">
            <div class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-2">Memuat preview...</p>
            </div>
          </div>
        </div>
  </div>
</div>

<!-- Print Frame (Hidden) -->
<iframe id="print-frame" style="display: none;"></iframe>
@endsection

@section('scripts')
<script>
  // Define global variables for the order-print.js script
  const printPreviewUrl = '{{ route("admin.orders.print.generate", $order->id) }}';
  const saveSettingsUrl = '{{ route("admin.orders.print.save-settings") }}';
  const csrfToken = '{{ csrf_token() }}';
</script>
<script src="{{ asset('js/admin/order-print.js') }}"></script>
@endsection