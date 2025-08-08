<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .number {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .stat-item .label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status.draft {
            background-color: #fff3cd;
            color: #856404;
        }
        .status.completed {
            background-color: #d1edff;
            color: #0c63e4;
        }
        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ setting('site_name', 'POS App') }}</h1>
        <h2>LAPORAN PEMBELIAN</h2>
        <p>Periode: {{ request('start_date') ? date('d/m/Y', strtotime(request('start_date'))) : 'Semua' }} - {{ request('end_date') ? date('d/m/Y', strtotime(request('end_date'))) : 'Semua' }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="number">{{ $totalPurchases }}</div>
            <div class="label">Total Pembelian</div>
        </div>
        <div class="stat-item">
            <div class="number">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            <div class="label">Total Nilai</div>
        </div>
        <div class="stat-item">
            <div class="number">{{ $completedPurchases }}</div>
            <div class="label">Selesai</div>
        </div>
        <div class="stat-item">
            <div class="number">{{ $draftPurchases }}</div>
            <div class="label">Draft</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Outlet</th>
                <th width="20%">Supplier</th>
                <th width="8%">Status</th>
                <th width="15%">Total</th>
                <th width="15%">Items</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $index => $purchase)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $purchase->code }}</strong>
                    @if($purchase->invoice_number)
                        <br><small>{{ $purchase->invoice_number }}</small>
                    @endif
                </td>
                <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $purchase->outlet->name }}</strong>
                    @if($purchase->outlet->address)
                        <br><small>{{ Str::limit($purchase->outlet->address, 30) }}</small>
                    @endif
                </td>
                <td>
                    <strong>{{ $purchase->supplier_name }}</strong>
                    @if($purchase->supplier_phone)
                        <br><small>{{ $purchase->supplier_phone }}</small>
                    @endif
                </td>
                <td class="text-center">
                    <span class="status {{ $purchase->status }}">
                        {{ $purchase->status_text }}
                    </span>
                </td>
                <td class="text-right">
                    <strong>{{ $purchase->formatted_total_amount }}</strong>
                </td>
                <td>
                    @foreach($purchase->purchaseItems->take(3) as $item)
                        <div>{{ $item->product->name }} ({{ $item->quantity }})</div>
                    @endforeach
                    @if($purchase->purchaseItems->count() > 3)
                        <small>+{{ $purchase->purchaseItems->count() - 3 }} lainnya</small>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pembelian</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem {{ setting('site_name', 'POS App') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>