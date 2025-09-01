@extends('admin.layouts.app')

@section('title', 'Perhitungan')
@section('subtitle', 'Perhitungan Laba Penjualan')

@section('right-header')
    <div class="btn-list">
        <button type="button" class="btn btn-outline-primary" onclick="exportData('csv')">
            <i class="ti ti-download me-1"></i>
            Export CSV
        </button>
        <button type="button" class="btn btn-primary" onclick="refreshData()">
            <i class="ti ti-refresh me-1"></i>
            Refresh
        </button>
    </div>
@endsection

@section('css')
<style>
    .profit-card {
        transition: transform 0.2s ease-in-out;
    }
    .profit-card:hover {
        transform: translateY(-2px);
    }
    .profit-positive {
        color: #28a745;
    }
    .profit-negative {
        color: #dc3545;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
    .filter-card {
        /* background: linear-gradient(135deg, #7cb3ff 0%, #e0e9ff 100%);
        color: white; */
    }
    .summary-card {
        background: linear-gradient(135deg, #7cb3ff 0%, #e0e9ff 100%);
        /* color: white; */
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Filter Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card filter-card">
                        <div class="card-body">
                            <form id="filterForm" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" name="start_date" value="{{ $startDate }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" name="end_date" value="{{ $endDate }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Outlet</label>
                                        <select class="form-select" name="outlet_id">
                                            <option value="">Semua Outlet</option>
                                            @foreach($outlets as $outlet)
                                                <option value="{{ $outlet->id }}" {{ $outletId == $outlet->id ? 'selected' : '' }}>
                                                    {{ $outlet->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-light w-100">
                                            <i class="ti ti-filter me-1"></i>
                                            Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1">{{ $profitData['summary']['total_sales'] }}</div>
                                        <div class="">Total Penjualan</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</div>
                                        <div class="">Total Pendapatan</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1 {{ $profitData['summary']['gross_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            Rp {{ number_format($profitData['summary']['gross_profit'], 0, ',', '.') }}
                                        </div>
                                        <div class="">Laba Kotor</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1 {{ $profitData['summary']['net_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            Rp {{ number_format($profitData['summary']['net_profit'], 0, ',', '.') }}
                                        </div>
                                        <div class="">Laba Bersih</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Summary -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card profit-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-calculator me-2"></i>
                                Ringkasan Keuangan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted">Total Pendapatan</div>
                                    <div class="h4 text-success">Rp {{ number_format($profitData['summary']['total_revenue'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Total Biaya</div>
                                    <div class="h4 text-danger">Rp {{ number_format($profitData['summary']['total_cost'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Total Pajak</div>
                                    <div class="h5">Rp {{ number_format($profitData['summary']['total_tax'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Total Diskon</div>
                                    <div class="h5">Rp {{ number_format($profitData['summary']['total_discount'], 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card profit-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-percentage me-2"></i>
                                Margin Keuntungan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted">Margin Kotor</div>
                                    <div class="h4 {{ $profitData['summary']['gross_margin_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($profitData['summary']['gross_margin_percentage'], 2) }}%
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Margin Bersih</div>
                                    <div class="h4 {{ $profitData['summary']['net_margin_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($profitData['summary']['net_margin_percentage'], 2) }}%
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: {{ max(0, min(100, $profitData['summary']['gross_margin_percentage'])) }}%" role="progressbar"></div>
                                    </div>
                                    <small class="text-muted">Persentase Margin Kotor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for detailed data -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabs-product" class="nav-link active" data-bs-toggle="tab">
                                <i class="ti ti-package me-1"></i>
                                Laba per Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-outlet" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-building-store me-1"></i>
                                Laba per Outlet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-daily" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-calendar me-1"></i>
                                Laba Harian
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Product Profit Tab -->
                        <div class="tab-pane active show" id="tabs-product">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Produk</th>
                                            <th class="text-center">Qty Terjual</th>
                                            <th class="text-end">Pendapatan</th>
                                            <th class="text-end">Biaya</th>
                                            <th class="text-end">Laba Kotor</th>
                                            <th class="text-center">Margin (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($profitData['by_product'] as $product)
                                        <tr>
                                            <td><span class="badge bg-blue-lt">{{ $product['product']->code }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $product['product']->name }}</div>
                                                <div class="text-muted small">{{ $product['product']->productCategory->name ?? 'Tanpa Kategori' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-green-lt">{{ number_format($product['quantity_sold']) }} {{ $product['product']->unit }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($product['cost'], 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <span class="{{ $product['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    Rp {{ number_format($product['gross_profit'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $product['margin_percentage'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ number_format($product['margin_percentage'], 2) }}%
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="ti ti-package-off fs-1 mb-2"></i>
                                                <div>Tidak ada data produk untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Outlet Profit Tab -->
                        <div class="tab-pane" id="tabs-outlet">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="outletTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Outlet</th>
                                            <th class="text-center">Jumlah Penjualan</th>
                                            <th class="text-end">Pendapatan</th>
                                            <th class="text-end">Biaya</th>
                                            <th class="text-end">Laba Kotor</th>
                                            <th class="text-end">Laba Bersih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($profitData['by_outlet'] as $outlet)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $outlet['outlet']->name }}</div>
                                                <div class="text-muted small">{{ $outlet['outlet']->address }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue-lt">{{ number_format($outlet['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($outlet['revenue'], 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($outlet['cost'], 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <span class="{{ $outlet['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    Rp {{ number_format($outlet['gross_profit'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="{{ $outlet['net_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    Rp {{ number_format($outlet['net_profit'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="ti ti-building-store-off fs-1 mb-2"></i>
                                                <div>Tidak ada data outlet untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Daily Profit Tab -->
                        <div class="tab-pane" id="tabs-daily">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="dailyTable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-center">Jumlah Penjualan</th>
                                            <th class="text-end">Pendapatan</th>
                                            <th class="text-end">Biaya</th>
                                            <th class="text-end">Laba Kotor</th>
                                            <th class="text-end">Laba Bersih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($profitData['daily'] as $daily)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $daily['date']->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ $daily['date']->format('l') }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue-lt">{{ number_format($daily['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($daily['revenue'], 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($daily['cost'], 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <span class="{{ $daily['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    Rp {{ number_format($daily['gross_profit'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="{{ $daily['net_profit'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    Rp {{ number_format($daily['net_profit'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="ti ti-calendar-off fs-1 mb-2"></i>
                                                <div>Tidak ada data harian untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('#productTable, #outletTable, #dailyTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[5, 'desc']], // Sort by profit column
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    });

    // Export function
    function exportData(format) {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        formData.append('format', format);
        
        const params = new URLSearchParams(formData);
        window.open(`{{ route('kasir.profit-calculation.export') }}?${params.toString()}`, '_blank');
    }

    // Refresh data
    function refreshData() {
        document.getElementById('filterForm').submit();
    }

    // Auto-submit form when filters change
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(element => {
        element.addEventListener('change', function() {
            // Add small delay to prevent too frequent requests
            setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 300);
        });
    });
</script>
@endsection