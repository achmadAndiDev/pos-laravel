<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_id }}</title>
    <style>
        @page {
            size: 210mm 297mm;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
        
        .invoice {
            width: 100%;
            max-width: 210mm;
            padding: 10mm;
            box-sizing: border-box;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10mm;
            border-bottom: 1px solid #000;
            padding-bottom: 5mm;
        }
        
        .logo {
            max-width: 50mm;
            max-height: 20mm;
        }
        
        .company-info {
            font-size: 10pt;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 10mm;
            text-transform: uppercase;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10mm;
        }
        
        .invoice-to {
            width: 50%;
        }
        
        .invoice-info {
            width: 40%;
            text-align: right;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 3mm;
            text-transform: uppercase;
            font-size: 10pt;
            color: #555;
        }
        
        .customer-name {
            font-weight: bold;
            margin-bottom: 2mm;
        }
        
        .invoice-id {
            font-weight: bold;
            margin-bottom: 2mm;
        }
        
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
        }
        
        .product-table th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 2mm;
            border-bottom: 1px solid #000;
        }
        
        .product-table td {
            padding: 2mm;
            border-bottom: 1px solid #ddd;
        }
        
        .product-table .text-right {
            text-align: right;
        }
        
        .product-table .text-center {
            text-align: center;
        }
        
        .total-section {
            width: 100%;
            margin-bottom: 10mm;
        }
        
        .total-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .total-table td {
            padding: 2mm;
        }
        
        .total-table .total-label {
            text-align: right;
            font-weight: normal;
        }
        
        .total-table .total-value {
            text-align: right;
            font-weight: bold;
        }
        
        .total-table .grand-total {
            font-size: 14pt;
            font-weight: bold;
            border-top: 1px solid #000;
        }
        
        .payment-info {
            margin-bottom: 10mm;
        }
        
        .payment-status {
            display: inline-block;
            padding: 2mm 5mm;
            font-weight: bold;
            border-radius: 2mm;
            margin-bottom: 5mm;
        }
        
        .payment-status.paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .payment-status.unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .payment-status.partial {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .footer {
            text-align: center;
            font-size: 10pt;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 5mm;
        }
        
        .barcode {
            text-align: center;
            margin-bottom: 5mm;
        }
        
        .barcode img {
            max-width: 100%;
            height: auto;
        }
        
        @media print {
            body {
                width: 210mm;
                height: 297mm;
            }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Header with Logo and Company Info -->
        @if(isset($options['show_logo']) && $options['show_logo'])
        <div class="header">
            <div>
                <img src="{{ asset('client/img/logo.png') }}" alt="Logo" class="logo">
            </div>
            <div class="company-info">
                <div><strong>Pindon Outdoor</strong></div>
                <div>Jl. Contoh No. 123, Kota, Provinsi</div>
                <div>Telp: (021) 1234567</div>
                <div>Email: info@pindonoutdoor.com</div>
            </div>
        </div>
        @endif
        
        <!-- Invoice Title -->
        <div class="invoice-title">Invoice</div>
        
        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="invoice-to">
                <div class="section-title">Kepada</div>
                @if(isset($options['show_customer_name']) && $options['show_customer_name'])
                <div class="customer-name">
                    @if(isset($order->shippingAddress) && !empty($order->shippingAddress->name))
                        {{ $order->shippingAddress->name }}
                    @elseif(isset($order->customer) && !empty($order->customer->name))
                        {{ $order->customer->name }}
                    @else
                        N/A
                    @endif
                </div>
                @endif
                
                @if(isset($options['show_shipping_address']) && $options['show_shipping_address'])
                <div>
                    @if(isset($order->shippingAddress))
                        {{ $order->shippingAddress->address ?? $order->shippingAddress->line ?? 'N/A' }}<br>
                        {{ $order->shippingAddress->district ?? 'N/A' }}, {{ $order->shippingAddress->city ?? 'N/A' }}<br>
                        {{ $order->shippingAddress->province ?? 'N/A' }}
                        @if(!empty($order->shippingAddress->postal_code))
                        , {{ $order->shippingAddress->postal_code }}
                        @endif
                    @else
                        Alamat pengiriman tidak tersedia
                    @endif
                </div>
                @endif
                
                @if(isset($options['show_customer_phone']) && $options['show_customer_phone'])
                <div>Telp: 
                    @if(isset($order->shippingAddress) && !empty($order->shippingAddress->phone))
                        {{ $order->shippingAddress->phone }}
                    @elseif(isset($order->customer) && !empty($order->customer->phone))
                        {{ $order->customer->phone }}
                    @else
                        N/A
                    @endif
                </div>
                @endif
                
                @if(isset($options['show_customer_email']) && $options['show_customer_email'])
                <div>Email: 
                    @if(isset($order->shippingAddress) && !empty($order->shippingAddress->email))
                        {{ $order->shippingAddress->email }}
                    @elseif(isset($order->customer) && !empty($order->customer->email))
                        {{ $order->customer->email }}
                    @else
                        N/A
                    @endif
                </div>
                @endif
            </div>
            
            <div class="invoice-info">
                @if(isset($options['show_order_id']) && $options['show_order_id'])
                <div class="invoice-id">Invoice #{{ $order->order_id }}</div>
                @endif
                
                @if(isset($options['show_order_date']) && $options['show_order_date'])
                <div>Tanggal: {{ $order->order_date->format('d M Y') }}</div>
                @endif
                
                @if(isset($options['show_shipping_method']) && $options['show_shipping_method'])
                <div>Pengiriman: {{ $order->shipping_courier ?? 'N/A' }} ({{ $order->shipping_service ?? 'N/A' }})</div>
                @endif
                
                @if(isset($options['show_tracking_number']) && $options['show_tracking_number'] && $order->tracking_number)
                <div>No. Resi: {{ $order->tracking_number }}</div>
                @endif
            </div>
        </div>
        
        <!-- Product Table -->
        @if(isset($options['show_product_list']) && $options['show_product_list'])
        <table class="product-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Produk</th>
                    <th width="10%" class="text-center">Qty</th>
                    @if(isset($options['show_product_price']) && $options['show_product_price'])
                    <th width="20%" class="text-right">Harga</th>
                    <th width="20%" class="text-right">Subtotal</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $product->name }}
                        @if(isset($options['show_product_sku']) && $options['show_product_sku'])
                        <br><small>SKU: {{ $product->sku ?? 'N/A' }}</small>
                        @endif
                        @if(isset($options['show_product_weight']) && $options['show_product_weight'])
                        <br><small>Berat: {{ $product->weight ?? 0 }} gram</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $product->pivot->quantity }}</td>
                    @if(isset($options['show_product_price']) && $options['show_product_price'])
                    <td class="text-right">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <!-- Total Section -->
        @if(isset($options['show_product_price']) && $options['show_product_price'])
        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-value">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td class="total-label">Diskon:</td>
                    <td class="total-value">Rp {{ number_format($order->discount ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="total-label">Ongkos Kirim:</td>
                    <td class="total-value">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if($order->additional_fee > 0)
                <tr>
                    <td class="total-label">Biaya Tambahan:</td>
                    <td class="total-value">Rp {{ number_format($order->additional_fee ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="total-label grand-total">Total:</td>
                    <td class="total-value grand-total">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        @endif
        
        <!-- Payment Info -->
        <div class="payment-info">
            <div class="section-title">Informasi Pembayaran</div>
            
            @php
                $statusClass = 'unpaid';
                if ($order->payment_status === 'PAID') {
                    $statusClass = 'paid';
                } elseif ($order->payment_status === 'INSTALLMENT') {
                    $statusClass = 'partial';
                }
                
                $statusText = 'Belum Dibayar';
                if ($order->payment_status === 'PAID') {
                    $statusText = 'Lunas';
                } elseif ($order->payment_status === 'INSTALLMENT') {
                    $statusText = 'Sebagian Dibayar';
                }
            @endphp
            
            <div class="payment-status {{ $statusClass }}">{{ $statusText }}</div>
            
            @if($order->payment_status === 'PAID' && $order->payment_date)
            <div>Tanggal Pembayaran: {{ \Carbon\Carbon::parse($order->payment_date)->format('d M Y') }}</div>
            @endif
            
            @if($order->payment_status === 'INSTALLMENT')
            <div>Total Dibayar: Rp {{ number_format($order->paymentHistories->sum('nominal') ?? 0, 0, ',', '.') }}</div>
            <div>Sisa Pembayaran: Rp {{ number_format($order->total_amount - $order->paymentHistories->sum('nominal'), 0, ',', '.') }}</div>
            @endif
        </div>
        
        <!-- Barcode -->
        @if(isset($options['show_barcode']) && $options['show_barcode'])
        <div class="barcode">
            {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->order_id, 'C128', 3, 50) }}" alt="Barcode"> --}}
            <div>{{ $order->order_id }}</div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda berbelanja di Pindon Outdoor</p>
        </div>
    </div>
</body>
</html>