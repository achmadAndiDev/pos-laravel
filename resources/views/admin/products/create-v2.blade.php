@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('subtitle', 'Tambah Produk Baru')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-arrow-left"></i>
    Kembali
  </a>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
  .required:after {
    content: ' *';
    color: red;
  }
  
  .preview-image {
    max-height: 200px;
    max-width: 100%;
    margin-top: 10px;
  }
  
  .variation-card {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #f8fafc;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }
  
  .variation-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .remove-variation {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: #e53e3e;
    background-color: #fee2e2;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
  }
  
  .remove-variation:hover {
    background-color: #f56565;
    color: white;
  }
  
  .warehouse-stock-table {
    width: 100%;
    margin-bottom: 1rem;
  }
  
  .warehouse-stock-table th,
  .warehouse-stock-table td {
    padding: 0.5rem;
  }
  
  .warehouse-stock-table input {
    width: 100%;
  }
  
  .tab-content {
    padding-top: 1rem;
  }
  
  .nav-tabs .nav-link {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem 0.25rem 0 0;
  }
  
  .nav-tabs .nav-link.active {
    background-color: #f8fafc;
    border-bottom-color: #f8fafc;
  }
  
  .form-section {
    margin-bottom: 2rem;
  }
  
  .form-section-title {
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
  }
  
  #addVariationBtn {
    transition: all 0.3s ease;
  }
  
  #addVariationBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .alert-saving {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: none;
  }
  
  .variation-header {
    cursor: pointer;
  }
  
  .variation-body {
    display: none;
  }
  
  .variation-body.show {
    display: block;
  }
  
  .variation-toggle-icon {
    transition: transform 0.3s ease;
  }
  
  .variation-toggle-icon.rotated {
    transform: rotate(180deg);
  }
</style>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Form Tambah Produk</h3>
  </div>
  <div class="card-body">
    <form id="productForm">
      @csrf
      
      <div id="alertContainer"></div>
      
      <!-- Informasi Dasar Produk -->
      <div class="card mb-4">
        <div class="card-header">
          <h4 class="card-title">Informasi Dasar Produk</h4>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Nama Produk</label>
              <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
              <div class="invalid-feedback" id="name-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label required">SKU</label>
              <input type="text" class="form-control" name="sku" id="sku" value="{{ old('sku') }}" placeholder="Masukkan SKU produk" required>
              <small class="form-hint">SKU harus unik</small>
              <div class="invalid-feedback" id="sku-error"></div>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Kategori</label>
              <select class="form-select select2" name="category_id" id="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
                @endforeach
              </select>
              <div class="invalid-feedback" id="category_id-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Brand</label>
              <select class="form-select select2" name="brand_id" id="brand_id">
                <option value="">Pilih Brand (Opsional)</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                  {{ $brand->name }}
                </option>
                @endforeach
              </select>
              <div class="invalid-feedback" id="brand_id-error"></div>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label required">Deskripsi</label>
              <textarea class="form-control" name="description" id="descriptionEditor" rows="4" placeholder="Masukkan deskripsi produk" required>{{ old('description') }}</textarea>
              <div class="invalid-feedback" id="description-error"></div>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Diskon (%)</label>
              <input type="number" step="0.01" min="0" max="100" class="form-control" name="discount" id="discount" value="{{ old('discount', 0) }}" placeholder="0.00" required>
              <small class="form-hint">Masukkan persentase diskon (0-100)</small>
              <div class="invalid-feedback" id="discount-error"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Status Publikasi</label>
              <select class="form-select" name="publish_status" id="publish_status" required>
                <option value="Y" {{ old('publish_status') == 'Y' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="N" {{ old('publish_status') == 'N' ? 'selected' : '' }}>Draft</option>
              </select>
              <div class="invalid-feedback" id="publish_status-error"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Variasi Produk -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Variasi Produk</h4>
        </div>
        <div class="card-body">
          <div id="variationsContainer">
            <!-- Template variasi akan ditambahkan di sini -->
            <div class="variation-card position-relative mb-3" data-index="0">
              <span class="btn btn-danger remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
              
              <div class="variation-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#variation-0-body">
                <h5 class="mb-0">Variasi #<span class="variation-number">1</span></h5>
                <div class="d-flex align-items-center"  style="padding-right: 40px;">
                  <span class="variation-summary me-2">-</span>
                  <i class="ti ti-chevron-down variation-toggle-icon"></i>
                </div>
              </div>
              
              <div class="variation-body mt-3" id="variation-0-body">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">SKU</label>
                    <input type="text" class="form-control variation-sku" name="variations[0][sku]" placeholder="SKU Variasi" required>
                    <small class="form-hint">SKU harus unik</small>
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Gambar Variasi</label>
                    <input type="file" class="form-control variation-image-input" name="variations[0][image]" accept="image/*">
                    <div class="variation-image-preview mt-2"></div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Ukuran</label>
                    <input type="text" class="form-control variation-size" name="variations[0][size]" placeholder="Contoh: S, M, L, XL, dll">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Warna</label>
                    <input type="text" class="form-control variation-color" name="variations[0][color]" placeholder="Contoh: Merah, Biru, Hitam, dll">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Berat (gram)</label>
                    <input type="number" step="1" min="0" class="form-control variation-weight" name="variations[0][weight]" placeholder="0" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Dimensi (cm)</label>
                    <div class="row g-2">
                      <div class="col-md-4">
                        <div class="input-group">
                          <span class="input-group-text">P</span>
                          <input type="number" step="0.1" min="0" class="form-control" name="variations[0][length]" placeholder="Panjang" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <span class="input-group-text">L</span>
                          <input type="number" step="0.1" min="0" class="form-control" name="variations[0][width]" placeholder="Lebar" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <span class="input-group-text">T</span>
                          <input type="number" step="0.1" min="0" class="form-control" name="variations[0][height]" placeholder="Tinggi" required>
                        </div>
                      </div>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                
                <h6 class="mt-4 mb-3">Informasi Harga</h6>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Harga Beli</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control variation-price-buy" name="variations[0][price_buy]" placeholder="0" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Normal</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control variation-price" name="variations[0][price]" placeholder="0" required>
                    </div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Reseller</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price_reseller]" placeholder="0" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Super Dropshipper</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price1]" placeholder="0" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Dropshipper Standar</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price2]" placeholder="0" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Grosir</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price3]" placeholder="0" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                
                <h6 class="mt-4 mb-3">Informasi Stok</h6>
                <div class="row">
                  @foreach($warehouses as $warehouse)
                  <div class="col-md-6 mb-3">
                    <div class="card">
                      <div class="card-header py-2">
                        <h6 class="mb-0">{{ $warehouse->name }}</h6>
                      </div>
                      <div class="card-body">
                        <div class="mb-2">
                          <label class="form-label">Stok</label>
                          <input type="number" min="0" class="form-control" 
                            name="variations[0][warehouses][{{ $warehouse->id }}][stock]" 
                            value="0" placeholder="0">
                        </div>
                        <div>
                          <label class="form-label">Lokasi Rak</label>
                          <input type="text" class="form-control" 
                            name="variations[0][warehouses][{{ $warehouse->id }}][rack]" 
                            placeholder="Lokasi rak">
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          
          <div class="text-center mt-3">
            <button type="button" class="btn btn-primary" id="addVariationBtn">
              <i class="ti ti-plus me-1"></i> Tambah Variasi
            </button>
          </div>
        </div>
      </div>
      
      <div class="form-footer">
        <div class="d-flex justify-content-between">
          <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Kembali
          </a>
          <div>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <i class="ti ti-device-floppy me-1"></i>Simpan Produk
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Alert untuk notifikasi penyimpanan -->
<div class="alert alert-success alert-dismissible alert-saving" id="savingAlert" role="alert">
  <div class="d-flex">
    <div>
      <i class="ti ti-circle-check alert-icon"></i>
    </div>
    <div>
      <h4 class="alert-title">Menyimpan produk...</h4>
      <div class="text-muted">Mohon tunggu sebentar</div>
    </div>
  </div>
  <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
      theme: 'bootstrap-5',
      width: '100%',
      placeholder: $(this).data('placeholder') || 'Pilih opsi'
    });
    
    // Inisialisasi Summernote
    $('#descriptionEditor').summernote({
      height: 200,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
    
    // Variabel untuk menyimpan jumlah variasi
    let variationCount = 1;
    
    // Fungsi untuk menambahkan variasi baru
    $('#addVariationBtn').click(function() {
      const newIndex = variationCount;
      variationCount++;
      
      // Clone template variasi
      const newVariation = $('#variationsContainer .variation-card:first').clone();
      
      // Update atribut dan konten
      newVariation.attr('data-index', newIndex);
      newVariation.find('.variation-number').text(variationCount);
      newVariation.find('.variation-header').attr('data-bs-toggle', 'collapse');
      newVariation.find('.variation-header').attr('data-bs-target', `#variation-${newIndex}-body`);
      newVariation.find('.variation-body').attr('id', `variation-${newIndex}-body`);
      
      // Reset nilai input
      newVariation.find('input[type="text"], input[type="number"]').val('');
      newVariation.find('input[type="file"]').val('');
      newVariation.find('.variation-image-preview').empty();
      
      // Set default values untuk harga
      newVariation.find('input[name$="[price_buy]"]').val('0');
      newVariation.find('input[name$="[price]"]').val('0');
      newVariation.find('input[name$="[price_reseller]"]').val('0');
      newVariation.find('input[name$="[price1]"]').val('0');
      newVariation.find('input[name$="[price2]"]').val('0');
      newVariation.find('input[name$="[price3]"]').val('0');
      
      // Generate SKU unik untuk variasi baru
      const baseSku = $('#sku').val() || 'var';
      const uniqueSku = baseSku + '-' + newIndex + '-' + Math.floor(Math.random() * 10000);
      newVariation.find('.variation-sku').val(uniqueSku);
      
      // Update nama input
      newVariation.find('input, select').each(function() {
        const name = $(this).attr('name');
        if (name) {
          $(this).attr('name', name.replace(/\[\d+\]/, `[${newIndex}]`));
        }
      });
      
      // Tambahkan ke container
      $('#variationsContainer').append(newVariation);
      
      // Tampilkan body variasi baru
      $(`#variation-${newIndex}-body`).addClass('show');
      newVariation.find('.variation-toggle-icon').addClass('rotated');
      
      // Inisialisasi event handler untuk variasi baru
      initVariationEvents(newVariation);
      
      // Update ringkasan variasi
      updateVariationSummary(newVariation);
      
      // Scroll ke variasi baru
      $('html, body').animate({
        scrollTop: newVariation.offset().top - 100
      }, 500);
    });
    
    // Fungsi untuk menginisialisasi event handler pada variasi
    function initVariationEvents(variation) {
      // Event handler untuk tombol hapus variasi
      variation.find('.remove-variation').click(function(e) {
        e.stopPropagation();
        if (variationCount > 1) {
          $(this).closest('.variation-card').remove();
          updateVariationNumbers();
        } else {
          alert('Produk harus memiliki minimal 1 variasi.');
        }
      });
      
      // Event handler untuk preview gambar
      variation.find('.variation-image-input').change(function() {
        const file = this.files[0];
        const preview = $(this).siblings('.variation-image-preview');
        
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.html(`<img src="${e.target.result}" class="preview-image" />`);
          }
          reader.readAsDataURL(file);
        } else {
          preview.empty();
        }
      });
      
      // Event handler untuk toggle collapse
      variation.find('.variation-header').click(function() {
        const body = $(this).siblings('.variation-body');
        const icon = $(this).find('.variation-toggle-icon');
        
        body.toggleClass('show');
        icon.toggleClass('rotated');
        
        // Update ringkasan variasi
        updateVariationSummary(variation);
      });
      
      // Event handler untuk update ringkasan saat nilai berubah
      variation.find('.variation-size, .variation-color, .variation-price').on('input', function() {
        updateVariationSummary(variation);
      });
    }
    
    // Fungsi untuk memperbarui nomor variasi
    function updateVariationNumbers() {
      $('#variationsContainer .variation-card').each(function(index) {
        $(this).find('.variation-number').text(index + 1);
      });
      
      variationCount = $('#variationsContainer .variation-card').length;
    }
    
    // Fungsi untuk memperbarui ringkasan variasi
    function updateVariationSummary(variation) {
      const size = variation.find('.variation-size').val() || '';
      const color = variation.find('.variation-color').val() || '';
      const price = variation.find('.variation-price').val() || '0';
      
      let summary = '';
      
      if (size && color) {
        summary = `${size} / ${color} - Rp ${formatNumber(price)}`;
      } else if (size) {
        summary = `${size} - Rp ${formatNumber(price)}`;
      } else if (color) {
        summary = `${color} - Rp ${formatNumber(price)}`;
      } else {
        summary = `Rp ${formatNumber(price)}`;
      }
      
      variation.find('.variation-summary').text(summary);
    }
    
    // Format angka dengan pemisah ribuan
    function formatNumber(num) {
      return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Inisialisasi event handler untuk variasi pertama
    initVariationEvents($('#variationsContainer .variation-card:first'));
    
    // Generate SKU unik untuk variasi pertama jika kosong
    if (!$('.variation-sku').val()) {
      const baseSku = $('#sku').val() || 'var';
      const uniqueSku = baseSku + '-0-' + Math.floor(Math.random() * 10000);
      $('.variation-sku').val(uniqueSku);
    }
    
    // Event untuk auto-generate SKU variasi saat SKU produk berubah
    $('#sku').on('input', function() {
      const baseSku = $(this).val() || 'var';
      
      // Update SKU untuk semua variasi yang belum diisi manual
      $('#variationsContainer .variation-card').each(function(index) {
        const skuInput = $(this).find('.variation-sku');
        if (!skuInput.data('manually-entered')) {
          const uniqueSku = baseSku + '-' + index + '-' + Math.floor(Math.random() * 10000);
          skuInput.val(uniqueSku);
        }
      });
    });
    
    // Tandai SKU variasi yang diisi manual
    $('.variation-sku').on('input', function() {
      $(this).data('manually-entered', true);
    });
    
    // Pastikan status publikasi sesuai dengan pilihan user
    $('#publish_status').on('change', function() {
      console.log('Status publikasi diubah menjadi: ' + $(this).val());
    });
    
    // Event handler untuk submit form
    $('#productForm').submit(function(e) {
      e.preventDefault();
      
      // Tampilkan alert penyimpanan
      $('#savingAlert').fadeIn();
      
      // Reset error messages
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').text('');
      
      // Kumpulkan data form
      const formData = new FormData(this);
      
      // Pastikan CSRF token disertakan
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
      
      // Tambahkan konten editor
      formData.set('description', $('#descriptionEditor').summernote('code'));
      
      // Debug: Log form data
      console.log('Form data being submitted:');
      for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }
      
      // Kirim request AJAX
      $.ajax({
        url: "{{ route('admin.products.store-ajax') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        timeout: 60000, // Tambahkan timeout 60 detik
        beforeSend: function(xhr) {
          // Pastikan CSRF token disertakan
          console.log('CSRF Token: ' + $('meta[name="csrf-token"]').attr('content'));
          console.log('Sending create request to: ' + "{{ route('admin.products.store-ajax') }}");
          
          // Tambahkan header X-CSRF-TOKEN
          xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success: function(response) {
          // Sembunyikan alert penyimpanan
          $('#savingAlert').fadeOut();
          
          // Tampilkan SweetAlert sukses
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
          
          // Redirect ke halaman index setelah 2 detik
          setTimeout(function() {
            window.location.href = "{{ route('admin.products.index') }}";
          }, 2000);
        },
        error: function(xhr, status, error) {
          // Debug: Log error details
          console.error('Error status:', xhr.status);
          console.error('Error response:', xhr.responseText);
          console.error('Error details:', error);
          console.error('Status text:', xhr.statusText);
          
          // Coba parse response JSON jika ada
          try {
            const errorResponse = JSON.parse(xhr.responseText);
            console.error('Parsed error response:', errorResponse);
          } catch (e) {
            console.error('Could not parse error response as JSON');
          }
          
          // Sembunyikan alert penyimpanan
          $('#savingAlert').fadeOut();
          
          if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            
            // Tampilkan SweetAlert error
            Swal.fire({
              icon: 'error',
              title: 'Terjadi kesalahan!',
              text: 'Silakan periksa kembali form input Anda.',
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: true
            });
            
            // Tampilkan error pada masing-masing field
            for (const field in errors) {
              const errorMsg = errors[field][0];
              
              // Cek apakah field adalah bagian dari variasi
              if (field.includes('variations.')) {
                // Format: variations.0.field_name
                const parts = field.split('.');
                if (parts.length === 3) {
                  const index = parts[1];
                  const fieldName = parts[2];
                  
                  // Temukan elemen input
                  const inputElement = $(`[name="variations[${index}][${fieldName}]"]`);
                  if (inputElement.length) {
                    inputElement.addClass('is-invalid');
                    inputElement.siblings('.invalid-feedback').text(errorMsg);
                    
                    // Buka collapse jika tertutup
                    $(`#variation-${index}-body`).addClass('show');
                  }
                }
              } else {
                // Field biasa
                $(`#${field}`).addClass('is-invalid');
                $(`#${field}-error`).text(errorMsg);
              }
            }
            
            // Scroll ke error pertama
            const firstError = $('.is-invalid:first');
            if (firstError.length) {
              $('html, body').animate({
                scrollTop: firstError.offset().top - 100
              }, 500);
            }
          } else {
            // Server error
            Swal.fire({
              icon: 'error',
              title: 'Terjadi kesalahan!',
              text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: true
            });
            
            // Tampilkan detail error di console
            console.error('Server error details:', xhr.responseText);
          }
        }
      });
    });
  });
</script>
@endsection