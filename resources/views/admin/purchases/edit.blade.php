@extends('admin.layouts.app')

@section('title', 'Edit Pembelian')
@section('subtitle', 'Edit Pembelian ' . $purchase->code)

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.purchases.show', $purchase) }}" class="btn btn-outline-info">
        <i class="ti ti-eye"></i>
        Lihat Detail
    </a>
    <a href="{{ route('kasir.purchases.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('kasir.purchases.update', $purchase) }}" method="POST" id="purchaseForm">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pembelian</h3>
                    <div class="card-actions">
                        <span class="badge {{ $purchase->status_badge_class }}">{{ $purchase->status_text }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Pembelian</label>
                                <input type="text" class="form-control" value="{{ $purchase->code }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tanggal Pembelian</label>
                                <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Outlet</label>
                                <select name="outlet_id" class="form-select @error('outlet_id') is-invalid @enderror" id="outletSelect" required>
                                    <option value="">Pilih Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ old('outlet_id', $purchase->outlet_id) == $outlet->id ? 'selected' : '' }}>
                                            {{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('outlet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Nama Supplier</label>
                                <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" 
                                       value="{{ old('supplier_name', $purchase->supplier_name) }}" placeholder="Masukkan nama supplier" required>
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telepon Supplier</label>
                                <input type="text" name="supplier_phone" class="form-control @error('supplier_phone') is-invalid @enderror" 
                                       value="{{ old('supplier_phone', $purchase->supplier_phone) }}" placeholder="Masukkan telepon supplier">
                                @error('supplier_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Supplier</label>
                        <textarea name="supplier_address" class="form-control @error('supplier_address') is-invalid @enderror" 
                                  rows="2" placeholder="Masukkan alamat supplier">{{ old('supplier_address', $purchase->supplier_address) }}</textarea>
                        @error('supplier_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nomor Invoice</label>
                                <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" 
                                       value="{{ old('invoice_number', $purchase->invoice_number) }}" placeholder="Masukkan nomor invoice">
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="2" placeholder="Masukkan catatan (opsional)">{{ old('notes', $purchase->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Purchase Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Item Pembelian</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">
                            <i class="ti ti-plus"></i>
                            Tambah Item
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="30%">Produk</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="20%">Harga Satuan</th>
                                    <th width="20%">Total</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @forelse($purchase->purchaseItems as $index => $item)
                                <tr data-index="{{ $index }}">
                                    <td>
                                        <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->purchase_price }}" 
                                                        data-unit="{{ $product->unit }}"
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->code }} - {{ $product->name }} ({{ $product->productCategory->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input" 
                                               min="1" step="1" value="{{ $item->quantity }}" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="items[{{ $index }}][unit_price]" class="form-control price-input" 
                                                   min="0" step="0.01" value="{{ $item->unit_price }}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="total-price">{{ $item->formatted_total_price }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Klik "Tambah Item" untuk menambahkan produk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pajak</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="tax_amount" class="form-control @error('tax_amount') is-invalid @enderror" 
                                           value="{{ old('tax_amount', $purchase->tax_amount) }}" min="0" step="0.01" id="taxAmount">
                                </div>
                                @error('tax_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Diskon</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" 
                                           value="{{ old('discount_amount', $purchase->discount_amount) }}" min="0" step="0.01" id="discountAmount">
                                </div>
                                @error('discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotalDisplay">{{ $purchase->formatted_subtotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak:</span>
                                <span id="taxDisplay">Rp {{ number_format($purchase->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Diskon:</span>
                                <span id="discountDisplay">Rp {{ number_format($purchase->discount_amount, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong id="totalDisplay">{{ $purchase->formatted_total_amount }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                            <i class="ti ti-device-floppy"></i>
                            Update Pembelian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = {{ $purchase->purchaseItems->count() }};
    let products = @json($products->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'category' => $product->productCategory->name,
            'current_stock' => $product->stock,
            'unit' => $product->unit,
            'purchase_price' => $product->purchase_price,
        ];
    }));

    // Load products when outlet changes
    $('#outletSelect').change(function() {
        const outletId = $(this).val();
        
        if (outletId) {
            loadProducts(outletId);
        } else {
            products = [];
        }
    });

    // Load products via AJAX
    function loadProducts(outletId) {
        $.ajax({
            url: '{{ route("kasir.purchases.products-by-outlet") }}',
            method: 'GET',
            data: { outlet_id: outletId },
            success: function(response) {
                products = response;
                updateProductSelects();
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat data produk', 'error');
            }
        });
    }

    // Update all product selects
    function updateProductSelects() {
        $('.product-select').each(function() {
            const currentValue = $(this).val();
            let productOptions = '<option value="">Pilih Produk</option>';
            
            products.forEach(function(product) {
                productOptions += `<option value="${product.id}" data-price="${product.purchase_price}" data-unit="${product.unit}">
                    ${product.code} - ${product.name} (${product.category})
                </option>`;
            });
            
            $(this).html(productOptions);
            if (currentValue) {
                $(this).val(currentValue);
            }
        });
    }

    // Add item button
    $('#addItemBtn').click(function() {
        if (products.length === 0) {
            Swal.fire('Peringatan', 'Tidak ada produk tersedia untuk outlet ini', 'warning');
            return;
        }
        
        addItemRow();
    });

    // Add item row
    function addItemRow() {
        $('#emptyRow').hide();
        
        let productOptions = '<option value="">Pilih Produk</option>';
        products.forEach(function(product) {
            productOptions += `<option value="${product.id}" data-price="${product.purchase_price}" data-unit="${product.unit}">
                ${product.code} - ${product.name} (${product.category})
            </option>`;
        });

        const row = `
            <tr data-index="${itemIndex}">
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" 
                           min="1" step="1" value="1" required>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" 
                               min="0" step="0.01" required>
                    </div>
                </td>
                <td>
                    <span class="total-price">Rp 0</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#itemsTableBody').append(row);
        itemIndex++;
        updateSubmitButton();
    }

    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        
        if ($('#itemsTableBody tr:not(#emptyRow)').length === 0) {
            $('#emptyRow').show();
        }
        
        calculateTotals();
        updateSubmitButton();
    });

    // Product select change
    $(document).on('change', '.product-select', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;
        const row = $(this).closest('tr');
        
        row.find('.price-input').val(price);
        calculateRowTotal(row);
    });

    // Quantity or price change
    $(document).on('input', '.quantity-input, .price-input', function() {
        const row = $(this).closest('tr');
        calculateRowTotal(row);
    });

    // Tax or discount change
    $('#taxAmount, #discountAmount').on('input', function() {
        calculateTotals();
    });

    // Calculate row total
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const total = quantity * price;
        
        row.find('.total-price').text(formatCurrency(total));
        calculateTotals();
    }

    // Calculate all totals
    function calculateTotals() {
        let subtotal = 0;
        
        $('#itemsTableBody tr:not(#emptyRow)').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            subtotal += quantity * price;
        });

        const tax = parseFloat($('#taxAmount').val()) || 0;
        const discount = parseFloat($('#discountAmount').val()) || 0;
        const total = subtotal + tax - discount;

        $('#subtotalDisplay').text(formatCurrency(subtotal));
        $('#taxDisplay').text(formatCurrency(tax));
        $('#discountDisplay').text(formatCurrency(discount));
        $('#totalDisplay').text(formatCurrency(total));
    }

    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Update submit button state
    function updateSubmitButton() {
        const hasItems = $('#itemsTableBody tr:not(#emptyRow)').length > 0;
        $('#submitBtn').prop('disabled', !hasItems);
    }

    // Form validation
    $('#purchaseForm').submit(function(e) {
        const hasItems = $('#itemsTableBody tr:not(#emptyRow)').length > 0;
        
        if (!hasItems) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Minimal harus ada satu item pembelian', 'warning');
            return false;
        }

        // Validate each item
        let isValid = true;
        $('#itemsTableBody tr:not(#emptyRow)').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.quantity-input').val();
            const price = $(this).find('.price-input').val();
            
            if (!productId || !quantity || !price) {
                isValid = false;
                return false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Semua item harus diisi dengan lengkap', 'warning');
            return false;
        }
    });

    // Initial calculations
    calculateTotals();
    updateSubmitButton();
});
</script>
@endsection