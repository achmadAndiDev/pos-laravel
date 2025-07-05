@extends('admin.layouts.app')

@section('title', 'Variasi Produk')
@section('subtitle', 'Manajemen Variasi Produk')

@section('right-header')
<div class="btn-list">
  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addVariationTypeModal">
    <i class="ti ti-plus"></i>
    Tambah Jenis Variasi
  </a>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-backpack"></i>
    Kembali ke Produk
  </a>
  <a href="#" class="btn btn-primary d-sm-none" data-bs-toggle="modal" data-bs-target="#addVariationTypeModal">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Jenis Variasi</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="variationTypeTable" class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Jenis</th>
                <th>Deskripsi</th>
                <th class="w-1">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>VAR-001</td>
                <td>Warna</td>
                <td>Variasi warna produk</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationTypeModal" data-id="1">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-type" data-id="1">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAR-002</td>
                <td>Ukuran</td>
                <td>Variasi ukuran produk</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationTypeModal" data-id="2">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-type" data-id="2">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAR-003</td>
                <td>Material</td>
                <td>Variasi material produk</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationTypeModal" data-id="3">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-type" data-id="3">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAR-004</td>
                <td>Tipe</td>
                <td>Variasi tipe produk</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationTypeModal" data-id="4">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-type" data-id="4">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Nilai Variasi</h3>
        <div>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVariationValueModal">
            <i class="ti ti-plus"></i>
            Tambah Nilai
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="variationValueTable" class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Jenis Variasi</th>
                <th>Nilai</th>
                <th class="w-1">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>VAL-001</td>
                <td>Warna</td>
                <td>Merah</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="1">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="1">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-002</td>
                <td>Warna</td>
                <td>Hitam</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="2">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="2">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-003</td>
                <td>Ukuran</td>
                <td>S</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="3">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="3">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-004</td>
                <td>Ukuran</td>
                <td>M</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="4">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="4">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-005</td>
                <td>Ukuran</td>
                <td>L</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="5">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="5">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-006</td>
                <td>Tipe</td>
                <td>Standar</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="6">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="6">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>VAL-007</td>
                <td>Tipe</td>
                <td>Premium</td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <button class="btn btn-sm btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editVariationValueModal" data-id="7">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon delete-variation-value" data-id="7">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Jenis Variasi -->
<div class="modal modal-blur fade" id="addVariationTypeModal" tabindex="-1" role="dialog" aria-labelledby="addVariationTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVariationTypeModalLabel">Tambah Jenis Variasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addVariationTypeForm">
          <div class="mb-3">
            <label class="form-label required">Nama Jenis Variasi</label>
            <input type="text" class="form-control" name="name" placeholder="Masukkan nama jenis variasi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi jenis variasi"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveVariationTypeBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Jenis Variasi -->
<div class="modal modal-blur fade" id="editVariationTypeModal" tabindex="-1" role="dialog" aria-labelledby="editVariationTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVariationTypeModalLabel">Edit Jenis Variasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editVariationTypeForm">
          <input type="hidden" name="variation_type_id" id="edit_variation_type_id" value="1">
          <div class="mb-3">
            <label class="form-label required">Nama Jenis Variasi</label>
            <input type="text" class="form-control" name="name" id="edit_variation_type_name" value="Warna" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" id="edit_variation_type_description" rows="3">Variasi warna produk</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="updateVariationTypeBtn">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Nilai Variasi -->
<div class="modal modal-blur fade" id="addVariationValueModal" tabindex="-1" role="dialog" aria-labelledby="addVariationValueModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVariationValueModalLabel">Tambah Nilai Variasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addVariationValueForm">
          <div class="mb-3">
            <label class="form-label required">Jenis Variasi</label>
            <select class="form-select" name="variation_type_id" required>
              <option value="">Pilih Jenis Variasi</option>
              <option value="1">Warna</option>
              <option value="2">Ukuran</option>
              <option value="3">Material</option>
              <option value="4">Tipe</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Nilai Variasi</label>
            <input type="text" class="form-control" name="value" placeholder="Masukkan nilai variasi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode</label>
            <input type="text" class="form-control" name="code" placeholder="Masukkan kode variasi (opsional)">
            <small class="form-hint">Kode ini akan digunakan untuk membuat SKU produk</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveVariationValueBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Nilai Variasi -->
<div class="modal modal-blur fade" id="editVariationValueModal" tabindex="-1" role="dialog" aria-labelledby="editVariationValueModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVariationValueModalLabel">Edit Nilai Variasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editVariationValueForm">
          <input type="hidden" name="variation_value_id" id="edit_variation_value_id" value="1">
          <div class="mb-3">
            <label class="form-label required">Jenis Variasi</label>
            <select class="form-select" name="variation_type_id" id="edit_variation_value_type_id" required>
              <option value="">Pilih Jenis Variasi</option>
              <option value="1" selected>Warna</option>
              <option value="2">Ukuran</option>
              <option value="3">Material</option>
              <option value="4">Tipe</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Nilai Variasi</label>
            <input type="text" class="form-control" name="value" id="edit_variation_value" value="Merah" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode</label>
            <input type="text" class="form-control" name="code" id="edit_variation_value_code" value="RED">
            <small class="form-hint">Kode ini akan digunakan untuk membuat SKU produk</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="updateVariationValueBtn">Simpan Perubahan</button>
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
  
  .btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 32px;
    width: 32px;
    padding: 0;
  }
  
  .form-label.required:after {
    content: " *";
    color: red;
  }
</style>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable untuk Jenis Variasi
    const variationTypeTable = $('#variationTypeTable').DataTable({
      responsive: true,
      language: {
        emptyTable: "Tidak ada data yang tersedia pada tabel ini",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
        infoPostFix: "",
        thousands: ".",
        lengthMenu: "Tampilkan _MENU_ entri",
        loadingRecords: "Sedang memuat...",
        processing: "Sedang memproses...",
        search: "Cari:",
        zeroRecords: "Tidak ditemukan data yang sesuai",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya"
        },
        aria: {
          sortAscending: ": aktifkan untuk mengurutkan kolom ke atas",
          sortDescending: ": aktifkan untuk mengurutkan kolom ke bawah"
        }
      },
      pagingType: "simple",
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
      pageLength: 5,
      drawCallback: function() {
        $('.dataTables_paginate > .pagination').addClass('pagination-sm');
      },
      initComplete: function() {
        // Menambahkan kelas Bootstrap pada elemen search dan length
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_length select').addClass('form-select');
        
        // Menambahkan label yang lebih jelas
        $('.dataTables_filter label').contents().filter(function() {
          return this.nodeType === 3;
        }).replaceWith('<span class="me-2">Cari:</span>');
      }
    });
    
    // Inisialisasi DataTable untuk Nilai Variasi
    const variationValueTable = $('#variationValueTable').DataTable({
      responsive: true,
      language: {
        emptyTable: "Tidak ada data yang tersedia pada tabel ini",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
        infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
        infoPostFix: "",
        thousands: ".",
        lengthMenu: "Tampilkan _MENU_ entri",
        loadingRecords: "Sedang memuat...",
        processing: "Sedang memproses...",
        search: "Cari:",
        zeroRecords: "Tidak ditemukan data yang sesuai",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya"
        },
        aria: {
          sortAscending: ": aktifkan untuk mengurutkan kolom ke atas",
          sortDescending: ": aktifkan untuk mengurutkan kolom ke bawah"
        }
      },
      pagingType: "simple",
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
      pageLength: 5,
      drawCallback: function() {
        $('.dataTables_paginate > .pagination').addClass('pagination-sm');
      },
      initComplete: function() {
        // Menambahkan kelas Bootstrap pada elemen search dan length
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_length select').addClass('form-select');
        
        // Menambahkan label yang lebih jelas
        $('.dataTables_filter label').contents().filter(function() {
          return this.nodeType === 3;
        }).replaceWith('<span class="me-2">Cari:</span>');
      }
    });
    
    // Event handler untuk tombol Tambah Jenis Variasi
    $('#saveVariationTypeBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('addVariationTypeForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi penyimpanan data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Jenis variasi baru telah ditambahkan',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#addVariationTypeModal').modal('hide');
          $('#addVariationTypeForm').trigger('reset');
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationTypeTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk tombol Edit Jenis Variasi
    $('#updateVariationTypeBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('editVariationTypeForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi update data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Jenis variasi telah diperbarui',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#editVariationTypeModal').modal('hide');
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationTypeTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk tombol Hapus Jenis Variasi
    $(document).on('click', '.delete-variation-type', function() {
      const variationTypeId = $(this).data('id');
      
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus jenis variasi ini? Semua nilai variasi terkait juga akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Simulasi penghapusan data
          Swal.fire(
            'Terhapus!',
            'Jenis variasi telah dihapus.',
            'success'
          );
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationTypeTable.ajax.reload();
          // variationValueTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk tombol Tambah Nilai Variasi
    $('#saveVariationValueBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('addVariationValueForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi penyimpanan data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Nilai variasi baru telah ditambahkan',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#addVariationValueModal').modal('hide');
          $('#addVariationValueForm').trigger('reset');
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationValueTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk tombol Edit Nilai Variasi
    $('#updateVariationValueBtn').on('click', function() {
      // Validasi form
      const form = document.getElementById('editVariationValueForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      
      // Simulasi update data
      Swal.fire({
        title: 'Berhasil!',
        text: 'Nilai variasi telah diperbarui',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#editVariationValueModal').modal('hide');
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationValueTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk tombol Hapus Nilai Variasi
    $(document).on('click', '.delete-variation-value', function() {
      const variationValueId = $(this).data('id');
      
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus nilai variasi ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Simulasi penghapusan data
          Swal.fire(
            'Terhapus!',
            'Nilai variasi telah dihapus.',
            'success'
          );
          
          // Refresh tabel (dalam implementasi nyata, ini akan mengambil data terbaru)
          // variationValueTable.ajax.reload();
        }
      });
    });
    
    // Event handler untuk modal Edit Jenis Variasi
    $('#editVariationTypeModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const variationTypeId = button.data('id');
      
      // Dalam implementasi nyata, di sini akan ada AJAX request untuk mengambil data jenis variasi
      // Untuk contoh ini, kita gunakan data dummy
      
      // Simulasi data yang diambil dari server
      const variationTypeData = {
        id: 'VAR-001',
        name: 'Warna',
        description: 'Variasi warna produk'
      };
      
      // Mengisi data ke dalam form edit
      $('#edit_variation_type_id').val(variationTypeData.id);
      $('#edit_variation_type_name').val(variationTypeData.name);
      $('#edit_variation_type_description').val(variationTypeData.description);
    });
    
    // Event handler untuk modal Edit Nilai Variasi
    $('#editVariationValueModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const variationValueId = button.data('id');
      
      // Dalam implementasi nyata, di sini akan ada AJAX request untuk mengambil data nilai variasi
      // Untuk contoh ini, kita gunakan data dummy
      
      // Simulasi data yang diambil dari server
      const variationValueData = {
        id: 'VAL-001',
        variation_type_id: '1',
        value: 'Merah',
        code: 'RED'
      };
      
      // Mengisi data ke dalam form edit
      $('#edit_variation_value_id').val(variationValueData.id);
      $('#edit_variation_value_type_id').val(variationValueData.variation_type_id);
      $('#edit_variation_value').val(variationValueData.value);
      $('#edit_variation_value_code').val(variationValueData.code);
    });
  });
</script>
@endsection