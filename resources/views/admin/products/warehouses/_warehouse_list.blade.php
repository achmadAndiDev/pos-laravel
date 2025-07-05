@foreach($warehouses as $warehouse)
<tr class="warehouse-row">
  <td>{{ $warehouse->id }}</td>
  <td>{{ $warehouse->name }}</td>
  <td>{{ $warehouse->address }}</td>
  <td>
    @if($warehouse->subdistrict)
      {{ $warehouse->subdistrict->name }} - {{ $warehouse->district->name ?? '' }}, {{ $warehouse->province->name ?? '' }}
    @else
      -
    @endif
  </td>
  <td>
    @if($warehouse->is_active)
      <span class="badge bg-success text-white">Aktif</span>
    @else
      <span class="badge bg-danger text-white">Nonaktif</span>
    @endif
  </td>
  <td>
    <div class="btn-list flex-nowrap">
      <a href="{{ route('admin.products.warehouses.edit', $warehouse->id) }}" class="btn btn-sm btn-warning btn-icon">
        <i class="ti ti-edit"></i>
      </a>
      <div class="form-check form-switch d-inline-block ms-2">
        <input class="form-check-input status-switch" type="checkbox" id="status-{{ $warehouse->id }}" 
          data-id="{{ $warehouse->id }}" {{ $warehouse->is_active ? 'checked' : '' }}>
      </div>
    </div>
  </td>
</tr>
@endforeach