@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('subtitle', 'Edit Data Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('kasir.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Outlet</label>
                                <select name="outlet_id" class="form-select @error('outlet_id') is-invalid @enderror">
                                    <option value="">Pilih Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ old('outlet_id', $product->outlet_id) == $outlet->id ? 'selected' : '' }}>
                                            {{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('outlet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kategori Produk</label>
                                <select name="product_category_id" class="form-select @error('product_category_id') is-invalid @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kode Produk</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code', $product->code) }}" placeholder="Masukkan kode produk">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                                       value="{{ old('barcode', $product->barcode) }}" placeholder="Masukkan barcode (opsional)">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Produk</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Masukkan deskripsi produk (opsional)">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" 
                                           value="{{ old('purchase_price', $product->purchase_price) }}" placeholder="0" min="0" step="0.01">
                                </div>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" 
                                           value="{{ old('selling_price', $product->selling_price) }}" placeholder="0" min="0" step="0.01">
                                </div>
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Stok</label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                       value="{{ old('stock', $product->stock) }}" placeholder="0" min="0">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Stok Minimum</label>
                                <input type="number" name="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" 
                                       value="{{ old('minimum_stock', $product->minimum_stock) }}" placeholder="0" min="0">
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Satuan</label>
                                <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" 
                                       value="{{ old('unit', $product->unit) }}" placeholder="pcs">
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Berat (gram)</label>
                                <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" 
                                       value="{{ old('weight', $product->weight) }}" placeholder="0" min="0" step="0.01">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteImage()">
                                            <i class="ti ti-trash"></i> Hapus Gambar
                                        </button>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <div class="form-hint">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Dapat Dijual</label>
                                <select name="is_sellable" class="form-select @error('is_sellable') is-invalid @enderror">
                                    <option value="1" {{ old('is_sellable', $product->is_sellable ? '1' : '0') == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ old('is_sellable', $product->is_sellable ? '1' : '0') == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('is_sellable')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3" placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes', $product->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <a href="{{ route('kasir.products.index') }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-device-floppy"></i>
                            Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete image function
    function deleteImage() {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            fetch(`{{ route('kasir.products.delete-image', $product) }}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus gambar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus gambar');
            });
        }
    }

    // Auto calculate profit margin
    const purchasePrice = document.querySelector('input[name="purchase_price"]');
    const sellingPrice = document.querySelector('input[name="selling_price"]');
    
    function calculateMargin() {
        const purchase = parseFloat(purchasePrice.value) || 0;
        const selling = parseFloat(sellingPrice.value) || 0;
        
        if (purchase > 0) {
            const margin = ((selling - purchase) / purchase) * 100;
            // You can display margin somewhere if needed
        }
    }
    
    purchasePrice.addEventListener('input', calculateMargin);
    sellingPrice.addEventListener('input', calculateMargin);
</script>
@endpush