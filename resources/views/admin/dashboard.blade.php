@extends('admin.layouts.app')

@section('title', 'Dasbor')
@section('subtitle', 'Ringkasan Sistem')

@section('content')
<div class="row row-deck row-cards">
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-primary text-white avatar">
              <!-- Download SVG icon from http://tabler-icons.io/i/users -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
               number_format($customerCount, 0, ',', '.') }} Pelanggan
            </div>
            <div class="text-secondary">
              Pelanggan Aktif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-green text-white avatar">
              <!-- Download SVG icon from http://tabler-icons.io/i/package -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-package" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
               number_format($productCount, 0, ',', '.') }} Produk
            </div>
            <div class="text-secondary">
              Jumlah Produk
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-yellow text-white avatar">
              <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
               number_format($orderCount, 0, ',', '.') }} Pesanan
            </div>
            <div class="text-secondary">
              Total Pesanan
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-azure text-white avatar">
              <!-- Download SVG icon from http://tabler-icons.io/i/report-money -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report-money" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 17v1m0 -8v1" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              Rp  number_format($grossRevenueThisMonth, 0, ',', '.') }}
            </div>
            <div class="text-secondary">
              Omset Bulan Ini
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistik Order -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Statistik Order</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="text-center">
              <div class="h2 mb-1"> number_format($ordersTodayCount, 0, ',', '.') }}</div>
              <div class="text-secondary">Order Hari Ini</div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="text-center">
              <div class="h2 mb-1"> number_format($ordersThisMonthCount, 0, ',', '.') }}</div>
              <div class="text-secondary">Order Bulan Ini</div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 mb-3 mb-sm-0">
            <div class="text-center">
              <div class="h2 mb-1"> number_format($unpaidOrdersCount, 0, ',', '.') }}</div>
              <div class="text-secondary">Menunggu Pembayaran</div>
              <small class="text-muted">(Status Pembayaran: pending)</small>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="text-center">
              <div class="h2 mb-1"> number_format($processingOrdersCount, 0, ',', '.') }}</div>
              <div class="text-secondary">Sedang Diproses</div>
              <small class="text-muted">(Status Order: processing)</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Order Terbaru -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Order Terbaru</h3>
      </div>
      <div class="card-table table-responsive">
        <table class="table table-vcenter">
          <thead>
            <tr>
              <th>ID Order</th>
              <th>Tanggal</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status Pembayaran</th>
              <th>Status Order</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($latestOrders ?? []  as $order)
            <tr>
              <td> $order->order_id }}</td>
              <td> $order->order_date->format('d M Y') }}</td>
              <td> $order->customer['name'] ?? 'N/A' }}</td>
              <td>Rp  number_format($order->total_amount, 0, ',', '.') }}</td>
              <td><span class="badge bg- strtolower($order->payment_status ?? 'unknown') == 'paid' ? 'green' : (strtolower($order->payment_status ?? 'unknown') == 'pending' ? 'yellow' : 'red') }}"> ucfirst($order->payment_status ?? 'N/A') }}</span></td>
              <td><span class="badge bg-azure"> ucfirst($order->order_status ?? 'N/A') }}</span></td>
              <td>
                <button class="btn btn-sm btn-primary viewOrderBtn" data-id=" $order->id }}">Detail</button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center">Tidak ada order terbaru.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Order -->
<div class="modal modal-blur fade" id="viewOrderModal" tabindex="-1" role="dialog" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewOrderModalLabel">Detail Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <h3 class="mb-3">Informasi Order</h3>
          <div class="table-responsive">
            <table class="table table-vcenter card-table" id="orderInfoTable">
              <!-- Data akan diisi oleh JavaScript -->
            </table>
          </div>
        </div>
        
        <div class="mb-4">
          <h3 class="mb-3">Informasi Pengiriman</h3>
          <div class="table-responsive">
            <table class="table table-vcenter card-table" id="customerInfoTable">
              <!-- Data akan diisi oleh JavaScript -->
            </table>
          </div>
        </div>
        
        <div>
          <h3 class="mb-3">Item Produk</h3>
          <div class="table-responsive">
            <table class="table table-vcenter" id="productItemsTable">
              <thead>
                <tr>
                  <th>Nama Produk</th>
                  <th>ID Produk</th>
                  <th>Qty</th>
                  <th>Harga</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data akan diisi oleh JavaScript -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        -- <a href="#" id="updateOrderStatusBtn" class="btn btn-primary">Perbarui Status</a> --}}
      </div>
    </div>
  </div>
</div>
@endsection