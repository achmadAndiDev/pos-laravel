@extends('admin.layouts.app')

@section('title', 'Laporan')
@section('subtitle', 'Laporan Laba Rugi')

@section('css')
<style>
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .stats-card {
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Optimasi tabel untuk mobile */
    .table-responsive .table {
        min-width: 800px;
    }
    
    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table-responsive .table th,
        .table-responsive .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .btn-list .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    .profit-positive {
        color: #28a745;
        font-weight: bold;
    }
    .profit-negative {
        color: #dc3545;
        font-weight: bold;
    }
    .margin-high {
        background-color: #d4edda;
        color: #155724;
    }
    .margin-medium {
        background-color: #fff3cd;
        color: #856404;
    }
    .margin-low {
        background-color: #f8d7da;
        color: #721c24;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .page-header {
            display: none !important;
        }
        .navbar {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('right-header')
<div class="btn-list">
    <button type="button" class="btn btn-outline-primary" onclick="printReport()">
        <i class="ti ti-printer me-1"></i>
        Print
    </button>
    <button type="button" class="btn btn-primary" onclick="exportPdf()">
        <i class="ti ti-file-type-pdf me-1"></i>
        Export PDF
    </button>
</div>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <!-- Filter Card -->
        <div class="card filter-card mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="{{ route('kasir.profit-calculation.report') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-white">Outlet</label>
                            <select name="outlet_id" class="form-select">
                                <option value="">Semua Outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}" {{ $outletId == $outlet->id ? 'selected' : '' }}>
                                        {{ $outlet->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-light">
                                    <i class="ti ti-search me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Penjualan</div>
                        </div>
                        <div class="h1 mb-3">{{ $profitData['summary']['total_sales'] }}</div>
                        <div class="d-flex mb-2">
                            <div>Transaksi selesai</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Pendapatan</div>
                        </div>
                        <div class="h1 mb-3 text-blue">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</div>
                        <div class="d-flex mb-2">
                            <div>Revenue kotor</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Laba Kotor</div>
                        </div>
                        <div class="h1 mb-3 {{ $profitData['summary']['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($profitData['summary']['gross_profit'], 0, ',', '.') }}
                        </div>
                        <div class="d-flex mb-2">
                            <div>Margin: {{ number_format($profitData['summary']['gross_margin_percentage'], 1) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Laba Bersih</div>
                        </div>
                        <div class="h1 mb-3 {{ $profitData['summary']['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($profitData['summary']['net_profit'], 0, ',', '.') }}
                        </div>
                        <div class="d-flex mb-2">
                            <div>Margin: {{ number_format($profitData['summary']['net_margin_percentage'], 1) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Laba Rugi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Total Pendapatan</strong></td>
                                        <td class="text-end">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Biaya Pokok</strong></td>
                                        <td class="text-end text-danger">Rp {{ number_format($profitData['summary']['total_cost'], 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Laba Kotor</strong></td>
                                        <td class="text-end {{ $profitData['summary']['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            Rp {{ number_format($profitData['summary']['gross_profit'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Total Pajak</strong></td>
                                        <td class="text-end text-danger">Rp {{ number_format($profitData['summary']['total_tax'], 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Diskon</strong></td>
                                        <td class="text-end text-success">Rp {{ number_format($profitData['summary']['total_discount'], 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Laba Bersih</strong></td>
                                        <td class="text-end {{ $profitData['summary']['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            Rp {{ number_format($profitData['summary']['net_profit'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit by Product -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Laba per Produk</h3>
                <div class="card-actions">
                    <span class="text-muted">{{ count($profitData['by_product']) }} produk</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Qty Terjual</th>
                            <th>Pendapatan</th>
                            <th>Biaya</th>
                            <th>Laba Kotor</th>
                            <th>Margin (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profitData['by_product'] as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $product['product']->name }}</div>
                                        <div class="text-muted">{{ $product['product']->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product['quantity_sold'] }}</td>
                            <td>Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($product['cost'], 0, ',', '.') }}</td>
                            <td class="{{ $product['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rp {{ number_format($product['gross_profit'], 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge {{ $product['margin_percentage'] >= 50 ? 'margin-high' : ($product['margin_percentage'] >= 20 ? 'margin-medium' : 'margin-low') }}">
                                    {{ number_format($product['margin_percentage'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <p class="empty-title">Tidak ada data produk</p>
                                    <p class="empty-subtitle text-muted">
                                        Belum ada penjualan produk pada periode yang dipilih.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Profit by Outlet -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Laba per Outlet</h3>
                <div class="card-actions">
                    <span class="text-muted">{{ count($profitData['by_outlet']) }} outlet</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Outlet</th>
                            <th>Penjualan</th>
                            <th>Pendapatan</th>
                            <th>Biaya</th>
                            <th>Laba Kotor</th>
                            <th>Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profitData['by_outlet'] as $index => $outlet)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $outlet['outlet']->name }}</div>
                                        <div class="text-muted">{{ $outlet['outlet']->address }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $outlet['sales_count'] }} transaksi</td>
                            <td>Rp {{ number_format($outlet['revenue'], 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($outlet['cost'], 0, ',', '.') }}</td>
                            <td class="{{ $outlet['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rp {{ number_format($outlet['gross_profit'], 0, ',', '.') }}
                            </td>
                            <td class="{{ $outlet['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rp {{ number_format($outlet['net_profit'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <p class="empty-title">Tidak ada data outlet</p>
                                    <p class="empty-subtitle text-muted">
                                        Belum ada penjualan outlet pada periode yang dipilih.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Profit -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laba Harian</h3>
                <div class="card-actions">
                    <span class="text-muted">{{ count($profitData['daily']) }} hari</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Penjualan</th>
                            <th>Pendapatan</th>
                            <th>Biaya</th>
                            <th>Laba Kotor</th>
                            <th>Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profitData['daily'] as $daily)
                        <tr>
                            <td>
                                <div class="font-weight-medium">{{ $daily['date']->format('d/m/Y') }}</div>
                                <div class="text-muted">{{ $daily['date']->format('l') }}</div>
                            </td>
                            <td>{{ $daily['sales_count'] }} transaksi</td>
                            <td>Rp {{ number_format($daily['revenue'], 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($daily['cost'], 0, ',', '.') }}</td>
                            <td class="{{ $daily['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rp {{ number_format($daily['gross_profit'], 0, ',', '.') }}
                            </td>
                            <td class="{{ $daily['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rp {{ number_format($daily['net_profit'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty">
                                    <p class="empty-title">Tidak ada data harian</p>
                                    <p class="empty-subtitle text-muted">
                                        Belum ada penjualan pada periode yang dipilih.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function exportPdf() {
        // Get current filter parameters
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // Redirect to PDF export with current filters
        window.open('{{ route("admin.profit-calculation.export-pdf") }}?' + params.toString(), '_blank');
    }

    function printReport() {
        // Get current filter parameters
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // Open print view in new window
        const printWindow = window.open('{{ route("admin.profit-calculation.print-report") }}?' + params.toString(), '_blank');
        printWindow.focus();
    }

    // Auto submit form when filter changes
    document.addEventListener('DOMContentLoaded', function() {
        const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    });
</script>
@endsection