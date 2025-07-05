<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $order->order_id }}</title>
    <style>
        @page {
            size: 300mm 297mm;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
        
        .shipping-label {
            width: 100%;
            border: 1px solid black;
            /* max-width: 300mm; */
            padding: 10mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        
        .shipping-content {
            display: flex;
            flex-direction: row;
        }
        
        .shipping-main {
            flex: 3;
            display: flex;
            padding-right: 10mm;
            width: 100%;
            font-size: 11px;
        }
        
        .shipping-side {
            flex: 1;
            border-left: 1px dashed #000;
            padding-left: 10mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .fragile-icons {
            text-align: center;
            margin-bottom: 10mm;
        }
        
        .fragile-icon {
            max-width: 100%;
            height: auto;
            margin-bottom: 5mm;
        }
        
        .fragile-text {
            font-weight: bold;
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5mm;
        }
        
        .handling-instructions {
            border: 2px solid #000;
            padding: 5mm;
            margin-bottom: 10mm;
        }
        
        .instruction-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 3mm;
            text-transform: uppercase;
        }
        
        .instruction-item {
            margin-bottom: 2mm;
            display: flex;
            align-items: center;
        }
        
        .instruction-item:before {
            content: "•";
            margin-right: 2mm;
            font-weight: bold;
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
        
        .order-info {
            text-align: right;
        }
        
        .order-id {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2mm;
        }
        
        .order-date {
            font-size: 10pt;
            color: #555;
        }
        
        .shipping-info {
            margin-bottom: 10mm;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 3mm;
            text-transform: uppercase;
            font-size: 10pt;
            color: #555;
        }
        
        .customer-info {
            margin-bottom: 10mm;
        }
        
        .customer-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2mm;
        }
        
        .customer-contact {
            margin-bottom: 2mm;
        }
        
        .shipping-address {
            margin-bottom: 10mm;
            padding: 5mm;
            border: 1px solid #000;
            border-radius: 2mm;
        }
        
        .shipping-method {
            margin-bottom: 10mm;
            display: flex;
            justify-content: space-between;
        }
        
        .courier {
            font-weight: bold;
            font-size: 14pt;
        }
        
        .tracking-number {
            font-weight: bold;
        }
        
        .barcode {
            text-align: center;
            margin-bottom: 10mm;
        }
        
        .barcode img {
            max-width: 100%;
            height: auto;
        }
        
        .product-list {
            margin-bottom: 10mm;
        }
        
        .product-item {
            margin-bottom: 2mm;
            padding-bottom: 2mm;
            border-bottom: 1px dashed #ccc;
        }
        
        .product-name {
            font-weight: bold;
        }
        
        .product-details {
            font-size: 10pt;
            color: #555;
        }
        
        .footer {
            text-align: center;
            font-size: 10pt;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 5mm;
        }
        
        @media print {
            body {
                width: 300mm;
                height: 297mm;
            }
        }
    </style>
</head>
<body>
    <div class="shipping-label">
        <!-- Header with Logo and Order Info -->
        @if(isset($options['show_logo']) && $options['show_logo'])
        <div class="header">
            <div>
                <img src="{{ asset('client/img/logo.png') }}" alt="Logo" class="logo">
            </div>
            <div class="order-info">
                @if(isset($options['show_order_id']) && $options['show_order_id'])
                <div class="order-id">Order #{{ $order->order_id }}</div>
                @endif
                
                @if(isset($options['show_order_date']) && $options['show_order_date'])
                <div class="order-date">Tanggal: {{ $order->order_date->format('d M Y, H:i') }}</div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="shipping-content">
            <!-- Main Content (Left Side) -->
            <div class="shipping-main">

                    <div class="col-md-6 col-lg-6 col-sm-6"  style="flex: 2; width:100%; float:left; padding-right:10px;">
                            <!-- Customer Info -->

                            @if(isset($options['show_shipping_address']) && $options['show_shipping_address'])
                            {{-- <div class="shipping-address"> --}}
                            <div class="customer-info">
                                <div class="section-title"><b>Kepada:</b></div>
                                <div>
                                    @if(isset($order->shippingAddress))
                                        {{ $order->shippingAddress->name ?? 'N/A' }}<br>
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

                                
                                @if(isset($options['show_customer_phone']) && $options['show_customer_phone'])
                                <div class="customer-contact">
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
                                <div class="customer-contact">
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
                            @endif

                            <div class="customer-info">
                                <div class="section-title">Pengirim</div>
                                @php
                                    
                                        $customerData = is_array($order->customer) ? $order->customer : (is_object($order->customer) ? (array)$order->customer : []);
                                        $receiverName = $order->shippingAddress->name ?? '';
                                        $customerName = $customerData['name'] ?? '';
                                        
                                        if ($customerName && $receiverName && $customerName !== $receiverName) {
                                            // Ambil data pengirim dari customer
                                            $senderName = $customerName;
                                            
                                            // Ambil alamat dari customer
                                            $customerAddress = [];
                                            if (!empty($customerData['address'])) {
                                                $customerAddress[] = $customerData['address'];
                                            }
                                            if (!empty($customerData['district'])) {
                                                $customerAddress[] = $customerData['district'];
                                            }
                                            if (!empty($customerData['city'])) {
                                                $customerAddress[] = $customerData['city'];
                                            }
                                            if (!empty($customerData['province'])) {
                                                $customerAddress[] = $customerData['province'];
                                            }
                                            if (!empty($customerData['postal_code'])) {
                                                $customerAddress[] = $customerData['postal_code'];
                                            }
                                            
                                            if (!empty($customerAddress)) {
                                                $senderAddress = implode(', ', $customerAddress);
                                            }
                                            
                                            // Ambil nomor telepon dari customer
                                            if (!empty($customerData['phone'])) {
                                                $senderPhone = $customerData['phone'];
                                            }
                                        } else {
                                            $senderName = $order->warehouse->name ?? 'N/A';
                                            $senderAddress = $order->warehouse->address ?? 'N/A';
                                            $senderPhone = $order->warehouse->phone ?? setting('site_phone', '');
                                        }


                                @endphp
                                <p>{{ $senderName }} <br>
                                {{ $senderAddress }} <br>
                                {{ $senderPhone }}</p>
                            </div>


                           
                            

{{-- 
                            <!-- Additional Notes -->
                            <div class="handling-instructions">
                                <div class="instruction-title">Catatan Tambahan</div>
                                <div>
                                    @if($order->note)
                                        {{ $order->note }}
                                    @else
                                        Tidak ada catatan tambahan
                                    @endif
                                </div>
                            </div> --}}
                            

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6"  style="flex: 2; width:100%; float:right; padding-left:10px;">
                        
                        
                        <!-- Product List -->
                        @if(isset($options['show_product_list']) && $options['show_product_list'])
                        <div class="product-list">
                            <div class="section-title">Daftar Produk</div>
                            
                            @foreach($order->products as $product)
                            {{-- @php
                                dd($product);
                            @endphp --}}
                            <div class="product-item">
                                <div class="product-name">{{ $product->name }} ({{ $product->quantity }}x)</div>
                                
                                <div class="product-details">
                                    {{-- @if(isset($options['show_product_sku']) && $options['show_product_sku'])
                                    SKU: {{ $product->sku ?? 'N/A' }} | 
                                    @endif
                                    
                                    @if(isset($options['show_product_weight']) && $options['show_product_weight'])
                                    Berat: {{ $product->weight ?? 0 }} gram | 
                                    @endif --}}
                                    
                                    @if(isset($options['show_product_price']) && $options['show_product_price'])
                                    Harga: Rp {{ number_format($product->order_price, 0, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Shipping Method -->
                        @if(isset($options['show_shipping_method']) && $options['show_shipping_method'])
                        <div class="shipping-method">
                            <div>
                                <div class="section-title">Kurir Pengiriman</div>
                                <div class="courier">{{ $order->shipping_logistic ?? 'N/A' }}</div>
                            </div>
                            
                            @if(isset($options['show_tracking_number']) && $options['show_tracking_number'])
                            <div>
                                <div class="section-title">Nomor Resi</div>
                                <div class="tracking-number">{{ $order->awb_number ?? 'Belum ada nomor resi' }}</div>
                            </div>
                            @endif
                        </div>
                        @endif


                        <!-- Barcode -->
                        @if(isset($options['show_barcode_resi']) && $options['show_barcode_resi'] && !empty($order->awb_number))
                        <div class="barcode">
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->awb_number, 'C128', 3, 50) }}" alt="Barcode">
                            <div>RESI: {{ $order->awb_number }}</div>
                        </div>
                        @endif


                        <!-- Barcode -->
                        @if(isset($options['show_barcode_po']) && $options['show_barcode_po'])
                        <div class="barcode">
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order->po_number, 'C128', 3, 50) }}" alt="Barcode">
                            <div>PO: {{ $order->po_number }}</div>
                        </div>
                        @endif


                    </div>
                
            </div>
            
            <!-- Side Content (Right Side) -->
            @if(isset($options['show_fragile_section']) && $options['show_fragile_section'])
            <div class="shipping-side">
                <!-- Fragile Icons and Text -->
                <div class="fragile-icons">

                    <div class="fragile-icon">
                        <img src="{{ asset('img/fragile.png') }}" alt="Fragile Icon" width="150px" height="auto">
                    </div>

                    <div class="fragile-text">FRAGILE</div>
                    <div class="">BARANG JANGAN DIBANTING ATAU DI LEMPAR</div>
                </div>
                
            </div>
            @endif
        </div>
    
    </div>
</body>
</html>