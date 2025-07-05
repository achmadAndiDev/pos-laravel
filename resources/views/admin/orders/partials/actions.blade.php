<div class="btn-list">
  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
    <i class="ti ti-eye"></i>
  </a>
  <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Edit Order">
    <i class="ti ti-edit"></i>
  </a>
  <div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
      <i class="ti ti-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="{{ route('admin.orders.print', $order->id) }}">
        <i class="ti ti-printer me-2"></i>
        Cetak Dokumen
      </a>
      <a class="dropdown-item update-status-btn" href="#" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
        <i class="ti ti-refresh me-2"></i>
        Update Status
      </a>
      <a class="dropdown-item process-payment-btn" href="#" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#processPaymentModal">
        <i class="ti ti-cash me-2"></i>
        Proses Pembayaran
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item text-danger" href="#" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
        <i class="ti ti-x me-2"></i>
        Batalkan Order
      </a>
    </div>
  </div>
</div>