@extends('admin.layouts.app')

@section('title', 'Detail Order')
@section('subtitle', 'Detail Order #' . $order->order_id)

@section('styles')
    <style>
        /* Product image styles */
        .cursor-pointer {
            cursor: pointer;
        }
        .avatar[style*="background-image"] {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .avatar[style*="background-image"]:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        
        /* Payment history styles */
        .payment-history-item {
            position: relative;
            padding-left: 20px;
        }
        .payment-history-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #206bc4;
        }
        .payment-history-item:after {
            content: '';
            position: absolute;
            left: 4px;
            top: 18px;
            width: 2px;
            height: calc(100% - 8px);
            background-color: #e6e7e9;
        }
        .payment-history-item:last-child:after {
            display: none;
        }
    </style>
@endsection

@section('right-header')
    <div class="btn-list">
        <a class="btn btn-outline-primary d-none d-sm-inline-block" href="{{ route('admin.orders.index') }}">
            <i class="ti ti-arrow-left"></i>
            Kembali ke Daftar Order
        </a>
        <a class="btn btn-warning d-none d-sm-inline-block" href="{{ route('admin.orders.edit', $order->id) }}">
            <i class="ti ti-edit"></i>
            Edit Order
        </a>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <!-- Status Card -->
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @php
                                        $statusClass = 'bg-yellow';
                                        $statusIcon = 'ti-clock';

                                        if ($order->order_status === 'ACCEPTED') {
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'ti-check';
                                        } elseif ($order->order_status === 'CANCELLED') {
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'ti-x';
                                        } elseif ($order->order_status === 'INSTALLMENT') {
                                            $statusClass = 'bg-info';
                                            $statusIcon = 'ti-package';
                                        }

                                        $paymentStatusClass = 'bg-yellow';
                                        $paymentStatusIcon = 'ti-clock';

                                        if ($order->payment_status === 'PAID') {
                                            $paymentStatusClass = 'bg-success';
                                            $paymentStatusIcon = 'ti-check';
                                        } elseif ($order->payment_status === 'INSTALLMENT') {
                                            $paymentStatusClass = 'bg-info';
                                            $paymentStatusIcon = 'ti-cash';
                                        } elseif ($order->payment_status === 'CANCELLED') {
                                            $paymentStatusClass = 'bg-danger';
                                            $paymentStatusIcon = 'ti-x';
                                        }

                                        $progressStatusClass = 'bg-yellow';
                                        $progressStatusIcon = 'ti-clock';

                                        if ($order->order_progress_status === 'NEW') {
                                            $progressStatusClass = 'bg-blue';
                                            $progressStatusIcon = 'ti-plus';
                                        } elseif ($order->order_progress_status === 'UNPROCESSED') {
                                            $progressStatusClass = 'bg-yellow';
                                            $progressStatusIcon = 'ti-hourglass';
                                        } elseif ($order->order_progress_status === 'ON PROCESS') {
                                            $progressStatusClass = 'bg-indigo';
                                            $progressStatusIcon = 'ti-loader';
                                        } elseif ($order->order_progress_status === 'PICKREQ') {
                                            $progressStatusClass = 'bg-purple';
                                            $progressStatusIcon = 'ti-truck-pickup';
                                        } elseif ($order->order_progress_status === 'NO AWB') {
                                            $progressStatusClass = 'bg-orange';
                                            $progressStatusIcon = 'ti-file-invoice';
                                        } elseif ($order->order_progress_status === 'DELIVERED') {
                                            $progressStatusClass = 'bg-success';
                                            $progressStatusIcon = 'ti-check';
                                        } elseif ($order->order_progress_status === 'UNPAID') {
                                            $progressStatusClass = 'bg-danger';
                                            $progressStatusIcon = 'ti-cash-off';
                                        }
                                    @endphp
                                    <span class="avatar avatar-lg {{ $statusClass }} text-white">
                                        <i class="ti {{ $statusIcon }}"></i>
                                    </span>
                                </div>
                                <div>
                                    <h2 class="mb-0">{{ $order->order_id }}</h2>
                                    <div class="text-muted">
                                        Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}
                                        @if ($order->created_by_name)
                                            oleh {{ $order->created_by_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6mt-3 mt-md-4">
                            <div class="row g-2">

                                <div class="col-md-4 col-sm-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar {{ $paymentStatusClass }} text-white">
                                                        <i class="ti {{ $paymentStatusIcon }}"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="text-muted">Pembayaran</div>
                                                    <div class="font-weight-medium">
                                                        @if ($order->payment_status === 'UNPAID')
                                                            Belum Dibayar
                                                        @elseif($order->payment_status === 'INSTALLMENT')
                                                            Sebagian
                                                        @elseif($order->payment_status === 'PAID')
                                                            Lunas
                                                        @elseif($order->payment_status === 'REFUNDED')
                                                            Dikembalikan
                                                        @else
                                                            {{ $order->payment_status }}
                                                        @endif
                                                    </div>
                                                </div>                                               
                                                <div class="mt-2">
                                                    <button class="btn btn-primary btn-sm w-100" id="update-payment-status-btn" type="button">
                                                        <i class="ti ti-refresh me-1"></i>
                                                        Update Status
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar {{ $statusClass }}  text-white">
                                                        <i class="ti {{ $statusIcon }}"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="text-muted">Status Order</div>
                                                    <div class="font-weight-medium">
                                                        @if ($order->order_status === 'UNPAID')
                                                            Menunggu Pembayaran
                                                        @elseif($order->order_status === 'ACCEPTED')
                                                            ACCEPTED
                                                        @elseif($order->order_status === 'SHIPPED')
                                                            Dikirim
                                                        @else
                                                            {{ $order->order_status }}
                                                        @endif
                                                    </div>
                                                    <div class="mt-2">
                                                        <button class="btn btn-primary btn-sm w-100" id="update-status-btn" type="button" {{ $order->order_status === 'ACCEPTED' ? 'disabled' : '' }}>
                                                            <i class="ti ti-refresh me-1"></i>
                                                            Update Status
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="avatar {{ $progressStatusClass }}  text-white">
                                                        <i class="ti {{ $progressStatusIcon }}"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="text-muted">Progress Order Pengiriman</div>
                                                    <div class="font-weight-medium">
                                                        @if ($order->order_progress_status === 'UNPAID')
                                                            Menunggu Pembayaran
                                                        @elseif($order->order_progress_status === 'NEW')
                                                            Baru
                                                        @elseif($order->order_progress_status === 'UNPROCESSED')
                                                            Belum Diproses
                                                        @elseif($order->order_progress_status === 'ON PROCESS')
                                                            Sedang Diproses
                                                        @elseif($order->order_progress_status === 'PICKREQ')
                                                            Permintaan Pengambilan
                                                        @elseif($order->order_progress_status === 'NO AWB')
                                                            Belum Ada Nomor Resi
                                                        @elseif($order->order_progress_status === 'DELIVERED')
                                                            Terkirim
                                                        @else
                                                            {{ $order->order_progress_status }}
                                                        @endif
                                                    </div>
                                                    <div class="mt-2">
                                                        <button class="btn btn-primary btn-sm w-100" id="update-progress-status-btn" type="button">
                                                            <i class="ti ti-refresh me-1"></i>
                                                            Update Progress
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Utama -->
        <div class="col-lg-8">
            <!-- Detail Order -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-shopping-cart me-2 text-primary"></i>
                        Detail Order
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted mb-1">ID Order</div>
                                <div class="font-weight-medium">#{{ $order->order_id }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1">Tanggal Order</div>
                                <div class="font-weight-medium">{{ $order->order_date->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted mb-1">Sumber Order</div>
                                <div class="font-weight-medium">{{ $order->orderSource->name ?? 'Website' }}</div>
                            </div>
                            @if ($order->external_id)
                                <div class="mb-3">
                                    <div class="text-muted mb-1">ID External</div>
                                    <div class="font-weight-medium">{{ $order->external_id }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted mb-1">Total Order</div>
                                <div class="font-weight-medium fs-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            </div>
                            @if ($order->shipping_cost)
                                <div class="mb-3">
                                    <div class="text-muted mb-1">Ongkos Kirim</div>
                                    <div class="font-weight-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                                </div>
                            @endif
                            @if ($order->discount_amount)
                                <div class="mb-3">
                                    <div class="text-muted mb-1">Diskon</div>
                                    <div class="font-weight-medium">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</div>
                                </div>
                            @endif
                            @if ($order->weight)
                                <div class="mb-3">
                                    <div class="text-muted mb-1">Berat (Gram)</div>
                                    <div class="font-weight-medium">{{ number_format($order->weight, 2, ',', '.') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($order->note)
                        <div class="mt-3 pt-3 border-top">
                            <div class="text-muted mb-1">Catatan Order</div>
                            <div class="font-weight-medium">{{ $order->note }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-box me-2 text-primary"></i>
                        Produk yang Dibeli
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                @endphp
                                @forelse($order->products as $product)
                                    @php
                                        $productSubtotal = $product->order_price * $product->quantity;
                                        $subtotal += $productSubtotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $thumbnailPath = $product->variation ? $product->variation->thumbnail_path : null;
                                                    $imagePath = $product->variation ? $product->variation->image_path : null;
                                                    $displayImage = $thumbnailPath ?? $imagePath ?? $product->image_path;
                                                @endphp
                                                
                                                @if($displayImage)
                                                    <a href="{{ asset('storage/' . ($product->variation && $product->variation->image_path ? $product->variation->image_path : $displayImage)) }}" 
                                                       data-fslightbox="product-images" 
                                                       class="cursor-pointer">
                                                        <span class="avatar me-2" style="background-image:url('{{ asset('storage/' . $displayImage) }}')"></span>
                                                    </a>
                                                @else
                                                    <span class="avatar me-2 bg-muted">
                                                        <i class="ti ti-box"></i>
                                                    </span>
                                                @endif
                                                
                                                <div>
                                                    <div class="font-weight-medium">
                                                        @if($product->variation && $product->variation->product)
                                                            <a href="{{ route('admin.products.edit', $product->variation->product_id) }}" target="_blank">
                                                                {{ $product->name }}
                                                            </a>
                                                        @else
                                                            {{ $product->name }}
                                                        @endif
                                                    </div>
                                                    <div class="text-muted small">
                                                        @if($product->variation)
                                                            SKU: <strong>{{ $product->variation->sku }}</strong>
                                                            @if($product->variation->size || $product->variation->color)
                                                                <br>
                                                                @if($product->variation->size) <span class="badge bg-blue-lt">Size: {{ $product->variation->size }}</span> @endif
                                                                @if($product->variation->color) <span class="badge bg-purple-lt">Color: {{ $product->variation->color }}</span> @endif
                                                            @endif
                                                        @else
                                                            SKU: {{ $product->product_meta_id }}
                                                        @endif
                                                    </div>
                                                    @if (isset($product->category) && is_array($product->category))
                                                        <div class="text-muted small">{{ $product->category['name'] ?? '' }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($product->order_price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $product->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($productSubtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">Tidak ada produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="text-end" colspan="3">Subtotal:</td>
                                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($order->shipping_cost)
                                    <tr>
                                        <td class="text-end" colspan="3">Ongkos Kirim:</td>
                                        <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($order->discount_amount)
                                    <tr>
                                        <td class="text-end" colspan="3">Diskon:</td>
                                        <td class="text-end">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-end fw-bold" colspan="3">Total:</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-cash me-2 text-primary"></i>
                        Riwayat Pembayaran
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Bank</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->paymentHistories->sortByDesc('created_at') as $payment)
                                    <tr>
                                        <td>
                                            {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : $payment->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            <div class="font-weight-medium">{{ $payment->description ?: 'Pembayaran' }}</div>
                                            @if ($payment->note || $payment->notes)
                                                <div class="text-muted small">{{ $payment->note ?: $payment->notes }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    @if(is_array($payment->bank) && isset($payment->bank['destination']['bank_name']))
                                                        {{ $payment->bank['destination']['bank_name'] }}
                                                    @elseif(is_array($payment->bank) && isset($payment->bank['name']))
                                                        {{ $payment->bank['name'] }}
                                                    @elseif($payment->bankAccount)
                                                        {{ $payment->bankAccount->bank_name }}
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                                @if(is_array($payment->bank) && (isset($payment->bank['origin']) || isset($payment->bank['destination'])))
                                                    <button class="btn btn-sm btn-outline-primary ms-2 view-payment-detail-btn" 
                                                        data-payment-id="{{ $payment->id }}" 
                                                        data-payment-bank="{{ json_encode($payment->bank) }}" 
                                                        type="button">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            Rp {{ number_format($payment->nominal ?: $payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($payment->is_rejected)
                                                <span class="badge text-white bg-danger">Ditolak</span>
                                            @elseif($payment->is_confirmed)
                                                <span class="badge text-white bg-success">Terkonfirmasi</span>
                                            @else
                                                <span class="badge text-white bg-warning">Menunggu Konfirmasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$payment->is_confirmed && !$payment->is_rejected)
                                                <div class="btn-group">
                                                    <button class="btn btn-success btn-sm confirm-payment-btn" 
                                                        data-payment-id="{{ $payment->id }}" 
                                                        data-order-id="{{ $order->id }}" 
                                                        type="button">
                                                        <i class="ti ti-check me-1"></i> Konfirmasi
                                                    </button>
                                                    <button class="btn btn-danger btn-sm reject-payment-btn" 
                                                        data-payment-id="{{ $payment->id }}" 
                                                        data-order-id="{{ $order->id }}" 
                                                        type="button">
                                                        <i class="ti ti-x me-1"></i> Tolak
                                                    </button>
                                                </div>
                                            @elseif($payment->is_rejected)
                                                <button class="btn btn-outline-success btn-sm confirm-payment-btn" 
                                                    data-payment-id="{{ $payment->id }}" 
                                                    data-order-id="{{ $order->id }}" 
                                                    type="button">
                                                    <i class="ti ti-check me-1"></i> Konfirmasi
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada riwayat pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Riwayat Status dan Pembayaran -->
            {{-- TODO: Sementara Order History, di hide --}}
            <div class="card d-none">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-history me-2 text-primary"></i>
                        Riwayat Order
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <!-- Riwayat Pembuatan Order -->
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-primary">
                                {{-- <i class="ti ti-shopping-cart"></i> --}}
                            </div>
                            <div class="timeline-event-card">
                                <div class="card-body">
                                    <div class="text-muted float-end">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                    <h4>Order Dibuat</h4>
                                    <p class="text-muted">Order telah dibuat{{ $order->created_by_name ? ' oleh ' . $order->created_by_name : '' }}.</p>
                                </div>
                            </div>
                        </li>

                        <!-- Riwayat Pembayaran -->
                        @foreach ($order->paymentHistories->sortBy('created_at') as $payment)
                            <li class="timeline-event">
                                <div class="timeline-event-icon {{ $payment->is_confirmed ? 'bg-success' : 'bg-yellow' }}">
                                    <i class="ti ti-cash"></i>
                                </div>
                                <div class="timeline-event-card">
                                    <div class="card-body">
                                        <div class="text-muted float-end">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                                        <h4>{{ $payment->is_confirmed ? 'Pembayaran Dikonfirmasi' : 'Pembayaran Diterima' }}</h4>
                                        <p class="text-muted">
                                            Nominal: Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                            @if ($payment->description)
                                                <br>{{ $payment->description }}
                                            @endif
                                            @if ($payment->note)
                                                <br>Catatan: {{ $payment->note }}
                                            @endif
                                        </p>

                                        @if (!$payment->is_confirmed)
                                            <div class="mt-3">
                                                <button class="btn btn-success btn-sm confirm-payment-btn" data-payment-id="{{ $payment->id }}" data-order-id="{{ $order->id }}" type="button">
                                                    <i class="ti ti-check me-1"></i> Konfirmasi Pembayaran
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        <!-- Status Order Terakhir -->
                        @if ($order->updated_at->gt($order->created_at))
                            <li class="timeline-event">
                                <div
                                    class="timeline-event-icon 
              @if ($order->order_status === 'COMPLETED') bg-success
              @elseif($order->order_status === 'CANCELLED')
                bg-danger
              @elseif(in_array($order->order_status, ['PROCESSING', 'SHIPPED']))
                bg-info
              @else
                bg-yellow @endif
            ">
                                    <i class="ti ti-check"></i>
                                </div>
                                <div class="timeline-event-card">
                                    <div class="card-body">
                                        <div class="text-muted float-end">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                                        <h4>Status Diperbarui</h4>
                                        <p class="text-muted">
                                            Status order diperbarui menjadi <strong>{{ $order->order_status }}</strong>
                                            @if ($order->updated_by_name)
                                                oleh {{ $order->updated_by_name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan -->
        <div class="col-lg-4">
            <!-- Informasi Customer -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-user me-2 text-primary"></i>
                        Informasi Customer
                    </h3>
                    @if ($order->customer_id)
                        <div class="card-actions">
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.customers.show', $order->customer_id) }}/edit" target="_blank">
                                <i class="ti ti-user me-1"></i>
                                Lihat Profil
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted mb-1">Nama</div>
                        <div class="font-weight-medium">{{ $order->customer['name'] ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted mb-1">Email</div>
                        <div class="font-weight-medium">{{ $order->customer['email'] ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted mb-1">Telepon</div>
                        <div class="font-weight-medium">{{ $order->customer['phone'] ?? 'N/A' }}</div>
                    </div>
                    @if ($order->customer_id)
                        <div class="mb-3">
                            <div class="text-muted mb-1">ID Customer</div>
                            <div class="font-weight-medium">{{ $order->customer_id }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-map-pin me-2 text-primary"></i>
                        Alamat Pengiriman
                    </h3>
                </div>
                <div class="card-body">
                    @if (isset($order->receiver) && is_array($order->receiver))
                        <address>
                            <strong>{{ $order->receiver['name'] ?? ($order->customer['name'] ?? 'N/A') }}</strong><br>
                            {{ $order->receiver['address'] ?? 'Alamat tidak tersedia' }}<br>
                            @if (isset($order->receiver['city']) || isset($order->receiver['postal_code']))
                                {{ $order->receiver['city'] ?? '' }}{{ isset($order->receiver['postal_code']) ? ', ' . $order->receiver['postal_code'] : '' }}<br>
                            @endif
                            <abbr title="Phone">Telepon:</abbr> {{ $order->receiver['phone'] ?? ($order->customer['phone'] ?? 'N/A') }}
                        </address>
                    @else
                        <address>
                            <strong>{{ $order->customer['name'] ?? 'N/A' }}</strong><br>
                            {{ $order->customer['address'] ?? 'Alamat tidak tersedia' }}<br>
                            @if (isset($order->customer['city']) || isset($order->customer['postal_code']))
                                {{ $order->customer['city'] ?? '' }}{{ isset($order->customer['postal_code']) ? ', ' . $order->customer['postal_code'] : '' }}<br>
                            @endif
                            <abbr title="Phone">Telepon:</abbr> {{ $order->customer['phone'] ?? 'N/A' }}
                        </address>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">

                    <h3 class="card-title">
                        <i class="ti ti-map-pin me-2 text-primary"></i>
                        Resi Pengiriman
                    </h3>
                    <div class="card-actions">
                        <button class="btn btn-outline-primary btn-sm" type="button" onclick="showUpdateTrackingModal({{ $order->id }})">
                            <i class="ti ti-edit me-1"></i>
                            Update
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="">
                        {{-- <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="text-muted">Status Pengiriman</div>
            <div>
              @php
                $shippingStatusClass = 'bg-yellow';
                if (in_array($order->order_status, ['SHIPPED', 'DELIVERED'])) {
                    $shippingStatusClass = 'bg-info';
                } elseif ($order->order_status === 'COMPLETED') {
                    $shippingStatusClass = 'bg-success';
                }
              @endphp
              <span class="badge text-white {{ $shippingStatusClass }}">
                @if (in_array($order->order_status, ['PENDING_PAYMENT', 'PAYMENT_CONFIRMED']))
                  Menunggu Diproses
                @elseif($order->order_status === 'PROCESSING')
                  Sedang Diproses
                @elseif($order->order_status === 'SHIPPED')
                  Dikirim
                @elseif($order->order_status === 'DELIVERED')
                  Terkirim
                @elseif($order->order_status === 'COMPLETED')
                  Selesai
                @else
                  {{ $order->order_status }}
                @endif
              </span>
            </div>
          </div> --}}

                        @if ($order->awb_number)
                            <div class="mb-2">
                                <div class="text-muted mb-1">Nomor Resi</div>
                                <div class="font-weight-medium">{{ $order->shipping_logistic ? $order->shipping_logistic . ' - ' : '' }} {{ $order->awb_number }}</div>
                            </div>
                        @endif

                        @if ($order->awb_updated_at)
                            <div class="mb-2">
                                <div class="text-muted mb-1">Tanggal Pengiriman</div>
                                <div class="font-weight-medium">{{ $order->awb_updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-cash me-2 text-primary"></i>
                        Informasi Pembayaran
                    </h3>
                    {{-- <div class="card-actions">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" type="button">
                            <i class="ti ti-check me-1"></i>
                            Konfirmasi
                        </button>
                    </div> --}}
                </div>
                <div class="card-body">
                    @if ($order->paymentHistories->isNotEmpty())
                        @php
                            $latestPayment = $order->paymentHistories->sortByDesc('created_at')->first();
                            $confirmedPayments = $order->paymentHistories->where('is_confirmed', true);
                            $totalPaid = $confirmedPayments->sum('nominal');
                            $remainingAmount = $order->total_amount - $totalPaid;
                        @endphp
                        <div class="mb-3">
                            <div class="text-muted mb-1">Status Pembayaran</div>
                            <div class="font-weight-medium">
                                @php
                                    $paymentStatusClass = 'bg-yellow';
                                    if ($order->payment_status === 'PAID') {
                                        $paymentStatusClass = 'bg-success';
                                    } elseif ($order->payment_status === 'INSTALLMENT') {
                                        $paymentStatusClass = 'bg-info';
                                    } elseif ($order->payment_status === 'CANCELLED') {
                                        $paymentStatusClass = 'bg-danger';
                                    }
                                @endphp
                                <span class="badge text-white {{ $paymentStatusClass }}">{{ $order->payment_status }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted mb-1">Total Dibayar</div>
                            <div class="font-weight-medium">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted mb-1">Sisa Pembayaran</div>
                            <div class="font-weight-medium">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</div>
                        </div>
                        {{-- <div class="mb-3">
            <div class="text-muted mb-1">Metode Pembayaran Terakhir</div>
            <div class="font-weight-medium">{{ $latestPayment->type ?? 'Transfer Bank' }}</div>
          </div> --}}
                        {{-- @if (isset($latestPayment->bank) && is_array($latestPayment->bank))
                            <div class="mb-3">
                                <div class="text-muted mb-1">Bank</div>
                                <div class="font-weight-medium">{{ $latestPayment->bank['name'] ?? 'N/A' }}</div>
                            </div>
                        @endif --}}
                        @if ($latestPayment->paid_at)
                            <div class="mb-3">
                                <div class="text-muted mb-1">Tanggal Pembayaran Terakhir</div>
                                <div class="font-weight-medium">{{ $latestPayment->paid_at->format('d M Y, H:i') }}</div>
                            </div>
                        @endif
                    @else
                        <div class="mb-3">
                            <div class="text-muted mb-1">Status Pembayaran</div>
                            <div class="font-weight-medium"><span class="badge text-white bg-yellow">Menunggu Pembayaran</span></div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted mb-1">Total Tagihan</div>
                            <div class="font-weight-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted mb-1">Batas Waktu</div>
                            <div class="font-weight-medium">{{ $order->order_date->addDays(1)->format('d M Y, H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Catatan Admin -->
            {{-- <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="ti ti-notes me-2 text-primary"></i>
          Catatan Admin
        </h3>
      </div>
      <div class="card-body">
        <form id="addNoteForm">
          <div class="mb-3">
            <textarea class="form-control" id="noteText" name="note" rows="3" placeholder="Tambahkan catatan untuk order ini...">{{ $order->note }}</textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary" id="addNoteBtn">
              <i class="ti ti-plus me-1"></i>
              Simpan Catatan
            </button>
          </div>
        </form>
      </div>
    </div> --}}
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <!-- Modal Update Status -->
    <div class="modal modal-blur fade" id="updateStatusModal" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Status Order</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStatusForm" action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-info-circle me-2"></i>
                                </div>
                                <div>
                                    Status order saat ini: <strong>{{ $order->order_status }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status Order</label>
                            <select class="form-select" name="order_status" required>
                                <option value="PENDING_PAYMENT" {{ $order->order_status === 'PENDING_PAYMENT' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="ACCEPTED" {{ $order->order_status === 'ACCEPTED' ? 'selected' : '' }}>Diterima</option>
                                <option value="PROCESSING" {{ $order->order_status === 'PROCESSING' ? 'selected' : '' }}>Diproses</option>
                                <option value="SHIPPED" {{ $order->order_status === 'SHIPPED' ? 'selected' : '' }}>Dikirim</option>
                                <option value="DELIVERED" {{ $order->order_status === 'DELIVERED' ? 'selected' : '' }}>Diterima</option>
                                <option value="COMPLETED" {{ $order->order_status === 'COMPLETED' ? 'selected' : '' }}>Selesai</option>
                                <option value="CANCELLED" {{ $order->order_status === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="status_notes" rows="3" placeholder="Tambahkan catatan untuk perubahan status ini..."></textarea>
                        </div>

                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input" id="notifyCustomer" name="notify_customer" type="checkbox" checked>
                            <label class="form-check-label" for="notifyCustomer">
                                Kirim notifikasi ke customer
                            </label>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-primary" id="saveStatusBtn" type="button">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembayaran -->
    <div class="modal modal-blur fade" id="confirm-payment-modal" role="dialog" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="confirm-payment-form">
                        <input id="payment-id" name="payment_id" type="hidden">
                        <input id="payment-order-id" name="order_id" type="hidden">

                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-info-circle me-2"></i>
                                </div>
                                <div>
                                    Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="payment-note" name="note" rows="3" placeholder="Tambahkan catatan untuk pembayaran ini..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-success" id="confirm-payment-btn" type="button">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Status Pembayaran -->
    <div class="modal modal-blur fade" id="update-payment-status-modal" role="dialog" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pembayaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-payment-status-form">
                        <input id="payment-status-order-id" name="order_id" type="hidden" value="{{ $order->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label required">Status Pembayaran</label>
                            <select class="form-select" id="payment-status" name="payment_status" required>
                                <option value="UNPAID" {{ $order->payment_status === 'UNPAID' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="INSTALLMENT" {{ $order->payment_status === 'INSTALLMENT' ? 'selected' : '' }}>Sebagian</option>
                                <option value="PAID" {{ $order->payment_status === 'PAID' ? 'selected' : '' }}>Lunas</option>
                                <option value="REFUNDED" {{ $order->payment_status === 'REFUNDED' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" id="payment-status-note" name="note" rows="3" placeholder="Tambahkan catatan untuk perubahan status pembayaran ini..."></textarea>
                        </div>

                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input" id="notifyCustomerPayment" name="notify_customer" type="checkbox" checked>
                            <label class="form-check-label" for="notifyCustomerPayment">
                                Kirim notifikasi ke customer
                            </label>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-primary" id="save-payment-status-btn" type="button">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    <div class="modal modal-blur fade" id="payment-detail-modal" role="dialog" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembayaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Pengirim</h4>
                            <div class="mb-2">
                                <div class="text-muted">Nama Akun</div>
                                <div class="font-weight-medium" id="payment-account-name">-</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted">Nomor Rekening</div>
                                <div class="font-weight-medium" id="payment-account-number">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Tujuan</h4>
                            <div class="mb-2">
                                <div class="text-muted">Bank</div>
                                <div class="font-weight-medium" id="payment-bank-name">-</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4" id="payment-image-container">
                        <h4 class="mb-3">Bukti Transfer</h4>
                        <div class="text-center">
                            <img id="payment-transfer-image" src="" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    </div>
                    
                    <div class="mt-4 d-none" id="no-payment-image">
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-info-circle me-2"></i>
                                </div>
                                <div>
                                    Tidak ada bukti transfer yang diunggah.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Resi Pengiriman -->
    <div class="modal modal-blur fade" id="update-tracking-modal" role="dialog" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Resi Pengiriman</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="order-info mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div><strong>Nomor PO:</strong> <span id="tracking-modal-po-number">{{ $order->order_id }}</span></div>
                                <div><strong>Customer:</strong> <span id="tracking-modal-customer-name">{{ $order->customer->name ?? 'Customer' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <form id="update-tracking-form">
                        <input id="tracking-order-id" name="order_id" type="hidden" value="{{ $order->id }}">

                        <div class="mb-3">
                            <label class="form-label required">Logistik Pengiriman</label>
                            <select class="form-select" id="shipping-logistic" name="shipping_logistic" required>
                                <option value="">Pilih Logistik</option>
                                @foreach ($expeditions as $expedition)
                                    <option value="{{ $expedition->id }}" {{ $order->shipping_id == $expedition->id ? 'selected' : '' }}>
                                        {{ $expedition->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Nomor Resi</label>
                            <input class="form-control" id="awb-number" name="awb_number" type="text" value="{{ $order->awb_number ?? '' }}" placeholder="Masukkan nomor resi pengiriman" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-primary" id="save-tracking-btn" type="button">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembayaran -->
    <div class="modal modal-blur fade" id="confirmPaymentModal" role="dialog" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmPaymentModalLabel">Konfirmasi Pembayaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="confirmPaymentForm" action=" route('admin.orders.update-payment', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-info-circle me-2"></i>
                                </div>
                                <div>
                                    Status pembayaran saat ini: <strong>{{ $order->payment_status }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status Pembayaran</label>
                            <select class="form-select" name="payment_status" required>
                                <option value="UNPAID" {{ $order->payment_status === 'UNPAID' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="PAID" {{ $order->payment_status === 'PAID' ? 'selected' : '' }}>Lunas</option>
                                <option value="INSTALLMENT" {{ $order->payment_status === 'INSTALLMENT' ? 'selected' : '' }}>Sebagian</option>
                                <option value="REFUNDED" {{ $order->payment_status === 'REFUNDED' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Tanggal Pembayaran</label>
                            <input class="form-control" name="payment_date" type="date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Jumlah Pembayaran</label>
                            <input class="form-control" name="payment_amount" type="number" value="{{ $order->total_amount }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="transfer" selected>Transfer Bank</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                                <option value="credit_card">Kartu Kredit/Debit</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Pembayaran</label>
                            <textarea class="form-control" name="payment_notes" rows="3" placeholder="Tambahkan catatan untuk pembayaran..."></textarea>
                        </div>

                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input" id="notifyCustomerPayment" name="notify_customer" type="checkbox" checked>
                            <label class="form-check-label" for="notifyCustomerPayment">
                                Kirim notifikasi ke customer
                            </label>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button class="btn btn-primary" id="savePaymentBtn" type="button">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .modal-header {
            background-color: var(--tblr-primary);
            color: white;
        }

        .badge {
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .timeline {
            position: relative;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .timeline-event {
            position: relative;
            padding-bottom: 1.5rem;
            padding-left: 3rem;
        }

        .timeline-event:last-child {
            padding-bottom: 0;
        }

        .timeline-event:before {
            content: "";
            position: absolute;
            left: 0.85rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-event:last-child:before {
            bottom: 50%;
        }

        .timeline-event-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            text-align: center;
            font-size: 0.75rem;
            line-height: 1.75rem;
            color: #fff;
        }

        .timeline-event-card {
            margin-bottom: 0;
        }

        /* Card styling */
        .card-header .card-title {
            margin-bottom: 0;
        }

        .card-header .card-title i {
            opacity: 0.8;
        }

        .font-weight-medium {
            font-weight: 500;
        }

        .text-muted {
            font-size: 0.875rem;
        }

        .timeline-event-icon {
            position: absolute;
            left: 0;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            color: #fff;
            font-size: 0.75rem;
        }

        .timeline-event-card {
            margin-bottom: 0;
        }

        .datagrid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 0.5rem 1rem;
        }

        .datagrid-title {
            font-weight: 500;
            color: #6c757d;
        }

        .datagrid-content {
            font-weight: 400;
        }
    </style>
@endsection

<!-- Modal Update Status Order -->
<div class="modal fade" id="update-status-modal" role="dialog" aria-labelledby="update-status-modal-label" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update-status-modal-label">Update Status Order</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-status-form">
                    <input id="status-order-id" type="hidden" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label class="form-label" for="order-status">Status Order</label>
                        <select class="form-select" id="order-status" required>
                            <option value="">Pilih Status</option>
                            <option value="UNPAID" {{ $order->order_status === 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                            <option value="INSTALLMENT" {{ $order->order_status === 'INSTALLMENT' ? 'selected' : '' }}>INSTALLMENT</option>
                            <option value="ACCEPTED" {{ $order->order_status === 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status-note">Catatan (Opsional)</label>
                        <textarea class="form-control" id="status-note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                <button class="btn btn-primary" id="save-status-btn" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Progress Status Order -->
<div class="modal fade" id="update-progress-status-modal" role="dialog" aria-labelledby="update-progress-status-modal-label" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update-progress-status-modal-label">Update Progress Status Order</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-progress-status-form">
                    <input id="progress-status-order-id" type="hidden" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label class="form-label" for="order-progress-status">Progress Status Order</label>
                        <select class="form-select" id="order-progress-status" required>
                            <option value="">Pilih Progress Status</option>
                            <option value="ON PROCESS" {{ $order->order_progress_status === 'ON PROCESS' ? 'selected' : '' }}>ON PROCESS</option>
                            <option value="NO AWB" {{ $order->order_progress_status === 'NO AWB' ? 'selected' : '' }}>NO AWB</option>
                            <option value="UNPAID" {{ $order->order_progress_status === 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                            <option value="DELIVERED" {{ $order->order_progress_status === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                            <option value="UNPROCESSED" {{ $order->order_progress_status === 'UNPROCESSED' ? 'selected' : '' }}>UNPROCESSED</option>
                            <option value="PICKREQ" {{ $order->order_progress_status === 'PICKREQ' ? 'selected' : '' }}>PICKREQ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="progress-status-note">Catatan (Opsional)</label>
                        <textarea class="form-control" id="progress-status-note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                <button class="btn btn-primary" id="save-progress-status-btn" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <!-- FSLightbox for image preview -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
    
    <script>
        // Fungsi untuk menampilkan modal update resi
        function showUpdateTrackingModal(orderId) {
            // Tampilkan loading
            showLoading();

            // Ambil data order
            $.ajax({
                url: `/api/orders/${orderId}`,
                method: 'GET',
                success: function(response) {
                    // Sembunyikan loading
                    hideLoading();

                    if (response.success) {
                        const order = response.data;

                        // Isi data order ke modal
                        $('#tracking-modal-po-number').text(order.po_number);
                        $('#tracking-modal-customer-name').text(order.customer.name);
                        $('#tracking-order-id').val(order.id);

                        // Isi data resi jika sudah ada
                        if (order.awb_number) {
                            $('#awb-number').val(order.awb_number);
                        } else {
                            $('#awb-number').val('');
                        }

                        console.log(order);
                        if (order.shipping_id) {
                            $('#shipping-logistic').val(order.shipping_id).trigger('change');
                        } else {
                            $('#shipping-logistic').val('');
                        }

                        // Tampilkan modal
                        $('#update-tracking-modal').modal('show');
                    } else {
                        showToast('error', 'Gagal mengambil data order');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showToast('error', 'Terjadi kesalahan saat mengambil data order');
                    console.error(xhr);
                }
            });
        }
        // Fungsi untuk menyimpan nomor resi
        function saveTrackingNumber() {
            // Validasi form
            const form = document.getElementById('update-tracking-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Ambil data form
            const orderId = document.getElementById('tracking-order-id').value;
            const awbNumber = document.getElementById('awb-number').value;
            const shippingLogistic = document.getElementById('shipping-logistic').value;

            // Tampilkan loading
            document.body.classList.add('modal-open');

            // Kirim data ke server
            fetch(`/api/orders/${orderId}/tracking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        awb_number: awbNumber,
                        shipping_logistic: shippingLogistic
                    })
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('update-tracking-modal'));
                        modal.hide();

                        // Tampilkan pesan sukses
                        alert('Nomor resi berhasil diperbarui');

                        // Reload halaman untuk melihat perubahan
                        window.location.reload();
                    } else {
                        alert(response.message || 'Gagal memperbarui nomor resi');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan saat memperbarui nomor resi');
                })
                .finally(() => {
                    document.body.classList.remove('modal-open');
                });
        }




        function showToast(type, message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: type,
            });
        }

        // Fungsi untuk menampilkan loading
        function showLoading() {
            $('#loading-indicator').removeClass('d-none');
            $('#order-list').addClass('d-none');
            $('#empty-state').addClass('d-none');
        }

        // Fungsi untuk menyembunyikan loading
        function hideLoading() {
            $('#loading-indicator').addClass('d-none');
            $('#order-list').removeClass('d-none');
        }

        // Fungsi untuk menampilkan empty state
        function showEmptyState() {
            $('#empty-state').removeClass('d-none');
            $('#order-list').addClass('d-none');
        }

        // Fungsi untuk menyembunyikan empty state
        function hideEmptyState() {
            $('#empty-state').addClass('d-none');
            $('#order-list').removeClass('d-none');
        }

        // Konfirmasi Pembayaran
        $(document).on('click', '.confirm-payment-btn', function() {
            const paymentId = $(this).data('payment-id');
            const orderId = $(this).data('order-id');

            // Set nilai ke form
            $('#payment-id').val(paymentId);
            $('#payment-order-id').val(orderId);

            // Tampilkan modal
            $('#confirm-payment-modal').modal('show');
        });

        // Proses konfirmasi pembayaran
        $('#confirm-payment-btn').on('click', function() {
            const paymentId = $('#payment-id').val();
            const orderId = $('#payment-order-id').val();
            const note = $('#payment-note').val();

            // Kirim request ke server
            $.ajax({
                url: "{{ route('admin.orders.confirm-payment', $order->id) }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_id: paymentId,
                    is_confirmed: true,
                    note: note
                },
                success: function(response) {
                    if (response.success) {
                        // Tutup modal
                        $('#confirm-payment-modal').modal('hide');

                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil dikonfirmasi',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload halaman
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal mengkonfirmasi pembayaran',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengkonfirmasi pembayaran',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        // Event listener untuk tombol update status pembayaran
        $('#update-payment-status-btn').on('click', function() {
            // Tampilkan modal update status pembayaran
            $('#update-payment-status-modal').modal('show');
        });
        
        // Event listener untuk tombol simpan status pembayaran
        $('#save-payment-status-btn').on('click', function() {
            // Validasi form
            const form = document.getElementById('update-payment-status-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Ambil data form
            const orderId = $('#payment-status-order-id').val();
            const paymentStatus = $('#payment-status').val();
            const note = $('#payment-status-note').val();
            const notifyCustomer = $('#notifyCustomerPayment').is(':checked');
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui status pembayaran',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Kirim data ke server
            $.ajax({
                url: "{{ route('admin.orders.update-payment-status', $order->id) }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_status: paymentStatus,
                    note: note,
                    notify_customer: notifyCustomer
                },
                success: function(response) {
                    if (response.success) {
                        // Tutup modal
                        $('#update-payment-status-modal').modal('hide');
                        
                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Status pembayaran berhasil diperbarui',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload halaman
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal memperbarui status pembayaran',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui status pembayaran',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        
        // Event listener untuk tombol detail pembayaran
        $(document).on('click', '.view-payment-detail-btn', function() {
            // Ambil data dari atribut data
            const paymentBank = $(this).data('payment-bank');
            
            // Reset modal
            $('#payment-account-name').text('-');
            $('#payment-account-number').text('-');
            $('#payment-bank-name').text('-');
            $('#payment-image-container').addClass('d-none');
            $('#no-payment-image').addClass('d-none');
            
            // Isi data ke modal
            if (paymentBank) {
                // Informasi pengirim
                if (paymentBank.origin) {
                    if (paymentBank.origin.account_name) {
                        $('#payment-account-name').text(paymentBank.origin.account_name);
                    }
                    
                    if (paymentBank.origin.account_number) {
                        $('#payment-account-number').text(paymentBank.origin.account_number);
                    }
                    
                    // Tampilkan bukti transfer jika ada
                    if (paymentBank.origin.transfer_image) {
                        const imageUrl = paymentBank.origin.transfer_image.startsWith('http') 
                            ? paymentBank.origin.transfer_image 
                            : '/storage/' + paymentBank.origin.transfer_image;
                        
                        $('#payment-transfer-image').attr('src', imageUrl);
                        $('#payment-image-container').removeClass('d-none');
                    } else {
                        $('#no-payment-image').removeClass('d-none');
                    }
                } else {
                    $('#no-payment-image').removeClass('d-none');
                }
                
                // Informasi tujuan
                if (paymentBank.destination) {
                    if (paymentBank.destination.bank_name) {
                        $('#payment-bank-name').text(paymentBank.destination.bank_name);
                    }
                }
            }
            
            // Tampilkan modal
            $('#payment-detail-modal').modal('show');
        });

        // Event listener untuk tombol update status
        $('#update-status-btn').on('click', function() {
            // Tampilkan modal update status
            $('#update-status-modal').modal('show');
        });

        // Event listener untuk tombol update progress status
        $('#update-progress-status-btn').on('click', function() {
            // Cek apakah status order adalah ACCEPTED
            // @@@if ($order->order_status === 'ACCEPTED')
                // Tampilkan modal update progress status
                $('#update-progress-status-modal').modal('show');
            // @@@else
                // Tampilkan pesan error
                // Swal.fire({
                //     title: 'Perhatian!',
                //     text: 'Status order harus ACCEPTED untuk dapat mengupdate progress status',
                //     icon: 'warning',
                //     confirmButtonText: 'OK'
                // });
            // @@@endif
        });

        // Event listener untuk tombol simpan status
        $('#save-status-btn').on('click', function() {
            // Validasi form
            const form = document.getElementById('update-status-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Ambil data form
            const orderId = $('#status-order-id').val();
            const orderStatus = $('#order-status').val();
            const note = $('#status-note').val();

            // Tampilkan loading
            showLoading();

            // Kirim data ke server
            $.ajax({
                url: "{{ route('admin.orders.update-status', $order->id) }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    order_status: orderStatus,
                    note: note
                },
                success: function(response) {
                    // Sembunyikan loading
                    hideLoading();

                    if (response.success) {
                        // Tutup modal
                        $('#update-status-modal').modal('hide');

                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Status order berhasil diperbarui',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload halaman
                            window.location.reload();
                        });

                        // Jika status berubah menjadi ACCEPTED, aktifkan tombol update progress status
                        if (orderStatus === 'ACCEPTED') {
                            $('#update-progress-status-btn').prop('disabled', false);
                        } else {
                            $('#update-progress-status-btn').prop('disabled', true);
                        }
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal memperbarui status order',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // Sembunyikan loading
                    hideLoading();

                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui status order',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Event listener untuk tombol simpan progress status
        $('#save-progress-status-btn').on('click', function() {
            // Validasi form
            const form = document.getElementById('update-progress-status-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Ambil data form
            const orderId = $('#progress-status-order-id').val();
            const orderProgressStatus = $('#order-progress-status').val();
            const note = $('#progress-status-note').val();

            // Tampilkan loading
            showLoading();

            // Kirim data ke server
            $.ajax({
                url: "{{ route('admin.orders.update-progress-status', $order->id) }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    order_progress_status: orderProgressStatus,
                    note: note
                },
                success: function(response) {
                    // Sembunyikan loading
                    hideLoading();

                    if (response.success) {
                        // Tutup modal
                        $('#update-progress-status-modal').modal('hide');

                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Progress status order berhasil diperbarui',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload halaman
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal memperbarui progress status order',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // Sembunyikan loading
                    hideLoading();

                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui progress status order',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        // Reject Pembayaran
        $(document).on('click', '.reject-payment-btn', function() {
            const paymentId = $(this).data('payment-id');
            const orderId = $(this).data('order-id');

            // Set nilai ke form
            $('#reject-payment-id').val(paymentId);
            $('#reject-payment-order-id').val(orderId);

            // Tampilkan modal
            $('#reject-payment-modal').modal('show');
        });

        // Proses reject pembayaran
        $('#reject-payment-btn').on('click', function() {
            const paymentId = $('#reject-payment-id').val();
            const orderId = $('#reject-payment-order-id').val();
            const note = $('#reject-payment-note').val();

            // Kirim request ke server
            $.ajax({
                url: `/admin/payment-histories/${paymentId}/reject`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    note: note
                },
                success: function(response) {
                    if (response.success) {
                        // Tutup modal
                        $('#reject-payment-modal').modal('hide');

                        // Tampilkan pesan sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil ditolak',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Reload halaman
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal menolak pembayaran',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menolak pembayaran',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection

<!-- Modal Reject Pembayaran -->
<div class="modal modal-blur fade" id="reject-payment-modal" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pembayaran</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reject-payment-form">
                    <input type="hidden" id="reject-payment-id" name="payment_id">
                    <input type="hidden" id="reject-payment-order-id" name="order_id">
                    <div class="mb-3">
                        <label class="form-label" for="reject-payment-note">Alasan Penolakan</label>
                        <textarea class="form-control" id="reject-payment-note" name="note" rows="3" placeholder="Masukkan alasan penolakan pembayaran"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                <button class="btn btn-danger" id="reject-payment-btn" type="button">Tolak Pembayaran</button>
            </div>
        </div>
    </div>
</div>
