@extends('admin.layouts.app')

@section('title', 'Edit Penjualan')
@section('subtitle', 'Edit Penjualan - POS')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.sales.show', $sale) }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('kasir.sales.update', $sale) }}" method="POST" id="saleForm">
    @csrf
    @method('PUT')
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
                                        <option value="{{ $outlet->id }}" {{ (old('outlet_id', $sale->outlet_id) == $outlet->id) ? 'selected' : '' }}>
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
                                        <option value="{{ $customer->id }}" {{ (old('customer_id', $sale->customer_id) == $customer->id) ? 'selected' : '' }}>
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
                                       value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" required>
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
                        <input type="text" id="productSearch" class="form-control" placeholder="Cari produk...">
                    </div>
                </div>
                <div class="card-body">
                    <div id="productGrid" class="row">
                        <!-- Products will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Item Penjualan</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clearAllBtn">
                            <i class="ti ti-trash"></i>
                            Hapus Semua
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="35%">Produk</th>
                                    <th width="15%">Harga</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="20%">Total</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @forelse($sale->saleItems as $index => $item)
                                    <tr data-product-id="{{ $item->product_id }}">
                                        <td>
                                            <div class="text-reset">{{ $item->product->name }}</div>
                                            <div class="text-muted small">{{ $item->product->code }}</div>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="items[{{ $index }}][unit_price]" class="form-control price-input" 
                                                       value="{{ $item->unit_price }}" min="0" step="0.01" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease">-</button>
                                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input text-center" 
                                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock + $item->quantity }}" required>
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="total-price">{{ $item->formatted_total_price }}</strong>
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
                                            Belum ada item dipilih
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                       value="{{ old('tax_amount', $sale->tax_amount) }}" min="0" step="0.01" id="taxAmount">
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
                                       value="{{ old('discount_amount', $sale->discount_amount) }}" min="0" step="0.01" id="discountAmount">
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
                            <option value="cash" {{ old('payment_method', $sale->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="card" {{ old('payment_method', $sale->payment_method) == 'card' ? 'selected' : '' }}>Kartu</option>
                            <option value="transfer" {{ old('payment_method', $sale->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="e_wallet" {{ old('payment_method', $sale->payment_method) == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
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
                                   value="{{ old('paid_amount', $sale->paid_amount) }}" min="0" step="0.01" id="paidAmount" required>
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
                                  rows="2" placeholder="Catatan penjualan (opsional)">{{ old('notes', $sale->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success w-100 mb-2" id="submitBtn">
                        <i class="ti ti-device-floppy"></i>
                        Update Penjualan
                    </button>
                    <button type="button" class="btn btn-primary w-100" id="completeBtn">
                        <i class="ti ti-check"></i>
                        Update & Selesaikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = {{ $sale->saleItems->count() }};
    let products = [];
    let selectedItems = {};

    // Initialize existing items
    @foreach($sale->saleItems as $item)
        selectedItems[{{ $item->product_id }}] = {
            product: {
                id: {{ $item->product_id }},
                name: '{{ $item->product->name }}',
                code: '{{ $item->product->code }}',
                selling_price: {{ $item->product->selling_price }},
                stock: {{ $item->product->stock + $item->quantity }}
            },
            quantity: {{ $item->quantity }},
            unit_price: {{ $item->unit_price }}
        };
    @endforeach

    // Load products on page load
    const initialOutletId = $('#outletSelect').val();
    if (initialOutletId) {
        loadProducts(initialOutletId);
    }

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
        }
    });

    // Load products via AJAX
    function loadProducts(outletId) {
        $.ajax({
            url: '{{ route("kasir.sales.products-by-outlet") }}',
            method: 'GET',
            data: { outlet_id: outletId },
            success: function(response) {
                products = response;
                displayProducts(products);
                updateSummary(); // Update summary after loading products
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
                html += `
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card product-card ${isSelected}" data-product-id="${product.id}" style="cursor: pointer;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-1">${product.name}</h6>
                                    <span class="badge bg-secondary">${product.stock} ${product.unit}</span>
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
    }

    // Add item row
    function addItemRow(product) {
        $('#emptyRow').hide();
        
        const row = `
            <tr data-product-id="${product.id}">
                <td>
                    <div class="text-reset">${product.name}</div>
                    <div class="text-muted small">${product.code}</div>
                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${product.id}">
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" 
                               value="${product.selling_price}" min="0" step="0.01" required>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease">-</button>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input text-center" 
                               value="1" min="1" max="${product.stock}" required>
                        <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase">+</button>
                    </div>
                </td>
                <td>
                    <strong class="total-price">${formatCurrency(product.selling_price)}</strong>
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
    }

    // Update item row
    function updateItemRow(productId) {
        const item = selectedItems[productId];
        const row = $(`tr[data-product-id="${productId}"]`);
        
        row.find('.quantity-input').val(item.quantity);
        row.find('.price-input').val(item.unit_price);
        
        const total = item.quantity * item.unit_price;
        row.find('.total-price').text(formatCurrency(total));
    }

    // Quantity buttons
    $(document).on('click', '.qty-btn', function() {
        const action = $(this).data('action');
        const input = $(this).siblings('.quantity-input');
        const productId = $(this).closest('tr').data('product-id');
        const maxStock = parseInt(input.attr('max'));
        let currentVal = parseInt(input.val()) || 1;
        
        if (action === 'increase' && currentVal < maxStock) {
            currentVal++;
        } else if (action === 'decrease' && currentVal > 1) {
            currentVal--;
        }
        
        input.val(currentVal);
        if (selectedItems[productId]) {
            selectedItems[productId].quantity = currentVal;
        }
        
        calculateRowTotal($(this).closest('tr'));
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        const productId = $(this).closest('tr').data('product-id');
        delete selectedItems[productId];
        
        $(this).closest('tr').remove();
        
        if ($('#itemsTableBody tr:not(#emptyRow)').length === 0) {
            $('#emptyRow').show();
        }
        
        displayProducts(products.filter(p => $('#productSearch').val() === '' || 
            p.name.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.code.toLowerCase().includes($('#productSearch').val().toLowerCase()) ||
            p.category.toLowerCase().includes($('#productSearch').val().toLowerCase())
        ));
        updateSummary();
    });

    // Clear all items
    $('#clearAllBtn').click(function() {
        if (confirm('Yakin ingin menghapus semua item?')) {
            selectedItems = {};
            $('#itemsTableBody tr:not(#emptyRow)').remove();
            $('#emptyRow').show();
            displayProducts(products);
            updateSummary();
        }
    });

    // Quantity or price change
    $(document).on('input', '.quantity-input, .price-input', function() {
        const row = $(this).closest('tr');
        const productId = row.data('product-id');
        
        if ($(this).hasClass('quantity-input')) {
            if (selectedItems[productId]) {
                selectedItems[productId].quantity = parseInt($(this).val()) || 1;
            }
        } else {
            if (selectedItems[productId]) {
                selectedItems[productId].unit_price = parseFloat($(this).val()) || 0;
            }
        }
        
        calculateRowTotal(row);
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
        const hasItems = $('#itemsTableBody tr:not(#emptyRow)').length > 0;
        
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

    // Initialize summary on page load
    updateSummary();
});
</script>
@endsection