@extends('admin.layouts.app')

@section('title', 'Tambah Penjualan')
@section('subtitle', 'Buat Penjualan Baru - POS')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.sales.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('kasir.sales.store') }}" method="POST" id="saleForm">
    @csrf
    <div class="row">
        <!-- Left Panel - Product Selection & Items -->
        <div class="col-md-8">
            <!-- Sale Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Penjualan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Outlet</label>
                                <select name="outlet_id" class="form-select @error('outlet_id') is-invalid @enderror" id="outletSelect" required>
                                    <option value="">Pilih Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                            {{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('outlet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                                    <option value="">Walk-in Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Tanggal Penjualan</label>
                                <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" 
                                       value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                @error('sale_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Pilih Produk</h3>
                    <div class="card-actions">
                        <input type="text" id="productSearch" class="form-control" placeholder="Cari produk..." disabled>
                    </div>
                </div>
                <div class="card-body">
                    <div id="productGrid" class="row">
                        <div class="col-12 text-center text-muted py-4">
                            Pilih outlet terlebih dahulu untuk melihat produk
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Item Penjualan</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clearAllBtn" disabled>
                            <i class="ti ti-trash"></i>
                            Hapus Semua
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <div id="emptyState" class="text-center text-muted py-5">
                            <i class="ti ti-shopping-cart-off fs-1 mb-3 text-muted"></i>
                            <h4 class="text-muted">Belum ada item dipilih</h4>
                            <p class="text-muted">Pilih produk dari daftar di atas untuk menambahkan ke keranjang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Payment & Summary -->
        <div class="col-md-4">
            <!-- Summary -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Pajak</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="tax_amount" class="form-control @error('tax_amount') is-invalid @enderror" 
                                       value="{{ old('tax_amount', 0) }}" min="0" step="0.01" id="taxAmount">
                            </div>
                            @error('tax_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label">Diskon</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" 
                                       value="{{ old('discount_amount', 0) }}" min="0" step="0.01" id="discountAmount">
                            </div>
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-6"><strong>Subtotal:</strong></div>
                        <div class="col-6 text-end" id="subtotalDisplay">Rp 0</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Pajak:</div>
                        <div class="col-6 text-end" id="taxDisplay">Rp 0</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Diskon:</div>
                        <div class="col-6 text-end text-danger" id="discountDisplay">Rp 0</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6"><h4>TOTAL:</h4></div>
                        <div class="col-6 text-end"><h4 class="text-primary" id="totalDisplay">Rp 0</h4></div>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Jumlah Bayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" 
                                   value="{{ old('paid_amount', 0) }}" min="0" step="0.01" id="paidAmount" required>
                        </div>
                        @error('paid_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-6"><strong>Kembalian:</strong></div>
                        <div class="col-6 text-end"><strong class="text-success" id="changeDisplay">Rp 0</strong></div>
                    </div>

                    <!-- Quick Payment Buttons -->
                    <div class="mb-3">
                        <label class="form-label">Pembayaran Cepat</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary quick-pay" data-amount="exact">Pas</button>
                            <button type="button" class="btn btn-outline-primary quick-pay" data-amount="50000">50K</button>
                            <button type="button" class="btn btn-outline-primary quick-pay" data-amount="100000">100K</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="2" placeholder="Catatan penjualan (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success w-100 mb-2" id="submitBtn" disabled>
                        <i class="ti ti-device-floppy"></i>
                        Simpan Penjualan
                    </button>
                    <button type="button" class="btn btn-primary w-100" id="completeBtn" disabled>
                        <i class="ti ti-check"></i>
                        Selesaikan & Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('css')
<style>
    .item-card {
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    
    .item-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .item-card .card-body {
        padding: 1rem;
    }
    
    .item-card .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .item-card .input-group-sm .form-control {
        font-size: 0.875rem;
    }
    
    .item-card .qty-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .item-card .total-price {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    #emptyState {
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    #emptyState i {
        opacity: 0.5;
    }
    
    /* Product Card Styles */
    .product-card {
        transition: all 0.2s ease;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .product-card.border-primary {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Product Image Styles */
    .product-image-container {
        position: relative;
        width: 100%;
        height: 120px;
        border-radius: 6px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
        transition: transform 0.2s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 6px;
        color: #6c757d;
    }
    
    .product-image-placeholder i {
        font-size: 2rem;
        opacity: 0.5;
    }
    
    /* Product Card Content */
    .product-card .card-title {
        font-size: 0.9rem;
        font-weight: 600;
        line-height: 1.2;
    }
    
    .product-card .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .product-card .text-muted.small {
        font-size: 0.75rem;
        line-height: 1.2;
    }
    
    @media (max-width: 768px) {
        .item-card .card-body {
            padding: 0.75rem;
        }
        
        .item-card .total-price {
            font-size: 1rem;
        }
        
        .item-card .form-label {
            font-size: 0.8rem;
        }
        
        .product-image-container {
            height: 100px;
        }
        
        .product-card .card-title {
            font-size: 0.85rem;
        }
        
        .product-card .text-muted.small {
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 576px) {
        .product-image-container {
            height: 80px;
        }
        
        .product-card .card-body {
            padding: 0.75rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 0;
    let products = [];
    let selectedItems = {};

    // Load products when outlet changes
    $('#outletSelect').change(function() {
        const outletId = $(this).val();
        
        if (outletId) {
            loadProducts(outletId);
            $('#productSearch').prop('disabled', false);
        } else {
            products = [];
            $('#productSearch').prop('disabled', true);
            $('#productGrid').html('<div class="col-12 text-center text-muted py-4">Pilih outlet terlebih dahulu untuk melihat produk</div>');
            clearItems();
        }
    });

    // Load products via AJAX
    function loadProducts(outletId) {
        $.ajax({
            url: '{{ route("admin.sales.products-by-outlet") }}',
            method: 'GET',
            data: { outlet_id: outletId },
            success: function(response) {
                products = response;
                displayProducts(products);
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat data produk', 'error');
            }
        });
    }

    // Display products in grid
    function displayProducts(productsToShow) {
        let html = '';
        
        if (productsToShow.length === 0) {
            html = '<div class="col-12 text-center text-muted py-4">Tidak ada produk tersedia</div>';
        } else {
            productsToShow.forEach(function(product) {
                const isSelected = selectedItems[product.id] ? 'border-primary' : '';
                
                // Handle product image
                let imageHtml = '';
                if (product.image && product.image !== '' && product.image !== null) {
                    // If product has image, display it
                    const imageUrl = product.image.startsWith('http') ? product.image : `/storage/products/${product.image}`;
                    imageHtml = `
                        <div class="product-image-container mb-2">
                            <img src="${imageUrl}" alt="${product.name}" class="product-image" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="product-image-placeholder" style="display: none;">
                                <i class="ti ti-photo"></i>
                            </div>
                        </div>
                    `;
                } else {
                    // If no image, show placeholder
                    imageHtml = `
                        <div class="product-image-container mb-2">
                            <div class="product-image-placeholder">
                                <i class="ti ti-photo"></i>
                            </div>
                        </div>
                    `;
                }
                
                html += `
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <div class="card product-card ${isSelected}" data-product-id="${product.id}" style="cursor: pointer;">
                            <div class="card-body p-3">
                                ${imageHtml}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-1" title="${product.name}">${product.name.length > 20 ? product.name.substring(0, 20) + '...' : product.name}</h6>
                                    <span class="badge bg-secondary text-white">${product.stock} ${product.unit}</span>
                                </div>
                                <p class="text-muted small mb-1">${product.code}</p>
                                <p class="text-muted small mb-2">${product.category}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-primary">${formatCurrency(product.selling_price)}</strong>
                                    ${selectedItems[product.id] ? '<i class="ti ti-check text-primary"></i>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        $('#productGrid').html(html);
    }

    // Product search
    $('#productSearch').on('input', function() {
        const search = $(this).val().toLowerCase();
        const filtered = products.filter(function(product) {
            return product.name.toLowerCase().includes(search) || 
                   product.code.toLowerCase().includes(search) ||
                   product.category.toLowerCase().includes(search);
        });
        displayProducts(filtered);
    });

    // Product card click
    $(document).on('click', '.product-card', function() {
        const productId = $(this).data('product-id');
        const product = products.find(p => p.id == productId);
        
        if (product) {
            addOrUpdateItem(product);
        }
    });

    // Add or update item
    function addOrUpdateItem(product) {
        if (selectedItems[product.id]) {
            // Update quantity
            selectedItems[product.id].quantity += 1;
            updateItemRow(product.id);
        } else {
            // Add new item
            selectedItems[product.id] = {
                product: product,
                quantity: 1,
                unit_price: product.selling_price
            };
            addItemRow(product);
        }
        
        displayProducts(products.filter(p => $('#productSearch').val() === '' || 
            p.name.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.code.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.category.toLowerCase().includes($('#productSearch').val().toLowerCase())
        ));
        updateSummary();
        updateButtons();
    }

    // Add item card
    function addItemRow(product) {
        $('#emptyState').hide();
        
        const card = `
            <div class="card mb-3 item-card" data-product-id="${product.id}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Product Info -->
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <h6 class="mb-1">${product.name}</h6>
                                    <small class="text-muted">${product.code}</small>
                                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${product.id}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="col-6 col-md-2 mb-3 mb-md-0">
                            <label class="form-label small text-muted mb-1">Harga</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" 
                                       value="${product.selling_price}" min="0" step="0.01" required>
                            </div>
                        </div>
                        
                        <!-- Quantity -->
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <label class="form-label small text-muted mb-1">Jumlah</label>
                            <div class="input-group input-group-sm">
                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input text-center" 
                                       value="1" min="1" max="${product.stock}" required>
                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Total & Actions -->
                        <div class="col-12 col-md-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="form-label small text-muted mb-1 d-block d-md-none">Total</label>
                                    <strong class="total-price text-primary fs-5">${formatCurrency(product.selling_price)}</strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item ms-2">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#itemsContainer').append(card);
        itemIndex++;
    }

    // Update item card
    function updateItemRow(productId) {
        const item = selectedItems[productId];
        const card = $(`.item-card[data-product-id="${productId}"]`);
        
        card.find('.quantity-input').val(item.quantity);
        card.find('.price-input').val(item.unit_price);
        
        const total = item.quantity * item.unit_price;
        card.find('.total-price').text(formatCurrency(total));
    }

    // Quantity buttons
    $(document).on('click', '.qty-btn', function() {
        const action = $(this).data('action');
        const input = $(this).siblings('.quantity-input');
        const productId = $(this).closest('.item-card').data('product-id');
        const product = products.find(p => p.id == productId);
        let currentVal = parseInt(input.val()) || 1;
        
        if (action === 'increase' && currentVal < product.stock) {
            currentVal++;
        } else if (action === 'decrease' && currentVal > 1) {
            currentVal--;
        }
        
        input.val(currentVal);
        selectedItems[productId].quantity = currentVal;
        
        calculateRowTotal($(this).closest('.item-card'));
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        const productId = $(this).closest('.item-card').data('product-id');
        delete selectedItems[productId];
        
        $(this).closest('.item-card').remove();
        
        if (Object.keys(selectedItems).length === 0) {
            $('#emptyState').show();
        }
        
        displayProducts(products.filter(p => $('#productSearch').val() === '' || 
            p.name.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.code.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.category.toLowerCase().includes($('#productSearch').val().toLowerCase())
        ));
        updateSummary();
        updateButtons();
    });

    // Clear all items
    $('#clearAllBtn').click(function() {
        if (confirm('Yakin ingin menghapus semua item?')) {
            selectedItems = {};
            $('.item-card').remove();
            $('#emptyState').show();
            displayProducts(products);
            updateSummary();
            updateButtons();
        }
    });

    // Quantity or price change
    $(document).on('input', '.quantity-input, .price-input', function() {
        const card = $(this).closest('.item-card');
        const productId = card.data('product-id');
        
        if ($(this).hasClass('quantity-input')) {
            selectedItems[productId].quantity = parseInt($(this).val()) || 1;
        } else {
            selectedItems[productId].unit_price = parseFloat($(this).val()) || 0;
        }
        
        calculateRowTotal(card);
    });

    // Tax or discount change
    $('#taxAmount, #discountAmount').on('input', function() {
        updateSummary();
    });

    // Paid amount change
    $('#paidAmount').on('input', function() {
        calculateChange();
    });

    // Quick payment buttons
    $('.quick-pay').click(function() {
        const amount = $(this).data('amount');
        const total = parseFloat($('#totalDisplay').text().replace(/[^\d]/g, '')) || 0;
        
        if (amount === 'exact') {
            $('#paidAmount').val(total);
        } else {
            $('#paidAmount').val(amount);
        }
        
        calculateChange();
    });

    // Calculate row total
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const total = quantity * price;
        
        row.find('.total-price').text(formatCurrency(total));
        updateSummary();
    }

    // Update summary
    function updateSummary() {
        let subtotal = 0;
        
        Object.values(selectedItems).forEach(function(item) {
            subtotal += item.quantity * item.unit_price;
        });

        const tax = parseFloat($('#taxAmount').val()) || 0;
        const discount = parseFloat($('#discountAmount').val()) || 0;
        const total = subtotal + tax - discount;

        $('#subtotalDisplay').text(formatCurrency(subtotal));
        $('#taxDisplay').text(formatCurrency(tax));
        $('#discountDisplay').text(formatCurrency(discount));
        $('#totalDisplay').text(formatCurrency(total));
        
        calculateChange();
    }

    // Calculate change
    function calculateChange() {
        const total = parseFloat($('#totalDisplay').text().replace(/[^\d]/g, '')) || 0;
        const paid = parseFloat($('#paidAmount').val()) || 0;
        const change = Math.max(0, paid - total);
        
        $('#changeDisplay').text(formatCurrency(change));
    }

    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Clear all items
    function clearItems() {
        selectedItems = {};
        $('.item-card').remove();
        $('#emptyState').show();
        updateSummary();
        updateButtons();
    }

    // Update button states
    function updateButtons() {
        const hasItems = Object.keys(selectedItems).length > 0;
        $('#submitBtn, #completeBtn, #clearAllBtn').prop('disabled', !hasItems);
    }

    // Complete and pay button
    $('#completeBtn').click(function() {
        const total = parseFloat($('#totalDisplay').text().replace(/[^\d]/g, '')) || 0;
        const paid = parseFloat($('#paidAmount').val()) || 0;
        
        if (paid < total) {
            Swal.fire('Peringatan', 'Jumlah bayar kurang dari total', 'warning');
            return;
        }
        
        // Add hidden input to mark as completed
        $('<input>').attr({
            type: 'hidden',
            name: 'complete_sale',
            value: '1'
        }).appendTo('#saleForm');
        
        $('#saleForm').submit();
    });

    // Form validation
    $('#saleForm').submit(function(e) {
        const hasItems = Object.keys(selectedItems).length > 0;
        
        if (!hasItems) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Minimal harus ada satu item penjualan', 'warning');
            return false;
        }

        const total = parseFloat($('#totalDisplay').text().replace(/[^\d]/g, '')) || 0;
        const paid = parseFloat($('#paidAmount').val()) || 0;
        
        if ($('input[name="complete_sale"]').length > 0 && paid < total) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Jumlah bayar kurang dari total', 'warning');
            return false;
        }
    });
});
</script>
@endsection