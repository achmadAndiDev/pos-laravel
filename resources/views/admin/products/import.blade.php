@extends('admin.layouts.app')

@section('title', 'Import Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Produk</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-arrow-left"></i> Kembali ke Daftar Produk
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h4 class="alert-title"><i class="ti ti-alert-circle me-2"></i>Error!</h4>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if(session('import_errors'))
                        <div class="alert alert-warning">
                            <h4 class="alert-title"><i class="ti ti-alert-triangle me-2"></i>Peringatan!</h4>
                            <p>Beberapa baris data tidak dapat diproses:</p>
                            <ul class="mt-2">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="ti ti-info-circle me-2"></i>Petunjuk Import</h5>
                                <p>Silakan ikuti langkah-langkah berikut untuk mengimpor produk:</p>
                                <ol>
                                    <li>Download template Excel dengan mengklik tombol "Download Template"</li>
                                    <li>Isi data produk sesuai format yang telah disediakan</li>
                                    <li>Simpan file Excel tersebut</li>
                                    <li>Upload file Excel yang telah diisi dengan menggunakan form di bawah ini</li>
                                    <li>Klik tombol "Import" untuk memulai proses import</li>
                                </ol>
                                <p><strong>Catatan:</strong></p>
                                <ul>
                                    <li>Format stok: <code>Nama Gudang: jumlah, Nama Gudang 2: jumlah</code></li>
                                    <li>Format kategori: <code>Kategori1, Kategori2, Kategori3</code></li>
                                    <li>Format brand: <code>Nama Brand</code> (contoh: Eiger, Consina, dll)</li>
                                    <li>Kolom yang wajib diisi: Nama Produk, SKU, Harga Beli, Harga Jual</li>
                                    <li>Jika SKU sudah ada, data produk akan diperbarui dan stok akan ditambahkan ke stok yang sudah ada</li>
                                    <li>Jika SKU belum ada, produk baru akan dibuat dan stok akan ditambahkan ke stok yang sudah ada</li>
                                    <li>Jika SKU belum ada, produk baru akan dibuat</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <a href="{{ route('admin.products.import.template') }}" class="btn btn-primary">
                                <i class="ti ti-download me-1"></i> Download Template
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Upload File</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.import.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label" for="import_file">File Import (Excel/CSV)</label>
                                    <input type="file" class="form-control @error('import_file') is-invalid @enderror" id="import_file" name="import_file" accept=".xlsx,.xls,.csv,.txt">
                                    @error('import_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-hint">Format yang didukung: Excel (.xlsx, .xls) dan CSV (.csv, .txt). Ukuran maksimal 10MB.</small>
                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-file-import me-1"></i> Import
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-2">
                                        <i class="ti ti-x me-1"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Informasi Tambahan</h4>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="importInfo">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                Format Data
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#importInfo">
                                            <div class="accordion-body">
                                                <p>Berikut adalah penjelasan format data yang digunakan:</p>
                                                <ul>
                                                    <li><strong>Nama Produk:</strong> Nama lengkap produk</li>
                                                    <li><strong>Size:</strong> Ukuran produk (opsional)</li>
                                                    <li><strong>Warna:</strong> Warna produk (opsional)</li>
                                                    <li><strong>Deskripsi:</strong> Deskripsi lengkap produk (opsional)</li>
                                                    <li><strong>SKU:</strong> Kode unik produk (wajib)</li>
                                                    <li><strong>Link Gambar:</strong> URL gambar produk (opsional)</li>
                                                    <li><strong>Harga Beli:</strong> Harga modal produk (wajib)</li>
                                                    <li><strong>Harga Jual:</strong> Harga jual normal produk (wajib)</li>
                                                    <li><strong>Harga Jual ke Reseller:</strong> Harga khusus untuk reseller (opsional)</li>
                                                    <li><strong>Harga Custom 1-3:</strong> Harga khusus lainnya (opsional)</li>
                                                    <li><strong>Berat:</strong> Berat produk dalam gram (opsional)</li>
                                                    <li><strong>Dimensi:</strong> Panjang, lebar, tinggi dalam cm (opsional)</li>
                                                    <li><strong>Stok:</strong> Format "Nama Gudang: jumlah, Nama Gudang 2: jumlah" (opsional)</li>
                                                    <li><strong>Kategori:</strong> Kategori produk dipisahkan koma (opsional)</li>
                                                    <li><strong>Brand:</strong> Nama brand produk (opsional)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Tips Import
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#importInfo">
                                            <div class="accordion-body">
                                                <p>Berikut adalah tips untuk melakukan import produk:</p>
                                                <ul>
                                                    <li>Pastikan format data sesuai dengan template yang disediakan (stok akan ditambahkan ke stok yang sudah ada)</li>
                                                    <li>Untuk update produk yang sudah ada, gunakan SKU yang sama (stok akan ditambahkan ke stok yang sudah ada)</li>
                                                    <li>Jika ingin menambahkan banyak variasi, buat baris terpisah dengan SKU berbeda</li>
                                                    <li>Pastikan tidak ada karakter khusus pada data (seperti: &, <, >, ", ')</li>
                                                    <li>Untuk file CSV, pastikan menggunakan encoding UTF-8</li>
                                                    <li>Jika terjadi error, periksa pesan error dan perbaiki data sesuai petunjuk</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Menampilkan nama file yang dipilih
        $('#import_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $(this).next('.form-file-text').html(fileName);
            }
        });
    });
</script>
@endpush