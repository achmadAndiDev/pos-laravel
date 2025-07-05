@extends('admin.layouts.app')

@section('title', 'Edit Gudang')
@section('subtitle', 'Form Edit Gudang')

@section('css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 9999 !important;
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
  .form-label.required:after {
    content: " *";
    color: red;
  }
</style>
@endsection

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.products.warehouses.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Form Edit Gudang</h3>
  </div>
  <div class="card-body">
    <form id="warehouseForm" action="{{ route('admin.products.warehouses.update', $warehouse->id) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label required">Nama Gudang</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $warehouse->name) }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label required">Alamat</label>
          <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required>{{ old('address', $warehouse->address) }}</textarea>
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label required">Kota/Kecamatan</label>
          <input type="text" class="form-control @error('subdistrict_name') is-invalid @enderror" name="subdistrict_name" id="subdistrict_search" value="{{ old('subdistrict_name', $subdistrictName) }}" placeholder="Masukan Minimal 4 karakter untuk mencari" required>
          <input type="hidden" name="subdistrict_id" id="subdistrict_id" value="{{ old('subdistrict_id', $warehouse->subdistrict_id) }}">
          <input type="hidden" name="district_id" id="district_id" value="{{ old('district_id', $warehouse->district_id) }}">
          <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id', $warehouse->province_id) }}">
          @error('subdistrict_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label required">Kode Pos</label>
          <select class="form-select @error('postal_code') is-invalid @enderror" name="postal_code" id="postal_code_select" required>
            <option value="">Pilih Kode Pos</option>
            @if($warehouse->postal_code)
              <option value="{{ $warehouse->postal_code }}" selected>{{ $warehouse->postal_code }}</option>
            @endif
          </select>
          @error('postal_code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
            <option value="1" {{ old('is_active', $warehouse->is_active) ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('is_active', $warehouse->is_active) ? '' : 'selected' }}>Nonaktif</option>
          </select>
          @error('is_active')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="form-footer">
        <div class="row">
          <div class="col-md-6">
            <a href="{{ route('admin.products.warehouses.index') }}" class="btn btn-outline-secondary w-100">
              <i class="ti ti-x me-1"></i>
              Batal
            </a>
          </div>
          <div class="col-md-6">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti ti-device-floppy me-1"></i>
              Simpan
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
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
    // Initialize autocomplete for subdistrict search
    $('#subdistrict_search').autocomplete({
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
        $('#subdistrict_id').val(ui.item.id);
        $('#district_id').val(ui.item.district_id);
        $('#province_id').val(ui.item.province_id);
        
        // Load postal codes for the selected subdistrict
        loadPostalCodes(ui.item.id);
        
        return true;
      }
    });
    
    // Function to load postal codes for a subdistrict
    function loadPostalCodes(subdistrictId) {
      $.ajax({
        url: "{{ route('admin.subdistricts.postal-codes') }}",
        dataType: "json",
        data: {
          subdistrict_id: subdistrictId
        },
        success: function(data) {
          // Clear existing options except the selected one
          const selectedValue = $('#postal_code_select').val();
          $('#postal_code_select').empty();
          $('#postal_code_select').append('<option value="">Pilih Kode Pos</option>');
          
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
    
    // If subdistrict_id is already set, load postal codes
    const subdistrictId = $('#subdistrict_id').val();
    if (subdistrictId) {
      loadPostalCodes(subdistrictId);
    }
  });
</script>
@endsection