<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $sale->code }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: white;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .receipt {
            width: 300px;
            margin: 0 auto;
            background: white;
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        
        .info-section {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .items-section {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .item {
            margin-bottom: 8px;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        
        .totals-section {
            margin-bottom: 15px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .total-row.grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 8px;
        }
        
        .payment-section {
            margin-bottom: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 11px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .receipt {
                border: none;
                width: 100%;
                max-width: 300px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="print-button no-print">
        <button class="btn" onclick="window.print()">
            <i class="ti ti-printer"></i>
            Cetak Struk
        </button>
        <button class="btn" onclick="window.close()" style="background: #6c757d; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h2>{{ $sale->outlet->name }}</h2>
            <p>STRUK PENJUALAN</p>
        </div>
        
        <!-- Sale Info -->
        <div class="info-section">
            <div class="info-row">
                <span>No:</span>
                <span>{{ $sale->code }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span>{{ $sale->created_by ?? 'System' }}</span>
            </div>
            @if($sale->customer)
                <div class="info-row">
                    <span>Customer:</span>
                    <span>{{ $sale->customer->name }}</span>
                </div>
            @endif
        </div>
        
        <!-- Items -->
        <div class="items-section">
            @foreach($sale->saleItems as $item)
                <div class="item">
                    <div class="item-name">{{ $item->product->name }}</div>
                    <div class="item-detail">
                        <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->tax_amount > 0)
                <div class="total-row">
                    <span>Pajak:</span>
                    <span>{{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($sale->discount_amount > 0)
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>-{{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Payment -->
        @if($sale->status === 'completed')
            <div class="payment-section">
                <div class="total-row">
                    <span>Bayar ({{ $sale->payment_method_text }}):</span>
                    <span>{{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                </div>
                @if($sale->change_amount > 0)
                    <div class="total-row">
                        <span>Kembalian:</span>
                        <span>{{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>