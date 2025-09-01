@extends('admin.layouts.app')

@section('title', 'Laporan')
@section('subtitle', 'Laporan Penjualan')

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
    #salesTable {
        min-width: 900px; /* Minimum width untuk memastikan tabel tidak terlalu sempit */
    }
    
    #salesTable th,
    #salesTable td {
        white-space: nowrap;
        vertical-align: middle;
    }
    
    /* Kolom yang bisa dibuat lebih fleksibel */
    #salesTable th:nth-child(4), /* Outlet */
    #salesTable td:nth-child(4) {
        min-width: 120px;
        white-space: normal;
        word-wrap: break-word;
    }
    
    #salesTable th:nth-child(5), /* Customer */
    #salesTable td:nth-child(5) {
        min-width: 130px;
        white-space: normal;
        word-wrap: break-word;
    }
    
    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        #salesTable th,
        #salesTable td {
            padding: 0.5rem 0.25rem;
        }
        
        .btn-list .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .card-actions {
            font-size: 0.75rem;
        }
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
        
        .table-responsive {
            overflow: visible !important;
        }
        
        #salesTable {
            min-width: auto !important;
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
                <form method="GET" action="{{ route('kasir.sales.report') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label text-white">Outlet</label>
                            <select name="outlet_id" class="form-select">
                                <option value="">Semua Outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}" {{ request('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                        {{ $outlet->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">Metode Bayar</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Semua Metode</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="e_wallet" {{ request('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label text-white">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-light">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <input type="text" name="customer_name" class="form-control" placeholder="Nama customer..." value="{{ request('customer_name') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Penjualan</div>
                            <div class="ms-auto lh-1">
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="h1 mb-3">{{ $totalSales }}</div>
                        <div class="d-flex mb-2">
                            <div>Transaksi penjualan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Nilai</div>
                        </div>
                        <div class="h1 mb-3">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                        <div class="d-flex mb-2">
                            <div>Total nilai penjualan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Selesai</div>
                        </div>
                        <div class="h1 mb-3 text-success">{{ $completedSales }}</div>
                        <div class="d-flex mb-2">
                            <div>Penjualan selesai</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Draft</div>
                        </div>
                        <div class="h1 mb-3 text-warning">{{ $draftSales }}</div>
                        <div class="d-flex mb-2">
                            <div>Penjualan draft</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penjualan</h3>
                <div class="card-actions">
                    <span class="text-muted">{{ $sales->count() }} data ditemukan</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table" id="salesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Outlet</th>
                            <th>Customer</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $index => $sale)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="font-weight-medium">{{ $sale->code }}</div>
                                <div class="text-muted small">{{ $sale->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div>{{ $sale->sale_date->format('d/m/Y') }}</div>
                                <div class="text-muted">{{ $sale->created_at->format('H:i') }}</div>
                            </td>
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
                                <span class="badge bg-blue-lt">
                                    @switch($sale->payment_method)
                                        @case('cash')
                                            Tunai
                                            @break
                                        @case('card')
                                            Kartu
                                            @break
                                        @case('transfer')
                                            Transfer
                                            @break
                                        @case('e_wallet')
                                            E-Wallet
                                            @break
                                        @default
                                            {{ ucfirst($sale->payment_method) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $sale->status_badge_class }}">
                                    {{ $sale->status_text }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ $sale->formatted_total_amount }}</div>
                                <div class="text-muted small">{{ $sale->saleItems->count() }} item</div>
                            </td>
                            <td class="no-print">
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('kasir.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt=""></div>
                                    <p class="empty-title">Tidak ada data penjualan</p>
                                    <p class="empty-subtitle text-muted">
                                        Belum ada data penjualan yang sesuai dengan filter yang dipilih.
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
        window.open('{{ route("admin.sales.export-pdf") }}?' + params.toString(), '_blank');
    }

    function printReport() {
        // Get current filter parameters
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // Open print view in new window
        const printWindow = window.open('{{ route("admin.sales.print-report") }}?' + params.toString(), '_blank');
        printWindow.focus();
    }

    // Auto submit form when filter changes
    document.addEventListener('DOMContentLoaded', function() {
        const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.type !== 'text') { // Don't auto-submit for text inputs
                    document.getElementById('filterForm').submit();
                }
            });
        });
    });
</script>
@endsection