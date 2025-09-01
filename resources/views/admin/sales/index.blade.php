@extends('admin.layouts.app')

@section('title', 'Penjualan')
@section('subtitle', 'Kelola Data Penjualan')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.sales.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Tambah Penjualan
    </a>
</div>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('kasir.sales.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Outlet</label>
                        <select name="outlet_id" class="form-select">
                            <option value="">Semua Outlet</option>
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}" {{ request('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                    {{ $outlet->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Kode/Customer" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search"></i>
                            </button>
                            <a href="{{ route('kasir.sales.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sales Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Outlet</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Metode Bayar</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>
                            <div class="text-reset">{{ $sale->code }}</div>
                            <div class="text-muted">{{ $sale->created_at->format('H:i') }}</div>
                        </td>
                        <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                        <td>{{ $sale->outlet->name }}</td>
                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                        <td>
                            <div class="text-reset">{{ $sale->formatted_total_amount }}</div>
                            @if($sale->paid_amount > 0)
                                <div class="text-muted small">Bayar: {{ $sale->formatted_paid_amount }}</div>
                            @endif
                        </td>
                        <td>{{ $sale->payment_method_text }}</td>
                        <td>
                            <span class="badge {{ $sale->status_badge_class }}">{{ $sale->status_text }}</span>
                        </td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="{{ route('kasir.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                                
                                @if($sale->canBeEdited())
                                    <a href="{{ route('kasir.sales.edit', $sale) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                @endif

                                @if($sale->canBeCompleted())
                                    <form action="{{ route('kasir.sales.complete', $sale) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                onclick="return confirm('Yakin ingin menyelesaikan penjualan ini?')">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($sale->status !== 'completed')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($sale->status === 'draft')
                                                <li>
                                                    <form action="{{ route('kasir.sales.cancel', $sale) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('Yakin ingin membatalkan penjualan ini?')">
                                                            <i class="ti ti-x me-2"></i>Batalkan
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if($sale->status !== 'completed')
                                                <li>
                                                    <form action="{{ route('kasir.sales.destroy', $sale) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('Yakin ingin menghapus penjualan ini?')">
                                                            <i class="ti ti-trash me-2"></i>Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Tidak ada data penjualan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
        <div class="card-footer">
            {{ $sales->links() }}
        </div>
    @endif
</div>
@endsection