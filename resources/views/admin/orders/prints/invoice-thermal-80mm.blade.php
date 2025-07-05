<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Thermal - {{ $order->order_id }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 5mm;
            font-size: 9pt;
            width: 80mm;
            box-sizing: border-box;
        }
        
        .thermal-invoice {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 5mm;
        }
        
        .logo {
            max-width: 40mm;
            max-height: 15mm;
            margin-bottom: 2mm;
        }
        
        .company-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }
        
        .company-info {
            font-size: 8pt;
            margin-bottom: 1mm;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 3mm;
        }
        
        .invoice-details {
            margin-bottom: 3mm;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }
        
        .detail-label {
            font-weight: bold;
        }
        
        .customer-info {
            margin-bottom: 3mm;
        }
        
        .product-table {
            width: 100%;
            margin-bottom: 3mm;
            font-size: 8pt;
        }
        
        .product-row {
            margin-bottom: 2mm;
        }
        
        .product-name {
            font-weight: bold;
        }
        
        .product-details {
            display: flex;
            justify-content: space-between;
        }
        
        .total-section {
            margin-bottom: 3mm;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }
        
        .total-label {
            text-align: right;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 10pt;
        }
        
        .payment-info {
            margin-bottom: 3mm;
        }
        
        .barcode {
            text-align: center;
            margin-bottom: 3mm;
        }
        
        .barcode img {
            max-width: 100%;
            height: auto;
        }
        
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 5mm;
        }
        
        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>
<body>
    <div class="thermal-invoice">
        <!-- Header with Logo and Company Info -->
        @if(isset($options['show_logo']) && $options['show_logo'])
        <div class="header">
            <img src="{{ asset('client/img/logo.png') }}" alt="Logo" class="logo">
            <div class="company-name">Pindon Outdoor</div>
            <div class="company-info">Jl. Contoh No. 123, Kota, Provinsi</div>
            <div class="company-info">Telp: (021) 1234567</div>
        </div>
        @endif
        
        <div class="divider"></div>
        
        <!-- Invoice Title -->
        <div class="invoice-title">INVOICE</div>
        
        <!-- Invoice Details -->
        <div class="invoice-details">
            @if(isset($options['show_order_id']) && $options['show_order_id'])
            <div class="detail-row">
                <div class="detail-label">No. Invoice:</div>
                <div>#{{ $order->order_id }}</div>
            </div>
            @endif
            
            @if(isset($options['show_order_date']) && $options['show_order_date'])
            <div class="detail-row">
                <div class="detail-label">Tanggal:</div>
                <div>{{ $order->order_date->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            
            @if(isset($options['show_customer_name']) && $options['show_customer_name'])
            <div class="detail-row">
                <div class="detail-label">Penerima:</div>
                <div>
                    @if(isset($order->shippingAddress) && !empty($order->shippingAddress->name))
                        {{ $order->shippingAddress->name }}
                    @elseif(isset($order->customer) && !empty($order->customer->name))
                        {{ $order->customer->name }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            @endif
            
            @if(isset($options['show_customer_phone']) && $options['show_customer_phone'])
            <div class="detail-row">
                <div class="detail-label">Telp:</div>
                <div>
                    @if(isset($order->shippingAddress) && !empty($order->shippingAddress->phone))
                        {{ $order->shippingAddress->phone }}
                    @elseif(isset($order->customer) && !empty($order->customer->phone))
                        {{ $order->customer->phone }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            @endif
            
            @if(isset($options['show_shipping_address']) && $options['show_shipping_address'])
            <div class="detail-row">
                <div class="detail-label">Alamat:</div>
                <div>
                    @if(isset($order->shippingAddress))
                        {{ $order->shippingAddress->district ?? 'N/A' }}, {{ $order->shippingAddress->city ?? 'N/A' }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <!-- Product List -->
        @if(isset($options['show_product_list']) && $options['show_product_list'])
        <div class="product-table">
            @foreach($order->products as $product)
            <div class="product-row">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-details">
                    <div>{{ $product->pivot->quantity }}x</div>
                    @if(isset($options['show_product_price']) && $options['show_product_price'])
                    <div>Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</div>
                    <div>Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</div>
                    @endif
                </div>
                @if(isset($options['show_product_sku']) && $options['show_product_sku'])
                <div>SKU: {{ $product->sku ?? 'N/A' }}</div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="divider"></div>
        
        <!-- Total Section -->
        @if(isset($options['show_product_price']) && $options['show_product_price'])
        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div>Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</div>
            </div>
            
            @if($order->discount > 0)
            <div class="total-row">
                <div class="total-label">Diskon:</div>
                <div>Rp {{ number_format($order->discount ?? 0, 0, ',', '.') }}</div>
            </div>
            @endif
            
            <div class="total-row">
                <div class="total-label">Ongkir:</div>
                <div>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</div>
            </div>
            
            @if($order->additional_fee > 0)
            <div class="total-row">
                <div class="total-label">Biaya Tambahan:</div>
                <div>Rp {{ number_format($order->additional_fee ?? 0, 0, ',', '.') }}</div>
            </div>
            @endif
            
            <div class="divider"></div>
            
            <div class="total-row grand-total">
                <div class="total-label">TOTAL:</div>
                <div>Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
        @endif
        
        <!-- Payment Info -->
        <div class="payment-info">
            <div class="detail-row">
                <div class="detail-label">Status Pembayaran:</div>
                <div>
                    @if($order->payment_status === 'PAID')
                        LUNAS
                    @elseif($order->payment_status === 'INSTALLMENT')
                        SEBAGIAN
                    @else
                        BELUM DIBAYAR
                    @endif
                </div>
            </div>
            
            @if($order->payment_status === 'INSTALLMENT')
            <div class="detail-row">
                <div class="detail-label">Dibayar:</div>
                <div>Rp {{ number_format($order->paymentHistories->sum('nominal') ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Sisa:</div>
                <div>Rp {{ number_format($order->total_amount - $order->paymentHistories->sum('nominal'), 0, ',', '.') }}</div>
            </div>
            @endif
        </div>
        
        @if(isset($options['show_shipping_method']) && $options['show_shipping_method'])
        <div class="divider"></div>
        
        <div class="detail-row">
            <div class="detail-label">Pengiriman:</div>
            <div>{{ $order->shipping_courier ?? 'N/A' }} ({{ $order->shipping_service ?? 'N/A' }})</div>
        </div>
        
        @if(isset($options['show_tracking_number']) && $options['show_tracking_number'] && $order->tracking_number)
        <div class="detail-row">
            <div class="detail-label">No. Resi:</div>
            <div>{{ $order->tracking_number }}</div>
        </div>
        @endif
        @endif
        
        <!-- Barcode -->
        @if(isset($options['show_barcode']) && $options['show_barcode'])
        <div class="divider"></div>
        
        <div class="barcode">
            {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->order_id, 'C128', 2, 30) }}" alt="Barcode"> --}}
            <div>{{ $order->order_id }}</div>
        </div>
        @endif
        
        <div class="divider"></div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p>www.pindonoutdoor.com</p>
        </div>
    </div>
</body>
</html>