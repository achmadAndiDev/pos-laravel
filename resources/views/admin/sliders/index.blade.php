@extends('admin.layouts.app')

@section('title', 'Kelola Slider')
@section('subtitle', 'Manajemen Slider Halaman Utama')

@section('right-header')
<div class="btn-list">
  <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary d-none d-sm-inline-block">
    <i class="ti ti-plus"></i>
    Tambah Slider Baru
  </a>
  <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
    <i class="ti ti-dashboard"></i>
    Kembali ke Dashboard
  </a>
  <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary d-sm-none">
    <i class="ti ti-plus"></i>
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Slider</h3>
      </div>
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <div class="d-flex">
            <div>
              <i class="ti ti-check icon alert-icon"></i>
            </div>
            <div>
              <h4 class="alert-title">Sukses!</h4>
              <div class="text-secondary">{{ session('success') }}</div>
            </div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if($sliders->isEmpty())
        <div class="empty">
          <div class="empty-img">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M15 8h.01"></path>
              <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"></path>
              <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"></path>
              <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"></path>
            </svg>
          </div>
          <p class="empty-title">Belum ada slider</p>
          <p class="empty-subtitle text-secondary">
            Silakan tambahkan slider baru untuk ditampilkan di halaman utama
          </p>
          <div class="empty-action">
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
              <i class="ti ti-plus"></i>
              Tambah Slider Baru
            </a>
          </div>
        </div>
        @else
        <div class="table-responsive">
          <table class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th width="5%">Urutan</th>
                <th width="15%">Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th width="10%">Status</th>
                <th width="15%" class="w-1">Aksi</th>
              </tr>
            </thead>
            <tbody id="sliders-list">
              @foreach($sliders as $slider)
              <tr data-id="{{ $slider->id }}">
                <td class="text-center">
                  <div class="btn-list flex-nowrap">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary move-up" title="Pindah ke atas">
                      <i class="ti ti-arrow-up"></i>
                    </button>
                    <span class="badge bg-blue-lt order-number">{{ $slider->order + 1 }}</span>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary move-down" title="Pindah ke bawah">
                      <i class="ti ti-arrow-down"></i>
                    </button>
                  </div>
                </td>
                <td>
                  <span class="avatar avatar-xl rounded" style="background-image: url({{ asset('storage/' . $slider->image_path) }})"></span>
                </td>
                <td>{{ $slider->title }}</td>
                <td>{{ Str::limit($slider->description, 100) }}</td>
                <td>
                  @if($slider->is_active)
                    <span class="badge bg-success text-white">Aktif</span>
                  @else
                    <span class="badge bg-secondary text-white">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="btn-list flex-nowrap">
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                      <i class="ti ti-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-icon delete-slider" data-id="{{ $slider->id }}" title="Hapus">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Form untuk delete slider -->
<form id="delete-form" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);

    // Fungsi untuk mengatur ulang nomor urutan
    function updateOrderNumbers() {
      document.querySelectorAll('#sliders-list tr').forEach(function(row, index) {
        row.querySelector('.order-number').textContent = index + 1;
      });
    }

    // Fungsi untuk menyimpan urutan ke server
    function saveOrder() {
      const sliderIds = [];
      document.querySelectorAll('#sliders-list tr').forEach(function(row) {
        sliderIds.push(row.dataset.id);
      });

      fetch('{{ route("admin.sliders.order") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ sliders: sliderIds })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Tampilkan notifikasi sukses
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Urutan slider berhasil diperbarui',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Terjadi kesalahan saat menyimpan urutan',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
      });
    }

    // Tombol pindah ke atas
    document.querySelectorAll('.move-up').forEach(function(button) {
      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const prev = row.previousElementSibling;
        if (prev) {
          row.parentNode.insertBefore(row, prev);
          updateOrderNumbers();
          saveOrder();
        }
      });
    });

    // Tombol pindah ke bawah
    document.querySelectorAll('.move-down').forEach(function(button) {
      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const next = row.nextElementSibling;
        if (next) {
          row.parentNode.insertBefore(next, row);
          updateOrderNumbers();
          saveOrder();
        }
      });
    });

    // Tombol hapus slider
    document.querySelectorAll('.delete-slider').forEach(function(button) {
      button.addEventListener('click', function() {
        const sliderId = this.dataset.id;
        
        Swal.fire({
          title: 'Konfirmasi Hapus',
          text: 'Apakah Anda yakin ingin menghapus slider ini?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = '{{ route("admin.sliders.destroy", "") }}/' + sliderId;
            form.submit();
          }
        });
      });
    });
  });
</script>
@endsection