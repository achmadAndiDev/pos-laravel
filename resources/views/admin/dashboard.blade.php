@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan Bisnis')

@section('css')
<style>
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .stats-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .stats-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .stats-change {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .stats-change.positive {
        color: #28a745;
    }
    .stats-change.negative {
        color: #dc3545;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .avatar-icon {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.sales.report') }}" class="btn btn-outline-primary">
        <i class="ti ti-chart-line me-1"></i>
        Laporan Penjualan
    </a>
    <a href="{{ route('admin.profit-calculation.report') }}" class="btn btn-primary">
        <i class="ti ti-trending-up me-1"></i>
        Laporan Laba
    </a>
</div>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <!-- Filter Card -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
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

        <!-- Main Statistics -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-icon bg-primary text-white me-3">
                                <i class="ti ti-shopping-cart"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="stats-number text-primary">{{ number_format($dashboardData['sales']['total']) }}</div>
                                <div class="stats-label">Total Penjualan</div>
                                <div class="stats-change">
                                    <span class="text-success">{{ $dashboardData['sales']['completed'] }} selesai</span> • 
                                    <span class="text-warning">{{ $dashboardData['sales']['draft'] }} draft</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-icon bg-success text-white me-3">
                                <i class="ti ti-currency-dollar"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="stats-number text-success">Rp {{ number_format($dashboardData['sales']['revenue'], 0, ',', '.') }}</div>
                                <div class="stats-label">Total Pendapatan</div>
                                <div class="stats-change {{ $dashboardData['sales']['revenue_growth'] >= 0 ? 'positive' : 'negative' }}">
                                    <i class="ti ti-trending-{{ $dashboardData['sales']['revenue_growth'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($dashboardData['sales']['revenue_growth']), 1) }}% vs bulan lalu
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-icon bg-warning text-white me-3">
                                <i class="ti ti-trending-up"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="stats-number text-warning">Rp {{ number_format($dashboardData['profit']['gross'], 0, ',', '.') }}</div>
                                <div class="stats-label">Laba Kotor</div>
                                <div class="stats-change">
                                    Margin: {{ number_format($dashboardData['profit']['margin'], 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-icon bg-info text-white me-3">
                                <i class="ti ti-package"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="stats-number text-info">{{ number_format($dashboardData['products']['total']) }}</div>
                                <div class="stats-label">Total Produk</div>
                                <div class="stats-change">
                                    <span class="text-success">{{ $dashboardData['products']['active'] }} aktif</span>
                                    @if($dashboardData['products']['low_stock'] > 0)
                                        • <span class="text-danger">{{ $dashboardData['products']['low_stock'] }} stok rendah</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Statistics -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number text-blue">{{ $dashboardData['sales']['today'] }}</div>
                        <div class="stats-label">Penjualan Hari Ini</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number text-green">{{ number_format($dashboardData['customers']['total']) }}</div>
                        <div class="stats-label">Total Customer</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number text-orange">{{ number_format($dashboardData['purchases']['total']) }}</div>
                        <div class="stats-label">Total Pembelian</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number text-red">Rp {{ number_format($dashboardData['purchases']['amount'], 0, ',', '.') }}</div>
                        <div class="stats-label">Nilai Pembelian</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Sales Chart -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tren Penjualan 7 Hari Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Produk Terlaris</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($dashboardData['top_products'] as $index => $product)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $product->name }}</div>
                                        <div class="text-muted small">{{ $product->code }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="font-weight-medium">{{ $product->total_sold }} terjual</div>
                                        <div class="text-muted small">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item text-center text-muted">
                                Tidak ada data produk
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Penjualan Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Outlet</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dashboardData['recent_sales'] as $sale)
                        <tr>
                            <td>
                                <div class="font-weight-medium">{{ $sale->code }}</div>
                                <div class="text-muted small">{{ $sale->created_at->format('H:i') }}</div>
                            </td>
                            <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                            <td>
                                <div class="font-weight-medium">{{ $sale->outlet->name }}</div>
                                <div class="text-muted small">{{ Str::limit($sale->outlet->address, 30) }}</div>
                            </td>
                            <td>
                                @if($sale->customer)
                                    <div class="font-weight-medium">{{ $sale->customer->name }}</div>
                                    <div class="text-muted small">{{ $sale->customer->phone }}</div>
                                @else
                                    <span class="text-muted">Walk-in Customer</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ $sale->formatted_total_amount }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $sale->status_badge_class }}">
                                    {{ $sale->status_text }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <img src="{{ asset('tabler/static/illustrations/undraw_void_3ggu.svg') }}" height="128" alt="">
                                    </div>
                                    <p class="empty-title">Tidak ada penjualan</p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($dashboardData['daily_sales']);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.date),
            datasets: [{
                label: 'Penjualan',
                data: salesData.map(item => item.sales),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Pendapatan (Juta)',
                data: salesData.map(item => item.revenue / 1000000),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Penjualan'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Pendapatan (Juta Rp)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

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