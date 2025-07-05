@extends('layouts.app')

@section('title', 'Daftar Penugasan')
@section('subtitle', 'Manajemen data penugasan')

@section('css')
<!-- DateTimePicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<style>
    .modal-dialog {
        width: 90%;
        max-width: 800px;
    }
    
    .modal-header {
        background-color: var(--tblr-primary);
        color: white;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 3px;
    }
    .bg-warning {
        background-color: #f39c12;
        color: #fff;
    }
    .bg-info {
        background-color: #00c0ef;
        color: #fff;
    }
    .bg-success {
        background-color: #00a65a;
        color: #fff;
    }
    .bg-danger {
        background-color: #dd4b39;
        color: #fff;
    }
    
    /* Styling untuk DateTimePicker */
    /* .bootstrap-datetimepicker-widget .btn {
        padding: 6px 12px;
    }
    .bootstrap-datetimepicker-widget table td span {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
    }
    .bootstrap-datetimepicker-widget .timepicker-hour,
    .bootstrap-datetimepicker-widget .timepicker-minute {
        font-weight: bold;
        font-size: 1.2em;
    }
    .bootstrap-datetimepicker-widget table td.hour:hover,
    .bootstrap-datetimepicker-widget table td.minute:hover {
        background: #eee;
        cursor: pointer;
        border-radius: 4px;
    } */
</style>
@endsection

@section('right-header')
<div class="btn-list">
    <button type="button" class="btn btn-success d-sm-inline-block me-2" id="btnImport">
        <i class="fa fa-file-excel-o"></i> Import Excel
    </button>
    <button type="button" class="btn btn-primary d-sm-inline-block" id="btnAdd">
        <i class="fa fa-plus"></i> Tambah Penugasan
    </button>
</div>
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Filter</h3>
                <div class="ms-auto">
                    <button type="button" id="btn-reset-filter" class="btn btn-icon btn-outline-secondary" title="Reset Filter">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-label">Status</div>
                        <div class="d-inline gap-3">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input filter-status" type="checkbox" name="filter_status[]" value="menunggu">
                                <span class="form-check-label">Menunggu</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input filter-status" type="checkbox" name="filter_status[]" value="dalam_perjalanan">
                                <span class="form-check-label">Dalam Perjalanan</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input filter-status" type="checkbox" name="filter_status[]" value="selesai">
                                <span class="form-check-label">Selesai</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input filter-status" type="checkbox" name="filter_status[]" value="dibatalkan">
                                <span class="form-check-label">Dibatalkan</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label">Supir</div>
                        <select class="form-select" id="filter_supir" multiple>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-label">Mobil</div>
                        <select class="form-select" id="filter_mobil" multiple>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label">Tanggal Dibuat</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_created_dari" placeholder="Dari">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_created_dari" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_created_sampai" placeholder="Sampai">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_created_sampai" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-label">Tanggal Berangkat</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_berangkat_dari" placeholder="Dari">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_berangkat_dari" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_berangkat_sampai" placeholder="Sampai">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_berangkat_sampai" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-label">Tanggal Sampai</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_sampai_dari" placeholder="Dari">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_sampai_dari" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_sampai_sampai" placeholder="Sampai">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_sampai_sampai" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-label">Tanggal Target</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_target_dari" placeholder="Dari">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_target_dari" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control datepicker-filter" id="filter_target_sampai" placeholder="Sampai">
                                    <span class="input-group-text">
                                        <a href="#" class="link-secondary clear-date" data-target="filter_target_sampai" data-bs-toggle="tooltip" aria-label="Clear" data-bs-original-title="Clear">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M18 6l-12 12"></path>
                                            <path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penugasan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="w-full table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>RFID Asal</th>
                                <th>RFID Tujuan</th>
                                <th>Supir</th>
                                <th>Mobil</th>
                                <th>Target Waktu</th>
                                <th>Waktu Berangkat</th>
                                <th>Waktu Sampai</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Waktu Dibuat</th>
                                <th>Tanggal Update</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal modal-blur fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Form Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="mb-3">
                        <label class="form-label required" for="rfid_start">RFID Asal</label>
                        <select class="form-select" id="rfid_start" name="rfid_start" required>
                            <option value="">-- Pilih RFID Asal --</option>
                        </select>
                        <div class="form-hint">Pilih RFID lokasi asal keberangkatan</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required" for="rfid_end">RFID Tujuan</label>
                        <select class="form-select" id="rfid_end" name="rfid_end" required>
                            <option value="">-- Pilih RFID Tujuan --</option>
                        </select>
                        <div class="form-hint">Pilih RFID lokasi tujuan</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required" for="supir_id">Supir</label>
                        <select class="form-select" id="supir_id" name="supir_id" required>
                            <option value="">-- Pilih Supir --</option>
                            @foreach($supirs ?? [] as $supir)
                                <option value="{{ $supir->id }}">{{ $supir->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required" for="mobil_id">Mobil</label>
                        <select class="form-select" id="mobil_id" name="mobil_id" required>
                            <option value="">-- Pilih Mobil --</option>
                            @foreach($mobils ?? [] as $mobil)
                                <option value="{{ $mobil->id }}">{{ $mobil->nopol }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required" for="target_waktu">Target Waktu</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-calendar"></i>
                            </span>
                            <input type="text" class="form-control datetimepicker" id="target_waktu" name="target_waktu" placeholder="Pilih tanggal dan waktu" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required">Status</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="status" value="menunggu" class="form-selectgroup-input" checked required>
                                <span class="form-selectgroup-label">Menunggu</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="status" value="dalam_perjalanan" class="form-selectgroup-input" required>
                                <span class="form-selectgroup-label">Dalam Perjalanan</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="status" value="selesai" class="form-selectgroup-input" required>
                                <span class="form-selectgroup-label">Selesai</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="status" value="dibatalkan" class="form-selectgroup-input" required>
                                <span class="form-selectgroup-label">Dibatalkan</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="start_berangkat_group">
                        <label class="form-label" for="start_berangkat">Waktu Berangkat</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-calendar"></i>
                            </span>
                            <input type="text" class="form-control datetimepicker" id="start_berangkat" name="start_berangkat" placeholder="Pilih tanggal dan waktu">
                        </div>
                        <div class="form-hint">Isi jika status Dalam Perjalanan atau Selesai</div>
                    </div>
                    
                    <div class="mb-3" id="waktu_sampai_group">
                        <label class="form-label" for="waktu_sampai">Waktu Sampai</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-calendar"></i>
                            </span>
                            <input type="text" class="form-control datetimepicker" id="waktu_sampai" name="waktu_sampai" placeholder="Pilih tanggal dan waktu">
                        </div>
                        <div class="form-hint">Isi jika status Selesai</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal modal-blur fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <tr>
                            <th class="w-25">RFID Asal</th>
                            <td id="view_rfid_start"></td>
                        </tr>
                        <tr>
                            <th class="w-25">RFID Tujuan</th>
                            <td id="view_rfid_end"></td>
                        </tr>
                        <tr>
                            <th>Supir</th>
                            <td id="view_supir"></td>
                        </tr>
                        <tr>
                            <th>Mobil</th>
                            <td id="view_mobil"></td>
                        </tr>
                        <tr>
                            <th>Target Waktu</th>
                            <td id="view_target_waktu"></td>
                        </tr>
                        <tr>
                            <th>Waktu Berangkat</th>
                            <td id="view_start_berangkat"></td>
                        </tr>
                        <tr>
                            <th>Waktu Sampai</th>
                            <td id="view_waktu_sampai"></td>
                        </tr>
                        <tr>
                            <th>Durasi</th>
                            <td id="view_durasi"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="view_status"></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td id="view_keterangan"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td id="view_created_at"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Diperbarui</th>
                            <td id="view_updated_at"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Import Excel -->
<div class="modal modal-blur fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div>
                                <i class="fa fa-info-circle me-2"></i>
                            </div>
                            <div>
                                <h4>Petunjuk Import</h4>
                                <p>Silakan download template Excel terlebih dahulu. Isi data sesuai format yang telah ditentukan, kemudian upload file tersebut.</p>
                                <p>Setiap baris data yang diimport akan dibuat sebagai penugasan baru dalam sistem.</p>
                                <p>Kolom <strong>RFID Asal</strong> dan <strong>RFID Tujuan</strong> diisi dengan kode RFID yang sudah terdaftar di sistem.</p>
                                <p>Kolom <strong>Supir</strong> diisi dengan nomor telepon supir yang sudah terdaftar di sistem.</p>
                                <p>Kolom <strong>Mobil</strong> diisi dengan nomor polisi mobil yang sudah terdaftar di sistem.</p>
                                <p>Kolom <strong>Status</strong> diisi dengan: menunggu, dalam_perjalanan, selesai, atau dibatalkan.</p>
                                <p>Kolom <strong>Target Waktu</strong> diisi dengan format tanggal dan waktu: YYYY-MM-DD HH:MM:SS.</p>
                                <p>Kolom <strong>Waktu Berangkat</strong> dan <strong>Waktu Sampai</strong> bersifat opsional, dengan format yang sama.</p>
                                <p><strong>Penting:</strong> Baris kosong akan dianggap sebagai akhir dari data yang akan diimport.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('penugasan.template') }}" class="btn btn-outline-primary">
                            <i class="fa fa-download"></i> Download Template Excel
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required" for="file">File Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls" required>
                        <div class="form-text">Format file: .xlsx atau .xls (max 2MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success ms-auto" id="btnUpload">
                        <i class="fa fa-upload me-1"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- DateTimePicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    let table;
    let pickers = [];
    let stopFilterChangeListener = false;
    document.addEventListener('DOMContentLoaded', function() {
        // Event handler untuk tombol clear date
        $(document).on('click', '.clear-date', function(e) {
            e.preventDefault();
            const targetId = $(this).data('target');
            const inputElement = $('#' + targetId);
            
            // Kosongkan nilai input
            inputElement.val('');
            
            // Temukan picker yang sesuai dan reset
            const pickerIndex = pickers.findIndex(p => p.options.element.id === targetId);
            if (pickerIndex !== -1) {
                pickers[pickerIndex].clearSelection();
            }
            
            // Reload tabel untuk memperbarui data
            table.ajax.reload();
        });
        
        $('.datepicker-filter').each(function (index, elm) {
            $(elm).on('input', function () {
                table.ajax.reload()
            });
            const picker = new Litepicker({
                element: elm,
                buttonText: {
                    previousMonth: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M15 6l-6 6l6 6" /></svg>`,
                    nextMonth: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M9 6l6 6l-6 6" /></svg>`,
                },
                setup: (picker) => {
                    picker.on('selected', function(param1, param2) {
                        if (stopFilterChangeListener) return;
                        table.ajax.reload()
                    });
                }
            });
            pickers.push(picker);
            
        });
    });
    $(function() {
        // Initialize DateTimePicker for form inputs
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true,
            stepping: 5, // Langkah menit (5 menit)
            useCurrent: false, // Tidak menggunakan waktu saat ini secara default
            showTodayButton: true, // Menampilkan tombol "Hari Ini"
            showClear: true, // Menampilkan tombol "Hapus"
            showClose: true, // Menampilkan tombol "Tutup"
            keepOpen: false, // Menutup picker setelah memilih waktu
            toolbarPlacement: 'top', // Menempatkan toolbar di bagian atas
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
        
        // Initialize Select2 for RFID Start
        $('#rfid_start').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih RFID Asal...',
            dropdownParent: $('#modal'),
            ajax: {
                url: "{{ route('rfid.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
        
        // Initialize Select2 for RFID End
        $('#rfid_end').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih RFID Tujuan...',
            dropdownParent: $('#modal'),
            ajax: {
                url: "{{ route('rfid.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
        
        // Initialize Select2 for Supir
        $('#supir_id').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih Supir...',
            dropdownParent: $('#modal'),
            ajax: {
                url: "{{ route('supir.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
        
        // Initialize Select2 for Mobil
        $('#mobil_id').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih Mobil...',
            dropdownParent: $('#modal'),
            ajax: {
                url: "{{ route('mobil.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
        
        // Toggle fields based on status
        $('input[name="status"]').change(function() {
            var status = $(this).val();
            
            // Waktu Berangkat
            if (status === 'dalam_perjalanan' || status === 'selesai') {
                $('#start_berangkat_group').show();
            } else {
                $('#start_berangkat_group').hide();
                $('#start_berangkat').val('');
            }
            
            // Waktu Sampai
            if (status === 'selesai') {
                $('#waktu_sampai_group').show();
            } else {
                $('#waktu_sampai_group').hide();
                $('#waktu_sampai').val('');
            }
        });
        
        // No need for modal instances when using jQuery
        
        // Hide fields initially
        $('#start_berangkat_group').hide();
        $('#waktu_sampai_group').hide();
        
        // Initialize Select2 for filter supir
        $('#filter_supir').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih Supir...',
            allowClear: true,
            closeOnSelect: false,
            ajax: {
                url: "{{ route('supir.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
        
        // Initialize Select2 for filter mobil
        $('#filter_mobil').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih Mobil...',
            allowClear: true,
            closeOnSelect: false,
            ajax: {
                url: "{{ route('mobil.select2') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });

        // Initialize DataTable
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('penugasan.index') }}",
                data: function(d) {
                    // Status filter
                    var statusFilter = [];
                    $('.filter-status:checked').each(function() {
                        statusFilter.push($(this).val());
                    });
                    d.status_filter = statusFilter;
                    
                    // Supir filter
                    var supirFilter = $('#filter_supir').val();
                    if (supirFilter && supirFilter.length > 0) {
                        d.supir_filter = supirFilter;
                    }
                    
                    // Mobil filter
                    var mobilFilter = $('#filter_mobil').val();
                    if (mobilFilter && mobilFilter.length > 0) {
                        d.mobil_filter = mobilFilter;
                    }
                    
                    // Tanggal dibuat filter
                    if ($('#filter_created_dari').val()) {
                        d.created_dari = $('#filter_created_dari').val();
                    }
                    if ($('#filter_created_sampai').val()) {
                        d.created_sampai = $('#filter_created_sampai').val();
                    }
                    
                    // Waktu berangkat filter
                    if ($('#filter_berangkat_dari').val()) {
                        d.berangkat_dari = $('#filter_berangkat_dari').val();
                    }
                    if ($('#filter_berangkat_sampai').val()) {
                        d.berangkat_sampai = $('#filter_berangkat_sampai').val();
                    }
                    
                    // Waktu sampai filter
                    if ($('#filter_sampai_dari').val()) {
                        d.sampai_dari = $('#filter_sampai_dari').val();
                    }
                    if ($('#filter_sampai_sampai').val()) {
                        d.sampai_sampai = $('#filter_sampai_sampai').val();
                    }
                    
                    // Target waktu filter
                    if ($('#filter_target_dari').val()) {
                        d.target_dari = $('#filter_target_dari').val();
                    }
                    if ($('#filter_target_sampai').val()) {
                        d.target_sampai = $('#filter_target_sampai').val();
                    }
                }
            },
            columns: [
                {data: 'rfid_start_info', name: 'rfids.kode', searchable: true},
                {data: 'rfid_end_info', name: 'rfids_end.kode', searchable: true},
                {data: 'supir_id', name: 'supirs.nama', searchable: true},
                {data: 'mobil_id', name: 'mobils.nopol', searchable: true},
                {data: 'target_waktu', name: 'target_waktu', searchable: false},
                {data: 'start_berangkat', name: 'start_berangkat', searchable: false},
                {data: 'waktu_sampai', name: 'waktu_sampai', searchable: false},
                {data: 'durasi', name: 'durasi', searchable: false},
                {data: 'status', name: 'status', searchable: true},
                {
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        return formatDateTime(data);
                    }
                },
                {
                    data: 'updated_at', 
                    name: 'updated_at',
                    render: function(data) {
                        return formatDateTime(data);
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
                // Hidden column for keterangan search
                {data: 'keterangan', name: 'keterangan', visible: false, searchable: true},
            ],
            search: {
                smart: true,
                regex: false,
                caseInsensitive: true
            }
        });

        // Add button click
        $('#btnAdd').click(function() {
            $('#form')[0].reset();
            $('#id').val('');
            $('#modalLabel').text('Tambah Penugasan');
            $('input[name="status"][value="menunggu"]').prop('checked', true).trigger('change');
            
            // Reset all select2 dropdowns
            resetDropdowns();
            
            $("#modal").modal('show');
        });

        // Function to reset select2 dropdowns
        function resetDropdowns() {
            $('#rfid_start').val(null).trigger('change');
            $('#rfid_end').val(null).trigger('change');
            $('#supir_id').val(null).trigger('change');
            $('#mobil_id').val(null).trigger('change');
        }

        // Edit button click
        $(document).on('click', '.editBtn', function() {
            var id = $(this).data('id');
            $('#form')[0].reset();
            resetDropdowns();
            
            $.ajax({
                url: "{{ url('penugasan') }}/" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id').val(data.id);
                    
                    // Set target waktu, status, and other non-select2 fields
                    if (data.target_waktu) {
                        $('#target_waktu').val(moment(data.target_waktu).format('YYYY-MM-DD HH:mm'));
                    }
                    
                    // Set radio button status
                    $('input[name="status"][value="' + data.status + '"]').prop('checked', true).trigger('change');
                    
                    if (data.start_berangkat) {
                        $('#start_berangkat').val(moment(data.start_berangkat).format('YYYY-MM-DD HH:mm'));
                    }
                    
                    if (data.waktu_sampai) {
                        $('#waktu_sampai').val(moment(data.waktu_sampai).format('YYYY-MM-DD HH:mm'));
                    }
                    
                    $('#keterangan').val(data.keterangan || '');
                    
                    // Handle RFID Start select2
                    if (data.rfid_start && data.rfidStart) {
                        var rfidStartOption = new Option(
                            data.rfidStart.kode + ' (' + data.rfidStart.lokasi + ' - ' + data.rfidStart.perusahaan + ')', 
                            data.rfidStart.id, 
                            true, 
                            true
                        );
                        $('#rfid_start').append(rfidStartOption).trigger('change');
                    }
                    
                    // Handle RFID End select2
                    if (data.rfid_end && data.rfidEnd) {
                        var rfidEndOption = new Option(
                            data.rfidEnd.kode + ' (' + data.rfidEnd.lokasi + ' - ' + data.rfidEnd.perusahaan + ')', 
                            data.rfidEnd.id, 
                            true, 
                            true
                        );
                        $('#rfid_end').append(rfidEndOption).trigger('change');
                    }
                    
                    // Handle Supir select2
                    if (data.supir_id && data.supir) {
                        var supirOption = new Option(
                            data.supir.nama, 
                            data.supir_id, 
                            true, 
                            true
                        );
                        $('#supir_id').append(supirOption).trigger('change');
                    }
                    
                    // Handle Mobil select2
                    if (data.mobil_id && data.mobil) {
                        var mobilOption = new Option(
                            data.mobil.nopol, 
                            data.mobil_id, 
                            true, 
                            true
                        );
                        $('#mobil_id').append(mobilOption).trigger('change');
                    }
                    
                    $('#modalLabel').text('Edit Penugasan');
                    $("#modal").modal('show');
                },
                error: function(xhr, status, error) {
                    showErrorToast(xhr);
                    console.error('Error:', error);
                }
            });
        });

        // View button click
        $(document).on('click', '.viewBtn', function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: "{{ url('penugasan') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#view_rfid_start').text(data.rfid_start_kode ? data.rfid_start_kode + ' (' + data.rfid_start_lokasi + ' - ' + data.rfid_start_perusahaan + ')' : '-');
                    $('#view_rfid_end').text(data.rfid_end_kode ? data.rfid_end_kode + ' (' + data.rfid_end_lokasi + ' - ' + data.rfid_end_perusahaan + ')' : '-');
                    $('#view_supir').text(data.supir_nama);
                    $('#view_mobil').text(data.mobil_nopol);
                    $('#view_target_waktu').text(data.target_waktu_formatted);
                    $('#view_start_berangkat').text(data.start_berangkat_formatted || '-');
                    $('#view_waktu_sampai').text(data.waktu_sampai_formatted || '-');
                    $('#view_durasi').text(data.durasi || '-');
                    
                    var statusLabels = {
                        'menunggu': '<span class="badge bg-warning">Menunggu</span>',
                        'dalam_perjalanan': '<span class="badge bg-info">Dalam Perjalanan</span>',
                        'selesai': '<span class="badge bg-success">Selesai</span>',
                        'dibatalkan': '<span class="badge bg-danger">Dibatalkan</span>'
                    };
                    
                    $('#view_status').html(statusLabels[data.status]);
                    $('#view_keterangan').text(data.keterangan || '-');
                    $('#view_created_at').text(data.created_at_formatted);
                    $('#view_updated_at').text(data.updated_at_formatted);
                    $("#viewModal").modal('show');
                },
                error: function(xhr, status, error) {
                    showErrorToast(xhr);
                }
            });
        });

        // Delete button click
        $(document).on('click', '.deleteBtn', function() {
            var id = $(this).data('id');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('penugasan') }}/" + id,
                        type: "DELETE",
                        dataType: "JSON",
                        success: function(data) {
                            table.ajax.reload();
                            showSuccessToast(data.message);
                        },
                        error: function(xhr, status, error) {
                            showErrorToast(xhr);
                        }
                    });
                }
            });
        });

        // Form submit
        $('#form').submit(function(e) {
            e.preventDefault();
            // Disable the submit button to prevent multiple submissions
            var $submitBtn = $('#btnSave');
            $submitBtn.prop('disabled', true);
            
            var id = $('#id').val();
            var url = id ? "{{ url('penugasan') }}/" + id : "{{ route('penugasan.store') }}";
            var method = id ? "PUT" : "POST";
            var actionText = id ? 'diperbarui' : 'ditambahkan';
            
            // Pastikan semua nilai select2 diambil dengan benar
            // Untuk select2, pastikan hanya ID yang dikirim
            var rfidStartVal = $('#rfid_start').val();
            var rfidEndVal = $('#rfid_end').val();
            var supirIdVal = $('#supir_id').val();
            var mobilIdVal = $('#mobil_id').val();
            
            // Log untuk debugging
            console.log('RFID Start:', rfidStartVal);
            console.log('RFID End:', rfidEndVal);
            console.log('Supir ID:', supirIdVal);
            console.log('Mobil ID:', mobilIdVal);
            
            var originalBtnText = $submitBtn.html();
            $submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            
            var formData = {
                id: $('#id').val(),
                rfid_start: rfidStartVal,
                rfid_end: rfidEndVal,
                supir_id: supirIdVal,
                mobil_id: mobilIdVal,
                target_waktu: $('#target_waktu').val(),
                status: $('input[name="status"]:checked').val(),
                start_berangkat: $('#start_berangkat').val(),
                waktu_sampai: $('#waktu_sampai').val(),
                keterangan: $('#keterangan').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            $.ajax({
                url: url,
                type: method,
                data: formData,
                dataType: "JSON",
                success: function(data) {
                    $("#modal").modal('hide');
                    table.ajax.reload();
                    showSuccessToast('Data penugasan berhasil ' + actionText);
                },
                error: function(xhr, status, error) {
                    showErrorToast(xhr);
                    console.error('Error:', error);
                },
                complete: function() {
                    // Re-enable the button regardless of success or failure
                    $submitBtn.html(originalBtnText);
                    $submitBtn.prop('disabled', false);
                }
            });
        });
        
        // Handle modal events
        $("#modal").on('hidden.bs.modal', function () {
            $('#form')[0].reset();
        });
        
        // Initial status change trigger
        $('#status').trigger('change');

        // Filter status change event
        $('.filter-status').change(function() {
            if (stopFilterChangeListener) return;
            table.ajax.reload();
        });
        
        // Filter select2 change event
        $('#filter_supir, #filter_mobil').on('change', function() {
            if (stopFilterChangeListener) return;
            table.ajax.reload();
        });

        // Reset filter button click
        $('#btn-reset-filter').click(function() {
            stopFilterChangeListener = true;
            // Reset status checkboxes - uncheck all
            $('.filter-status').prop('checked', false);
            
            // Reset select2 filters dengan cara yang lebih baik
            $('#filter_supir').val(null).trigger('change');
            $('#filter_mobil').val(null).trigger('change');
            
            // Reset date filters
            for (let picker of pickers) {
                picker.clearSelection();
            }
            stopFilterChangeListener = false;
            // Reload table
            table.ajax.reload();
        });
        
        // Import button click
        $('#btnImport').click(function() {
            $('#importForm')[0].reset();
            $("#importModal").modal('show');
        });
        
        // Import form submit
        $('#importForm').submit(function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('penugasan.import') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnUpload').html('<i class="fa fa-spinner fa-spin me-1"></i> Uploading...');
                    $('#btnUpload').prop('disabled', true);
                },
                success: function(data) {
                    $("#importModal").modal('hide');
                    $('#importForm')[0].reset();
                    table.ajax.reload();
                    
                    if (data.warnings && data.warnings.length > 0) {
                        // Jika ada warnings, tampilkan sebagai info
                        var warningList = '<ul>';
                        $.each(data.warnings, function(key, value) {
                            warningList += '<li>' + value + '</li>';
                        });
                        warningList += '</ul>';
                        
                        // Gunakan toastr langsung untuk kasus khusus ini dengan HTML
                        toastr.options = {
                            closeButton: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: "toast-top-center",
                            preventDuplicates: false,
                            progressBar: false,
                            enableHtml: true
                        };
                        toastr.info(warningList, data.message);
                    } else {
                        showSuccessToast(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorToast(xhr);
                },
                complete: function() {
                    $('#btnUpload').html('<i class="fa fa-upload me-1"></i> Upload');
                    $('#btnUpload').prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection