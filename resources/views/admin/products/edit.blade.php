@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('subtitle', 'Edit Produk')

@push('styles')
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
  
  .current-image {
    max-height: 200px;
    max-width: 100%;
    margin-top: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    padding: 5px;
  }
  
  .variation-card {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 2rem;
    background-color: #f8fafc;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
</style>
@endpush

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Produk: {{ $product->name }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
      @csrf
      @method('PUT')
      
      @if ($errors->any())
      <div class="alert alert-danger">
        <h4 class="alert-title">Terjadi kesalahan!</h4>
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      
      <!-- Informasi Dasar Produk -->
      <div class="card mb-4">
        <div class="card-header">
          <h4 class="card-title">Informasi Dasar Produk</h4>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label required">Nama Produk</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk" required>
              @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label required">Kategori</label>
              <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
                @endforeach
              </select>
              @error('category_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label required">Deskripsi</label>
              <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Masukkan deskripsi produk" required>{{ old('description', $product->description) }}</textarea>
              @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Diskon (%)</label>
              <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ old('discount', $product->discount) }}" placeholder="0.00">
              <small class="form-hint">Masukkan persentase diskon (0-100)</small>
              @error('discount')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label required">Status Publikasi</label>
              <select class="form-select @error('publish_status') is-invalid @enderror" name="publish_status" required>
                <option value="Y" {{ old('publish_status', $product->publish_status) == 'Y' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="N" {{ old('publish_status', $product->publish_status) == 'N' ? 'selected' : '' }}>Draft</option>
              </select>
              @error('publish_status')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>
      
      <!-- Variasi Produk -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Variasi Produk</h4>
          <button type="button" class="btn btn-primary btn-sm" id="addVariationBtn">
            <i class="ti ti-plus me-1"></i> Tambah Variasi
          </button>
        </div>
        <div class="card-body">
          <div id="variationsContainer">
            <!-- Variasi yang sudah ada -->
            @foreach($product->variations as $index => $variation)
              <div class="variation-card position-relative mb-0" data-index="{{ $index }}" data-id="{{ $variation->id }}">
                <span class="remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
                <h5 class="mb-3">Variasi #<span class="variation-number">{{ $index + 1 }}</span></h5>
                
                <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="variations[{{ $index }}][sku]" value="{{ old('variations.'.$index.'.sku', $variation->sku) }}" placeholder="SKU Variasi">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Gambar Variasi</label>
                    @if($variation->image)
                    <div class="mb-2">
                      <img src="{{ $variation->image }}" alt="Variasi {{ $index + 1 }}" class="current-image">
                      <p class="text-muted small mt-1">Gambar saat ini</p>
                    </div>
                    @endif
                    <input type="file" class="form-control variation-image-input" name="variations[{{ $index }}][image]" accept="image/*">
                    <div class="variation-image-preview mt-2"></div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Ukuran</label>
                    <input type="text" class="form-control" name="variations[{{ $index }}][size]" value="{{ old('variations.'.$index.'.size', $variation->size) }}" placeholder="Contoh: S, M, L, XL, dll">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Warna</label>
                    <input type="text" class="form-control" name="variations[{{ $index }}][color]" value="{{ old('variations.'.$index.'.color', $variation->color) }}" placeholder="Contoh: Merah, Biru, Hitam, dll">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Berat (gram)</label>
                    <input type="number" step="1" min="0" class="form-control" name="variations[{{ $index }}][weight]" value="{{ old('variations.'.$index.'.weight', $variation->weight) }}" placeholder="0" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Dimensi (cm)</label>
                    <div class="input-group">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][length]" value="{{ old('variations.'.$index.'.length', $variation->length) }}" placeholder="Panjang">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][width]" value="{{ old('variations.'.$index.'.width', $variation->width) }}" placeholder="Lebar">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][height]" value="{{ old('variations.'.$index.'.height', $variation->height) }}" placeholder="Tinggi">
                    </div>
                  </div>
                </div>
                
                <h6 class="mt-4 mb-3">Informasi Harga</h6>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Harga Beli</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price_buy]" value="{{ old('variations.'.$index.'.price_buy', $variation->price_buy) }}" placeholder="0" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Normal</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price]" value="{{ old('variations.'.$index.'.price', $variation->price) }}" placeholder="0" required>
                    </div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Reseller</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price_reseller]" value="{{ old('variations.'.$index.'.price_reseller', $variation->price_reseller) }}" placeholder="0">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Super Dropshipper</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price1]" value="{{ old('variations.'.$index.'.price1', $variation->price1) }}" placeholder="0">
                    </div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Dropshipper Standar</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price2]" value="{{ old('variations.'.$index.'.price2', $variation->price2) }}" placeholder="0">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Grosir</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price3]" value="{{ old('variations.'.$index.'.price3', $variation->price3) }}" placeholder="0">
                    </div>
                  </div>
                </div>
                
                <h6 class="mt-4 mb-3">Informasi Stok</h6>
                <div class="table-responsive">
                  <table class="warehouse-stock-table">
                    <thead>
                      <tr>
                        <th>Gudang</th>
                        <th>Stok</th>
                        <th>Rak</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($warehouses as $warehouse)
                        @php
                          $warehouseStock = $variation->warehouseStocks->where('warehouse_id', $warehouse->id)->first();
                        @endphp
                        <tr>
                          <td>{{ $warehouse->name }}</td>
                          <td>
                            <input type="number" min="0" class="form-control form-control-sm" 
                              name="variations[{{ $index }}][warehouses][{{ $warehouse->id }}][stock]" 
                              value="{{ old('variations.'.$index.'.warehouses.'.$warehouse->id.'.stock', $warehouseStock ? $warehouseStock->stock : 0) }}" placeholder="0">
                          </td>
                          <td>
                            <input type="text" class="form-control form-control-sm" 
                              name="variations[{{ $index }}][warehouses][{{ $warehouse->id }}][rack]" 
                              value="{{ old('variations.'.$index.'.warehouses.'.$warehouse->id.'.rack', $warehouseStock ? $warehouseStock->rack : '') }}" placeholder="Lokasi rak">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          </div>
          
          <div id="noVariationsMessage" class="alert alert-info {{ $product->variations->count() > 0 ? 'd-none' : '' }}">
            <h4 class="alert-title">Belum ada variasi</h4>
            <p>Klik tombol "Tambah Variasi" untuk menambahkan variasi produk.</p>
          </div>
        </div>
      </div>
      
      <div class="form-footer">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<!-- Template untuk variasi baru -->
<template id="variationTemplate">
  <div class="variation-card position-relative mb-0" data-index="{index}">
    <span class="remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
    <h5 class="mb-3">Variasi #<span class="variation-number">{number}</span></h5>
    
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">SKU</label>
        <input type="text" class="form-control" name="variations[{index}][sku]" placeholder="SKU Variasi (opsional)">
        <small class="form-hint">Biarkan kosong untuk generate otomatis</small>
      </div>
      <div class="col-md-6">
        <label class="form-label">Gambar Variasi</label>
        <input type="file" class="form-control variation-image-input" name="variations[{index}][image]" accept="image/*">
        <div class="variation-image-preview mt-2"></div>
      </div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Ukuran</label>
        <input type="text" class="form-control" name="variations[{index}][size]" placeholder="Contoh: S, M, L, XL, dll">
      </div>
      <div class="col-md-6">
        <label class="form-label">Warna</label>
        <input type="text" class="form-control" name="variations[{index}][color]" placeholder="Contoh: Merah, Biru, Hitam, dll">
      </div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label required">Berat (gram)</label>
        <input type="number" step="1" min="0" class="form-control" name="variations[{index}][weight]" placeholder="0" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Dimensi (cm)</label>
        <div class="input-group">
          <input type="number" step="0.1" min="0" class="form-control" name="variations[{index}][length]" placeholder="Panjang">
          <input type="number" step="0.1" min="0" class="form-control" name="variations[{index}][width]" placeholder="Lebar">
          <input type="number" step="0.1" min="0" class="form-control" name="variations[{index}][height]" placeholder="Tinggi">
        </div>
      </div>
    </div>
    
    <h6 class="mt-4 mb-3">Informasi Harga</h6>
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label required">Harga Beli</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price_buy]" placeholder="0" required>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Harga Jual Normal</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price]" placeholder="0" required>
        </div>
      </div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Harga Jual Reseller</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price_reseller]" placeholder="0">
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Harga Jual Super Dropshipper</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price1]" placeholder="0">
        </div>
      </div>
    </div>
    
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Harga Jual Dropshipper Standar</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price2]" placeholder="0">
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Harga Jual Grosir</label>
        <div class="input-group">
          <span class="input-group-text">Rp</span>
          <input type="number" min="0" class="form-control" name="variations[{index}][price3]" placeholder="0">
        </div>
      </div>
    </div>
    
    <h6 class="mt-4 mb-3">Informasi Stok</h6>
    <div class="table-responsive">
      <table class="warehouse-stock-table">
        <thead>
          <tr>
            <th>Gudang</th>
            <th>Stok</th>
            <th>Rak</th>
          </tr>
        </thead>
        <tbody>
          {warehouseRows}
        </tbody>
      </table>
    </div>
  </div>
</template>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variabel untuk mengelola variasi
    let variationCount = {{ $product->variations->count() }};
    let nextVariationIndex = variationCount;
    const variationsContainer = document.getElementById('variationsContainer');
    const noVariationsMessage = document.getElementById('noVariationsMessage');
    const addVariationBtn = document.getElementById('addVariationBtn');
    const variationTemplate = document.getElementById('variationTemplate').innerHTML;
    
    // Fungsi untuk menambahkan variasi baru
    function addVariation() {
      // Sembunyikan pesan "belum ada variasi"
      noVariationsMessage.classList.add('d-none');
      
      // Buat template warehouse rows
      let warehouseRows = '';
      @foreach($warehouses as $warehouse)
      warehouseRows += `
        <tr>
          <td>{{ $warehouse->name }}</td>
          <td>
            <input type="number" min="0" class="form-control form-control-sm" 
              name="variations[${nextVariationIndex}][warehouses][{{ $warehouse->id }}][stock]" 
              value="0" placeholder="0">
          </td>
          <td>
            <input type="text" class="form-control form-control-sm" 
              name="variations[${nextVariationIndex}][warehouses][{{ $warehouse->id }}][rack]" 
              placeholder="Lokasi rak">
          </td>
        </tr>
      `;
      @endforeach
      
      // Buat variasi baru dari template
      let newVariation = variationTemplate
        .replace(/{index}/g, nextVariationIndex)
        .replace(/{number}/g, variationCount + 1)
        .replace(/{warehouseRows}/g, warehouseRows);
      
      // Tambahkan ke container
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = newVariation;
      variationsContainer.appendChild(tempDiv.firstElementChild);
      
      // Setup event listener untuk preview gambar
      setupImagePreview(nextVariationIndex);
      
      // Tambahkan event listener untuk tombol hapus
      setupRemoveButton(nextVariationIndex);
      
      // Increment counters
      variationCount++;
      nextVariationIndex++;
    }
    
    // Fungsi untuk setup preview gambar
    function setupImagePreview(index) {
      const imageInput = document.querySelector(`input[name="variations[${index}][image]"]`);
      if (!imageInput) return;
      
      const imagePreview = imageInput.parentElement.querySelector('.variation-image-preview');
      
      imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          
          reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            imagePreview.appendChild(img);
          }
          
          reader.readAsDataURL(this.files[0]);
        }
      });
    }
    
    // Fungsi untuk setup tombol hapus
    function setupRemoveButton(index) {
      const removeBtn = document.querySelector(`.variation-card[data-index="${index}"] .remove-variation`);
      if (!removeBtn) return;
      
      removeBtn.addEventListener('click', function() {
        const card = this.closest('.variation-card');
        const variationId = card.dataset.id;
        
        // Jika variasi sudah ada di database, tambahkan input hidden untuk menandai penghapusan
        if (variationId) {
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'deleted_variations[]';
          hiddenInput.value = variationId;
          document.getElementById('productForm').appendChild(hiddenInput);
        }
        
        card.remove();
        
        // Perbarui nomor variasi
        updateVariationNumbers();
        
        // Tampilkan pesan jika tidak ada variasi
        if (document.querySelectorAll('.variation-card').length === 0) {
          noVariationsMessage.classList.remove('d-none');
        }
      });
    }
    
    // Fungsi untuk memperbarui nomor variasi
    function updateVariationNumbers() {
      const variations = document.querySelectorAll('.variation-card');
      variations.forEach((variation, index) => {
        variation.querySelector('.variation-number').textContent = index + 1;
      });
      
      variationCount = variations.length;
    }
    
    // Event listener untuk tombol tambah variasi
    addVariationBtn.addEventListener('click', addVariation);
    
    // Setup event listener untuk semua tombol hapus yang sudah ada
    document.querySelectorAll('.remove-variation').forEach(btn => {
      btn.addEventListener('click', function() {
        const card = this.closest('.variation-card');
        const variationId = card.dataset.id;
        
        // Jika variasi sudah ada di database, tambahkan input hidden untuk menandai penghapusan
        if (variationId) {
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'deleted_variations[]';
          hiddenInput.value = variationId;
          document.getElementById('productForm').appendChild(hiddenInput);
        }
        
        card.remove();
        
        // Perbarui nomor variasi
        updateVariationNumbers();
        
        // Tampilkan pesan jika tidak ada variasi
        if (document.querySelectorAll('.variation-card').length === 0) {
          noVariationsMessage.classList.remove('d-none');
        }
      });
    });
    
    // Setup event listener untuk semua input gambar yang sudah ada
    document.querySelectorAll('.variation-image-input').forEach(input => {
      const index = input.closest('.variation-card').dataset.index;
      setupImagePreview(index);
    });
    
    // Tambahkan variasi jika belum ada
    if (variationCount === 0) {
      addVariation();
    }
    
    // Form validation
    const productForm = document.getElementById('productForm');
    
    productForm.addEventListener('submit', function(event) {
      // Validasi dasar
      const name = document.querySelector('input[name="name"]').value;
      const category = document.querySelector('select[name="category_id"]').value;
      const description = document.querySelector('textarea[name="description"]').value;
      
      if (!name) {
        alert('Nama produk harus diisi');
        event.preventDefault();
        return;
      }
      
      if (!category) {
        alert('Kategori harus dipilih');
        event.preventDefault();
        return;
      }
      
      if (!description) {
        alert('Deskripsi produk harus diisi');
        event.preventDefault();
        return;
      }
      
      // Validasi variasi
      const variations = document.querySelectorAll('.variation-card');
      if (variations.length === 0) {
        alert('Produk harus memiliki minimal 1 variasi');
        event.preventDefault();
        return;
      }
      
      // Validasi setiap variasi
      let isValid = true;
      variations.forEach((variation, index) => {
        const weight = variation.querySelector(`input[name="variations[${variation.dataset.index}][weight]"]`).value;
        const priceBuy = variation.querySelector(`input[name="variations[${variation.dataset.index}][price_buy]"]`).value;
        const price = variation.querySelector(`input[name="variations[${variation.dataset.index}][price]"]`).value;
        
        if (!weight || weight <= 0) {
          alert(`Berat pada Variasi #${index + 1} harus diisi dan lebih dari 0`);
          isValid = false;
          return;
        }
        
        if (!priceBuy || priceBuy <= 0) {
          alert(`Harga beli pada Variasi #${index + 1} harus diisi dan lebih dari 0`);
          isValid = false;
          return;
        }
        
        if (!price || price <= 0) {
          alert(`Harga jual normal pada Variasi #${index + 1} harus diisi dan lebih dari 0`);
          isValid = false;
          return;
        }
      });
      
      if (!isValid) {
        event.preventDefault();
      }
    });
  });
</script>
@endpush