@extends('admin.layouts.app')

@section('title', 'Perhitungan Jumlah Penjualan')

@section('css')
<style>
    .sales-card {
        transition: transform 0.2s ease-in-out;
    }
    .sales-card:hover {
        transform: translateY(-2px);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .summary-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Transaksi
                    </div>
                    <h2 class="page-title">
                        <i class="ti ti-sum me-2"></i>
                        Perhitungan Jumlah Penjualan
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
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
                </div>
            </div>
        </div>
    </div>

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
                                        <div class="h1 mb-1">{{ number_format($salesData['summary']['total_sales']) }}</div>
                                        <div class="text-white-50">Total Transaksi</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1">{{ number_format($salesData['summary']['total_items']) }}</div>
                                        <div class="text-white-50">Total Item Terjual</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1">Rp {{ number_format($salesData['summary']['total_revenue'], 0, ',', '.') }}</div>
                                        <div class="text-white-50">Total Pendapatan</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <div class="h1 mb-1">Rp {{ number_format($salesData['summary']['avg_transaction'], 0, ',', '.') }}</div>
                                        <div class="text-white-50">Rata-rata Transaksi</div>
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
                    <div class="card sales-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-calculator me-2"></i>
                                Ringkasan Transaksi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted">Total Pendapatan</div>
                                    <div class="h4 text-success">Rp {{ number_format($salesData['summary']['total_revenue'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Total Pajak</div>
                                    <div class="h4">Rp {{ number_format($salesData['summary']['total_tax'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Total Diskon</div>
                                    <div class="h5">Rp {{ number_format($salesData['summary']['total_discount'], 0, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Rata-rata Item/Transaksi</div>
                                    <div class="h5">{{ number_format($salesData['summary']['avg_items_per_sale'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card sales-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-credit-card me-2"></i>
                                Metode Pembayaran
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse($salesData['payment_methods'] as $payment)
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span class="badge bg-blue-lt me-2">
                                        @switch($payment['method'])
                                            @case('cash') Tunai @break
                                            @case('card') Kartu @break
                                            @case('transfer') Transfer @break
                                            @case('e_wallet') E-Wallet @break
                                            @default {{ $payment['method'] }}
                                        @endswitch
                                    </span>
                                    <span>{{ $payment['count'] }}x</span>
                                </div>
                                <div class="col-6 text-end">
                                    <strong>Rp {{ number_format($payment['total_amount'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            @empty
                            <div class="text-muted text-center">Tidak ada data pembayaran</div>
                            @endforelse
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
                                Penjualan per Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-outlet" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-building-store me-1"></i>
                                Penjualan per Outlet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-customer" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-users me-1"></i>
                                Penjualan per Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-daily" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-calendar me-1"></i>
                                Penjualan Harian
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Product Sales Tab -->
                        <div class="tab-pane active show" id="tabs-product">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Produk</th>
                                            <th class="text-center">Qty Terjual</th>
                                            <th class="text-end">Total Pendapatan</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-end">Harga Rata-rata</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salesData['by_product'] as $product)
                                        <tr>
                                            <td><span class="badge bg-blue-lt">{{ $product['product']->code }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $product['product']->name }}</div>
                                                <div class="text-muted small">{{ $product['product']->productCategory->name ?? 'Tanpa Kategori' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-green-lt">{{ number_format($product['quantity_sold']) }} {{ $product['product']->unit }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($product['total_revenue'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-yellow-lt">{{ number_format($product['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($product['avg_price'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="ti ti-package-off fs-1 mb-2"></i>
                                                <div>Tidak ada data produk untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Outlet Sales Tab -->
                        <div class="tab-pane" id="tabs-outlet">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="outletTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Outlet</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-end">Total Pendapatan</th>
                                            <th class="text-center">Total Item</th>
                                            <th class="text-end">Rata-rata Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salesData['by_outlet'] as $outlet)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $outlet['outlet']->name }}</div>
                                                <div class="text-muted small">{{ $outlet['outlet']->address }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue-lt">{{ number_format($outlet['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($outlet['total_revenue'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-green-lt">{{ number_format($outlet['total_items']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($outlet['avg_transaction'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="ti ti-building-store-off fs-1 mb-2"></i>
                                                <div>Tidak ada data outlet untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Customer Sales Tab -->
                        <div class="tab-pane" id="tabs-customer">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="customerTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Customer</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-end">Total Pendapatan</th>
                                            <th class="text-center">Total Item</th>
                                            <th class="text-end">Rata-rata Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salesData['by_customer'] as $customer)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $customer['customer']->name }}</div>
                                                <div class="text-muted small">{{ $customer['customer']->phone ?? 'Tidak ada telepon' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue-lt">{{ number_format($customer['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($customer['total_revenue'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-green-lt">{{ number_format($customer['total_items']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($customer['avg_transaction'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="ti ti-users-off fs-1 mb-2"></i>
                                                <div>Tidak ada data customer untuk periode ini</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Daily Sales Tab -->
                        <div class="tab-pane" id="tabs-daily">
                            <div class="table-responsive">
                                <table class="table table-vcenter" id="dailyTable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-end">Total Pendapatan</th>
                                            <th class="text-center">Total Item</th>
                                            <th class="text-end">Rata-rata Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salesData['daily'] as $daily)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $daily['date']->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ $daily['date']->format('l') }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-blue-lt">{{ number_format($daily['sales_count']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($daily['total_revenue'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-green-lt">{{ number_format($daily['total_items']) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($daily['avg_transaction'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
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
        $('#productTable, #outletTable, #customerTable, #dailyTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[2, 'desc']], // Sort by quantity/count column
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
        window.open(`{{ route('admin.sales-calculation.export') }}?${params.toString()}`, '_blank');
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