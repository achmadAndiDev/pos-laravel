@extends('admin.layouts.app')

@section('title', 'Kategori Produk')
@section('subtitle', 'Detail Kategori Produk')

@section('right-header')
<div class="btn-list">
    <a href="{{ route('kasir.product-categories.edit', $productCategory) }}" class="btn btn-warning">
        <i class="ti ti-edit"></i>
        Edit
    </a>
    <a href="{{ route('kasir.product-categories.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Kategori Produk</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <div class="form-control-plaintext">{{ $productCategory->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Kategori</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-blue-lt">{{ $productCategory->code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($productCategory->description)
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <div class="form-control-plaintext">{{ $productCategory->description }}</div>
                </div>
                @endif
                
                @if($productCategory->image)
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <div class="form-control-plaintext">
                        <img src="{{ Storage::url($productCategory->image) }}" alt="{{ $productCategory->name }}" 
                             class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-control-plaintext">
                                <span class="badge {{ $productCategory->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $productCategory->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $productCategory->sort_order }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Dibuat</label>
                            <div class="form-control-plaintext">{{ $productCategory->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Diperbarui</label>
                            <div class="form-control-plaintext">{{ $productCategory->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-primary text-white avatar">
                                        <i class="ti ti-package"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $productCategory->products->count() }} Produk
                                    </div>
                                    <div class="text-muted">
                                        {{ $productCategory->products->where('status', 'active')->count() }} Aktif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($productCategory->products->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-green text-white avatar">
                                        <i class="ti ti-currency-dollar"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        Rp {{ number_format($productCategory->products->sum('selling_price'), 0, ',', '.') }}
                                    </div>
                                    <div class="text-muted">
                                        Total Nilai Produk
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-warning text-white avatar">
                                        <i class="ti ti-alert-triangle"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $productCategory->products->filter(function($product) { return $product->stock <= $product->minimum_stock; })->count() }}
                                    </div>
                                    <div class="text-muted">
                                        Stok Menipis
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($productCategory->products->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Produk dalam Kategori Ini</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Outlet</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productCategory->products->take(10) as $product)
                            <tr>
                                <td><span class="badge bg-blue-lt">{{ $product->code }}</span></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->outlet->name ?? '-' }}</td>
                                <td>{{ $product->formatted_selling_price }}</td>
                                <td>
                                    <span class="badge {{ $product->stock <= $product->minimum_stock ? 'bg-warning' : 'bg-success' }}">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($productCategory->products->count() > 10)
                <div class="text-center mt-3">
                    <small class="text-muted">Menampilkan 10 dari {{ $productCategory->products->count() }} produk</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection