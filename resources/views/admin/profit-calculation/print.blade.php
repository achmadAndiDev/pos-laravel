<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi - {{ date('d/m/Y') }}</title>
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
        .summary-stats {
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
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .stat-item .label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .profit-positive {
            color: #28a745;
        }
        .profit-negative {
            color: #dc3545;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .summary-table .text-right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            font-size: 9px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
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
        <h2>LAPORAN LABA RUGI</h2>
        <p>Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <div class="number">{{ $profitData['summary']['total_sales'] }}</div>
            <div class="label">Total Penjualan</div>
        </div>
        <div class="stat-item">
            <div class="number">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
        <div class="stat-item">
            <div class="number {{ $profitData['summary']['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                Rp {{ number_format($profitData['summary']['gross_profit'], 0, ',', '.') }}
            </div>
            <div class="label">Laba Kotor</div>
        </div>
        <div class="stat-item">
            <div class="number {{ $profitData['summary']['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                Rp {{ number_format($profitData['summary']['net_profit'], 0, ',', '.') }}
            </div>
            <div class="label">Laba Bersih</div>
        </div>
    </div>

    <div class="summary-table">
        <h3 class="section-title">Ringkasan Laba Rugi</h3>
        <table>
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td class="text-right">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Biaya Pokok</strong></td>
                <td class="text-right profit-negative">Rp {{ number_format($profitData['summary']['total_cost'], 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td><strong>Laba Kotor</strong></td>
                <td class="text-right {{ $profitData['summary']['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    <strong>Rp {{ number_format($profitData['summary']['gross_profit'], 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Total Pajak</strong></td>
                <td class="text-right profit-negative">Rp {{ number_format($profitData['summary']['total_tax'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Diskon</strong></td>
                <td class="text-right profit-positive">Rp {{ number_format($profitData['summary']['total_discount'], 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td><strong>Laba Bersih</strong></td>
                <td class="text-right {{ $profitData['summary']['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    <strong>Rp {{ number_format($profitData['summary']['net_profit'], 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Margin Kotor</strong></td>
                <td class="text-right">{{ number_format($profitData['summary']['gross_margin_percentage'], 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Margin Bersih</strong></td>
                <td class="text-right">{{ number_format($profitData['summary']['net_margin_percentage'], 1) }}%</td>
            </tr>
        </table>
    </div>

    @if(count($profitData['by_product']) > 0)
    <h3 class="section-title">Laba per Produk (Top 10)</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Produk</th>
                <th width="10%">Qty</th>
                <th width="15%">Pendapatan</th>
                <th width="15%">Biaya</th>
                <th width="15%">Laba Kotor</th>
                <th width="15%">Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach(array_slice($profitData['by_product'], 0, 10) as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $product['product']->name }}</strong>
                    <br><small>{{ $product['product']->code }}</small>
                </td>
                <td class="text-center">{{ $product['quantity_sold'] }}</td>
                <td class="text-right">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                <td class="text-right profit-negative">Rp {{ number_format($product['cost'], 0, ',', '.') }}</td>
                <td class="text-right {{ $product['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    Rp {{ number_format($product['gross_profit'], 0, ',', '.') }}
                </td>
                <td class="text-center">{{ number_format($product['margin_percentage'], 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($profitData['by_outlet']) > 0)
    <h3 class="section-title">Laba per Outlet</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Outlet</th>
                <th width="10%">Penjualan</th>
                <th width="15%">Pendapatan</th>
                <th width="15%">Biaya</th>
                <th width="15%">Laba Kotor</th>
                <th width="15%">Laba Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profitData['by_outlet'] as $index => $outlet)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $outlet['outlet']->name }}</strong>
                    <br><small>{{ Str::limit($outlet['outlet']->address, 30) }}</small>
                </td>
                <td class="text-center">{{ $outlet['sales_count'] }}</td>
                <td class="text-right">Rp {{ number_format($outlet['revenue'], 0, ',', '.') }}</td>
                <td class="text-right profit-negative">Rp {{ number_format($outlet['cost'], 0, ',', '.') }}</td>
                <td class="text-right {{ $outlet['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    Rp {{ number_format($outlet['gross_profit'], 0, ',', '.') }}
                </td>
                <td class="text-right {{ $outlet['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                    Rp {{ number_format($outlet['net_profit'], 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

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