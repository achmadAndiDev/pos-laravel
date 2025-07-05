@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('subtitle', 'Tambah Produk Baru')

@section('css')
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


<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Form Tambah Produk</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
      @csrf
      
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
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
              @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label required">Kategori</label>
              <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
              <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="descriptionEditor" rows="4" placeholder="Masukkan deskripsi produk" required>{{ old('description') }}</textarea>
              @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Diskon (%)</label>
              <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ old('discount') }}" placeholder="0.00">
              <small class="form-hint">Masukkan persentase diskon (0-100)</small>
              @error('discount')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label required">Status Publikasi</label>
              <select class="form-select @error('publish_status') is-invalid @enderror" name="publish_status" required>
                <option value="Y" {{ old('publish_status') == 'Y' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="N" {{ old('publish_status') == 'N' ? 'selected' : '' }}>Draft</option>
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
            <!-- Template variasi akan ditambahkan di sini -->
            @if(old('variations'))
              @foreach(old('variations') as $index => $variation)
                <div class="variation-card position-relative mb-0" data-index="{{ $index }}">
                  <span class="btn btn-danger remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
                  <h5 class="mb-3">Variasi #<span class="variation-number">{{ $index + 1 }}</span></h5>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">SKU</label>
                      <input type="text" class="form-control" name="variations[{{ $index }}][sku]" value="{{ $variation['sku'] ?? '' }}" placeholder="SKU Variasi (opsional)">
                      <small class="form-hint">Biarkan kosong untuk generate otomatis</small>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Gambar Variasi</label>
                      <input type="file" class="form-control variation-image-input" name="variations[{{ $index }}][image]" accept="image/*">
                      <div class="variation-image-preview mt-2"></div>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Ukuran</label>
                      <input type="text" class="form-control" name="variations[{{ $index }}][size]" value="{{ $variation['size'] ?? '' }}" placeholder="Contoh: S, M, L, XL, dll">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Warna</label>
                      <input type="text" class="form-control" name="variations[{{ $index }}][color]" value="{{ $variation['color'] ?? '' }}" placeholder="Contoh: Merah, Biru, Hitam, dll">
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label required">Berat (gram)</label>
                      <input type="number" step="1" min="0" class="form-control" name="variations[{{ $index }}][weight]" value="{{ $variation['weight'] ?? '' }}" placeholder="0" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Dimensi (cm)</label>
                      <div class="input-group">
                        <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][length]" value="{{ $variation['length'] ?? '' }}" placeholder="Panjang">
                        <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][width]" value="{{ $variation['width'] ?? '' }}" placeholder="Lebar">
                        <input type="number" step="0.1" min="0" class="form-control" name="variations[{{ $index }}][height]" value="{{ $variation['height'] ?? '' }}" placeholder="Tinggi">
                      </div>
                    </div>
                  </div>
                  
                  <h6 class="mt-4 mb-3">Informasi Harga</h6>
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label required">Harga Beli</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price_buy]" value="{{ $variation['price_buy'] ?? '' }}" placeholder="0" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label required">Harga Jual Normal</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price]" value="{{ $variation['price'] ?? '' }}" placeholder="0" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Harga Jual Reseller</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price_reseller]" value="{{ $variation['price_reseller'] ?? '' }}" placeholder="0">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Harga Jual Super Dropshipper</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price1]" value="{{ $variation['price1'] ?? '' }}" placeholder="0">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Harga Jual Dropshipper Standar</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price2]" value="{{ $variation['price2'] ?? '' }}" placeholder="0">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Harga Jual Grosir</label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" min="0" class="form-control" name="variations[{{ $index }}][price3]" value="{{ $variation['price3'] ?? '' }}" placeholder="0">
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
                        <tr>
                          <td>{{ $warehouse->name }}</td>
                          <td>
                            <input type="number" min="0" class="form-control form-control-sm" 
                              name="variations[{{ $index }}][warehouses][{{ $warehouse->id }}][stock]" 
                              value="{{ $variation['warehouses'][$warehouse->id]['stock'] ?? 0 }}" placeholder="0">
                          </td>
                          <td>
                            <input type="text" class="form-control form-control-sm" 
                              name="variations[{{ $index }}][warehouses][{{ $warehouse->id }}][rack]" 
                              value="{{ $variation['warehouses'][$warehouse->id]['rack'] ?? '' }}" placeholder="Lokasi rak">
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @endforeach
            @else
              <!-- Default empty variation -->
              <div class="variation-card position-relative mb-0" data-index="0">
                <span class="btn btn-danger remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
                <h5 class="mb-3">Variasi #<span class="variation-number">1</span></h5>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="variations[0][sku]" placeholder="SKU Variasi (opsional)">
                    <small class="form-hint">Biarkan kosong untuk generate otomatis</small>
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
                    <input type="text" class="form-control" name="variations[0][size]" placeholder="Contoh: S, M, L, XL, dll">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Warna</label>
                    <input type="text" class="form-control" name="variations[0][color]" placeholder="Contoh: Merah, Biru, Hitam, dll">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Berat (gram)</label>
                    <input type="number" step="1" min="0" class="form-control" name="variations[0][weight]" placeholder="0" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Dimensi (cm)</label>
                    <div class="input-group">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[0][length]" placeholder="Panjang">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[0][width]" placeholder="Lebar">
                      <input type="number" step="0.1" min="0" class="form-control" name="variations[0][height]" placeholder="Tinggi">
                    </div>
                  </div>
                </div>
                
                <h6 class="mt-4 mb-3">Informasi Harga</h6>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label required">Harga Beli</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price_buy]" placeholder="0" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label required">Harga Jual Normal</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price]" placeholder="0" required>
                    </div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Reseller</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price_reseller]" placeholder="0">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Super Dropshipper</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price1]" placeholder="0">
                    </div>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Dropshipper Standar</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price2]" placeholder="0">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Harga Jual Grosir</label>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input type="number" min="0" class="form-control" name="variations[0][price3]" placeholder="0">
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
                      <tr>
                        <td>{{ $warehouse->name }}</td>
                        <td>
                          <input type="number" min="0" class="form-control form-control-sm" 
                            name="variations[0][warehouses][{{ $warehouse->id }}][stock]" 
                            value="0" placeholder="0">
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" 
                            name="variations[0][warehouses][{{ $warehouse->id }}][rack]" 
                            placeholder="Lokasi rak">
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif
          </div>


          <div id="noVariationsMessage" class="alert alert-info {{ old('variations') || !old('has_variation', true) ? 'd-none' : '' }}">
            <h4 class="alert-title">Belum ada variasi</h4>
            <p>Klik tombol "Tambah Variasi" untuk menambahkan variasi produk.</p>
          </div>
          
        </div>
      </div>
      
      <div class="form-footer">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Produk</button>
      </div>
    </form>
  </div>
</div>

<!-- Template untuk variasi baru -->
<template id="variationTemplate">
  <div class="variation-card position-relative mt-3" data-index="{index}">
    <span class="btn btn-danger remove-variation" title="Hapus Variasi"><i class="ti ti-trash"></i></span>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    $('#descriptionEditor').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']]
        ],
        height: 200
    });
    
    // Variabel untuk mengelola variasi
    let variationCount = document.querySelectorAll('.variation-card').length || 0;
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
              name="variations[${variationCount}][warehouses][{{ $warehouse->id }}][stock]" 
              value="0" placeholder="0">
          </td>
          <td>
            <input type="text" class="form-control form-control-sm" 
              name="variations[${variationCount}][warehouses][{{ $warehouse->id }}][rack]" 
              placeholder="Lokasi rak">
          </td>
        </tr>
      `;
      @endforeach
      
      // Buat variasi baru dari template
      let newVariation = variationTemplate
        .replace(/{index}/g, variationCount)
        .replace(/{number}/g, variationCount + 1)
        .replace('{warehouseRows}', warehouseRows);
      
      // Tambahkan ke container
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = newVariation;
      variationsContainer.appendChild(tempDiv.firstElementChild);
      
      // Setup event listener untuk preview gambar
      setupImagePreview(variationCount);
      
      // Tambahkan event listener untuk tombol hapus
      setupRemoveButton(variationCount);
      
      // Increment counter
      variationCount++;
    }
    
    // Fungsi untuk setup preview gambar
    function setupImagePreview(index) {
      const imageInput = document.querySelector(`input[name="variations[${index}][image]"]`);
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
      
      removeBtn.addEventListener('click', function() {
        const card = this.closest('.variation-card');
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
    }
    
    // Event listener untuk tombol tambah variasi
    addVariationBtn.addEventListener('click', addVariation);
    
    // Setup event listener untuk semua tombol hapus yang sudah ada
    document.querySelectorAll('.remove-variation').forEach(btn => {
      btn.addEventListener('click', function() {
        const card = this.closest('.variation-card');
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
@endsection