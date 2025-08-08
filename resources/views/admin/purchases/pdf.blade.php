<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 9px;
        }
        .stats {
            width: 100%;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
        }
        .stats-row {
            display: table;
            width: 100%;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            width: 25%;
            vertical-align: top;
        }
        .stat-item .number {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        .stat-item .label {
            font-size: 8px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 8px;
        }
        td {
            font-size: 8px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .status {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
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
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
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
        <div class="stats-row">
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
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Kode</th>
                <th width="8%">Tanggal</th>
                <th width="15%">Outlet</th>
                <th width="18%">Supplier</th>
                <th width="6%">Status</th>
                <th width="12%">Total</th>
                <th width="25%">Items</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $index => $purchase)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $purchase->code }}</strong>
                    @if($purchase->invoice_number)
                        <br><span style="font-size: 7px;">{{ $purchase->invoice_number }}</span>
                    @endif
                </td>
                <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $purchase->outlet->name }}</strong>
                    @if($purchase->outlet->address)
                        <br><span style="font-size: 7px;">{{ Str::limit($purchase->outlet->address, 25) }}</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $purchase->supplier_name }}</strong>
                    @if($purchase->supplier_phone)
                        <br><span style="font-size: 7px;">{{ $purchase->supplier_phone }}</span>
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
                    @foreach($purchase->purchaseItems->take(4) as $item)
                        <div style="font-size: 7px; margin-bottom: 1px;">
                            {{ Str::limit($item->product->name, 20) }} ({{ $item->quantity }})
                        </div>
                    @endforeach
                    @if($purchase->purchaseItems->count() > 4)
                        <div style="font-size: 6px; color: #666;">+{{ $purchase->purchaseItems->count() - 4 }} lainnya</div>
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
        <p>Halaman {{ $purchases->count() > 0 ? '1' : '0' }} dari 1</p>
    </div>
</body>
</html>