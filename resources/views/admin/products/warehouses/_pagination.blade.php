<div class="d-flex justify-content-between align-items-center">
  <div>
    <span id="paginationInfo" class="text-muted">
      Menampilkan {{ $warehouses->firstItem() ?? 0 }}-{{ $warehouses->lastItem() ?? 0 }} dari {{ $warehouses->total() ?? 0 }} gudang
      (Halaman {{ $warehouses->currentPage() ?? 0 }} dari {{ $warehouses->lastPage() ?? 0 }})
    </span>
  </div>
  <div class="d-flex align-items-center">
    {{ $warehouses->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
  </div>
</div>