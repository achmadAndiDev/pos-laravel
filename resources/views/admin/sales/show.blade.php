@extends('admin.layouts.app')

@section('title', 'Detail Penjualan')
@section('subtitle', 'Lihat Detail Penjualan')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
    @if($sale->canBeEdited())
        <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-warning">
            <i class="ti ti-edit"></i>
            Edit
        </a>
    @endif
    @if($sale->canBeCompleted())
        <form action="{{ route('admin.sales.complete', $sale) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin menyelesaikan penjualan ini?')">
                <i class="ti ti-check"></i>
                Selesaikan
            </button>
        </form>
    @endif
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Sale Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Informasi Penjualan</h3>
                <div class="card-actions">
                    <span class="badge {{ $sale->status_badge_class }} fs-3">{{ $sale->status_text }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Kode Penjualan:</strong></td>
                                <td>{{ $sale->code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td>{{ $sale->created_at->format('H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Outlet:</strong></td>
                                <td>{{ $sale->outlet->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Customer:</strong></td>
                                <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                            </tr>
                            @if($sale->customer)
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td>{{ $sale->customer->phone ?? '-' }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>Metode Bayar:</strong></td>
                                <td>{{ $sale->payment_method_text }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat oleh:</strong></td>
                                <td>{{ $sale->created_by ?? 'System' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($sale->notes)
                    <div class="mt-3">
                        <strong>Catatan:</strong>
                        <p class="text-muted">{{ $sale->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sale Items -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Item Penjualan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th width="15%">Harga Satuan</th>
                                <th width="10%">Jumlah</th>
                                <th width="15%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleItems as $item)
                                <tr>
                                    <td>
                                        <div class="text-reset">{{ $item->product->name }}</div>
                                        <div class="text-muted small">{{ $item->product->code }}</div>
                                        @if($item->notes)
                                            <div class="text-muted small">{{ $item->notes }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $item->formatted_unit_price }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td><strong>{{ $item->formatted_total_price }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">Subtotal:</div>
                    <div class="col-6 text-end">{{ $sale->formatted_subtotal }}</div>
                </div>
                @if($sale->tax_amount > 0)
                    <div class="row mb-2">
                        <div class="col-6">Pajak:</div>
                        <div class="col-6 text-end">Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</div>
                    </div>
                @endif
                @if($sale->discount_amount > 0)
                    <div class="row mb-2">
                        <div class="col-6">Diskon:</div>
                        <div class="col-6 text-end text-danger">-Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</div>
                    </div>
                @endif
                <hr>
                <div class="row mb-3">
                    <div class="col-6"><h4>TOTAL:</h4></div>
                    <div class="col-6 text-end"><h4 class="text-primary">{{ $sale->formatted_total_amount }}</h4></div>
                </div>
                
                @if($sale->status === 'completed')
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Dibayar:</strong></div>
                        <div class="col-6 text-end"><strong>{{ $sale->formatted_paid_amount }}</strong></div>
                    </div>
                    @if($sale->change_amount > 0)
                        <div class="row">
                            <div class="col-6"><strong>Kembalian:</strong></div>
                            <div class="col-6 text-end"><strong class="text-success">{{ $sale->formatted_change_amount }}</strong></div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($sale->status !== 'completed')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi</h3>
                </div>
                <div class="card-body">
                    @if($sale->canBeCompleted())
                        <form action="{{ route('admin.sales.complete', $sale) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin menyelesaikan penjualan ini?')">
                                <i class="ti ti-check"></i>
                                Selesaikan Penjualan
                            </button>
                        </form>
                    @endif
                    
                    @if($sale->canBeEdited())
                        <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-warning w-100 mb-2">
                            <i class="ti ti-edit"></i>
                            Edit Penjualan
                        </a>
                    @endif

                    @if($sale->status === 'draft')
                        <form action="{{ route('admin.sales.cancel', $sale) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Yakin ingin membatalkan penjualan ini?')">
                                <i class="ti ti-x"></i>
                                Batalkan Penjualan
                            </button>
                        </form>
                    @endif

                    @if($sale->status !== 'completed')
                        <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Yakin ingin menghapus penjualan ini?')">
                                <i class="ti ti-trash"></i>
                                Hapus Penjualan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <!-- Print Receipt -->
        @if($sale->status === 'completed')
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary w-100" onclick="printReceipt()">
                        <i class="ti ti-printer"></i>
                        Cetak Struk
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Receipt Template (Hidden) -->
<div id="receiptTemplate" style="display: none;">
    <div style="width: 300px; font-family: monospace; font-size: 12px;">
        <div style="text-align: center; margin-bottom: 10px;">
            <h3 style="margin: 0;">{{ $sale->outlet->name }}</h3>
            <p style="margin: 0;">STRUK PENJUALAN</p>
        </div>
        
        <div style="margin-bottom: 10px;">
            <p style="margin: 0;">No: {{ $sale->code }}</p>
            <p style="margin: 0;">Tanggal: {{ $sale->sale_date->format('d/m/Y H:i') }}</p>
            <p style="margin: 0;">Kasir: {{ $sale->created_by ?? 'System' }}</p>
            @if($sale->customer)
                <p style="margin: 0;">Customer: {{ $sale->customer->name }}</p>
            @endif
        </div>
        
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        
        <div style="margin-bottom: 10px;">
            @foreach($sale->saleItems as $item)
                <div style="margin-bottom: 5px;">
                    <div>{{ $item->product->name }}</div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        
        <div style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between;">
                <span>Subtotal:</span>
                <span>{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->tax_amount > 0)
                <div style="display: flex; justify-content: space-between;">
                    <span>Pajak:</span>
                    <span>{{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($sale->discount_amount > 0)
                <div style="display: flex; justify-content: space-between;">
                    <span>Diskon:</span>
                    <span>-{{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px;">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        @if($sale->status === 'completed')
            <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
            <div style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Bayar ({{ $sale->payment_method_text }}):</span>
                    <span>{{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                </div>
                @if($sale->change_amount > 0)
                    <div style="display: flex; justify-content: space-between;">
                        <span>Kembalian:</span>
                        <span>{{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="margin: 0;">Terima kasih atas kunjungan Anda!</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function printReceipt() {
    const receiptContent = document.getElementById('receiptTemplate').innerHTML;
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Struk Penjualan - {{ $sale->code }}</title>
                <style>
                    body { margin: 0; padding: 20px; }
                    @media print {
                        body { margin: 0; padding: 0; }
                    }
                </style>
            </head>
            <body>
                ${receiptContent}
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    }
                </script>
            </body>
        </html>
    `);
    
    printWindow.document.close();
}
</script>
@endsection