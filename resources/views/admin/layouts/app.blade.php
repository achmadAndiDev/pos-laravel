<!doctype html>
<html lang="id" data-bs-theme-primary="green" data-bs-theme-base="light" data-bs-theme-radius="2" data-bs-theme-font="sans-serif">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ setting('site_name', 'POS App') }} - @yield('title', 'Dashboard')</title>
    <meta name="description" content="Admin Panel - {{ setting('site_description', '') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" type="image/x-icon">
    <!-- CSS files -->
    <link href="{{ asset('tabler/dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-themes.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <!-- Custom Font -->
    <style>
      @import url('https://rsms.me/inter/inter.css');
      html {
        margin-left: 0 !important;
      }
      .btn-sm {
        --tblr-btn-padding-y: 0.27rem;
        --tblr-btn-padding-x: 0.27rem;
      }
      
      /* DataTables Styling */
      /* Styling untuk pagination */
      .dataTables_paginate .pagination {
          margin: 10px 0;
          justify-content: flex-end;
      }
      
      .dataTables_paginate .pagination .page-item .page-link {
          padding: 0.375rem 0.75rem;
          border-radius: 4px;
          margin: 0 2px;
          color: var(--tblr-primary);
          border: 1px solid #dee2e6;
      }
      
      .dataTables_paginate .pagination .page-item.active .page-link {
          background-color: var(--tblr-primary);
          border-color: var(--tblr-primary);
          color: white;
      }
      
      .dataTables_paginate .pagination .page-item.disabled .page-link {
          color: #6c757d;
          pointer-events: none;
          background-color: #fff;
          border-color: #dee2e6;
      }
      
      .dataTables_paginate .pagination .page-item:not(.active) .page-link:hover {
          background-color: #e9ecef;
      }
      
      /* Styling untuk search box dan page length select */
      .dataTables_filter input, 
      .dataTables_length select {
          height: 38px !important;
          padding: 0.375rem 0.75rem !important;
          font-size: 0.875rem !important;
          line-height: 1.5 !important;
          border-radius: 8px !important;
          border: 1px solid #dee2e6 !important;
      }
      
      .dataTables_length select {
          padding-right: 2rem !important;
          background-position: right 0.75rem center !important;
      }
      
      .dataTables_filter input:focus,
      .dataTables_length select:focus {
          border-color: var(--tblr-primary) !important;
          box-shadow: 0 0 0 0.2rem rgba(var(--tblr-primary-rgb), 0.25) !important;
          outline: 0 !important;
      }
      
      .dataTables_filter, 
      .dataTables_length {
          margin-bottom: 0.5rem !important;
      }

      /* Navbar Dropdown Styling */
      .navbar-nav .dropdown-menu {
        border: 1px solid rgba(255, 255, 255, 0.15);
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-top: 0.5rem;
      }

      .navbar-nav .dropdown-item {
        padding: 0.5rem 1rem;
        color: #374151;
        display: flex;
        align-items: center;
        border-radius: 6px;
        margin: 0.125rem 0.5rem;
        transition: all 0.15s ease-in-out;
      }

      .navbar-nav .dropdown-item:hover {
        background-color: var(--tblr-primary);
        color: white;
      }

      .navbar-nav .dropdown-item.active {
        background-color: var(--tblr-primary);
        color: white;
        font-weight: 500;
      }

      .navbar-nav .dropdown-item .nav-link-icon {
        width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      /* Active dropdown parent styling */
      .navbar-nav .nav-item.dropdown.active > .nav-link {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
      }

      /* Mobile dropdown styling */
      @media (max-width: 767.98px) {
        .navbar-nav .dropdown-menu {
          background-color: rgba(255, 255, 255, 0.1);
          border: 1px solid rgba(255, 255, 255, 0.2);
          margin-left: 1rem;
          margin-top: 0.25rem;
          position: static;
          box-shadow: none;
          backdrop-filter: none;
        }

        .navbar-nav .dropdown-item {
          color: rgba(255, 255, 255, 0.9);
          margin: 0.125rem 0.25rem;
        }

        .navbar-nav .dropdown-item:hover {
          background-color: rgba(255, 255, 255, 0.2);
          color: white;
        }

        .navbar-nav .dropdown-item.active {
          background-color: rgba(255, 255, 255, 0.3);
          color: white;
        }
      }
    </style>
    <!-- CSS per page -->
    @yield('css')
  </head>
  <body>
    <!-- Global Theme Script -->
    <script src="{{ asset('tabler/dist/js/tabler-theme.min.js') }}"></script>
    
    <div class="page">
      <!-- Navbar Overlap -->
      <header class="navbar navbar-expand-md navbar-overlap d-print-none" data-bs-theme="dark">
        <div class="container-xl">
          <!-- Toggle Button untuk Mobile -->
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar-menu"
            aria-controls="navbar-menu"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          
          <!-- Navbar Logo -->
          <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <div style="display: flex; align-items: center;">
              {{-- <img src="{{ asset(setting('site_logo', 'client/img/logo.png')) }}" alt="Logo" width="35px" class="me-2"> --}}
              <span>Majeko - {{ strtoupper(setting('site_name', 'Admin')) }}</span>
            </div>
          </div>
          
          <!-- Navbar Right Side -->
          <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex">
              <!-- Home Page Link -->
              <div class="nav-item">
                <a href="{{ url('/') }}" class="nav-link px-0" title="Buka Halaman Toko" data-bs-toggle="tooltip" data-bs-placement="bottom">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                  </svg>
                </a>
              </div>
              <!-- Theme Switcher -->
              <div class="nav-item">
                <a href="?theme=dark" class="nav-link px-0 hide-theme-light" title="Aktifkan mode gelap" data-bs-toggle="tooltip" data-bs-placement="bottom">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                  </svg>
                </a>
              </div>
            </div>
            
            <!-- User Menu -->
            <div class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm"><i class="bi bi-person-circle"></i></span>
                <div class="d-none d-xl-block ps-2">
                  <div>{{ Auth::user()->name ?? 'Admin' }}</div>
                  <div class="mt-1 small text-muted">{{ Auth::user()->role ?? 'Administrator' }}</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="#" class="dropdown-item">
                  <i class="bi bi-person-gear me-2"></i> Profil
                </a>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-gear me-2"></i> Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <form action="#" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                  </button>
                </form>
              </div>
            </div>
          </div>
          
          <!-- Navbar Menu -->
          <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
              <!-- Dashboard -->
              <li class="nav-item {{ request()->is('admin') || request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                  <span class="nav-link-icon d-inline-block">
                    <i class="ti ti-dashboard"></i>
                  </span>
                  <span class="nav-link-title">Dashboard</span>
                </a>
              </li>

              <!-- Master Data -->
              <li class="nav-item dropdown {{ request()->is('admin/outlets*') || request()->is('admin/customers*') || request()->is('admin/product-categories*') || request()->is('admin/products*') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#navbar-master" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-database"></i>
                  </span>
                  <span class="nav-link-title">Master Data</span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item {{ request()->is('admin/outlets*') ? 'active' : '' }}" href="{{ route('admin.outlets.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-building-store"></i>
                    </span>
                    Outlet
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/customers*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-users"></i>
                    </span>
                    Customer
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/product-categories*') ? 'active' : '' }}" href="{{ route('admin.product-categories.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-category"></i>
                    </span>
                    Kategori
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-package"></i>
                    </span>
                    Product
                  </a>
                </div>
              </li>

              <!-- Transaksi -->
              <li class="nav-item dropdown {{ request()->is('admin/purchases*') || request()->is('admin/sales*') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#navbar-transaction" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-shopping-cart"></i>
                  </span>
                  <span class="nav-link-title">Transaksi</span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item {{ request()->is('admin/purchases*') ? 'active' : '' }}" href="{{ route('admin.purchases.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-truck"></i>
                    </span>
                    Pembelian
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/sales*') ? 'active' : '' }}" href="{{ route('admin.sales.index') }}">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-cash"></i>
                    </span>
                    Penjualan
                  </a>
                </div>
              </li>

              <!-- Perhitungan -->
              <li class="nav-item dropdown {{ request()->is('admin/calculations*') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#navbar-calculation" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-calculator"></i>
                  </span>
                  <span class="nav-link-title">Perhitungan</span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item {{ request()->is('admin/calculations/quantity*') ? 'active' : '' }}" href="#" onclick="alert('Fitur Perhitungan Jumlah belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-sum"></i>
                    </span>
                    Jumlah Penjualan
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/calculations/profit*') ? 'active' : '' }}" href="#" onclick="alert('Fitur Perhitungan Laba belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-trending-up"></i>
                    </span>
                    Laba Penjualan
                  </a>
                </div>
              </li>

              <!-- Laporan -->
              <li class="nav-item dropdown {{ request()->is('admin/reports*') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#navbar-reports" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-file-report"></i>
                  </span>
                  <span class="nav-link-title">Laporan</span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item {{ request()->is('admin/reports/purchases*') ? 'active' : '' }}" href="#" onclick="alert('Laporan Pembelian belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-truck"></i>
                    </span>
                    Pembelian
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/reports/sales*') ? 'active' : '' }}" href="#" onclick="alert('Laporan Penjualan belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-cash"></i>
                    </span>
                    Penjualan
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/reports/profit*') ? 'active' : '' }}" href="#" onclick="alert('Laporan Laba belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-trending-up"></i>
                    </span>
                    Laba
                  </a>
                </div>
              </li>

              <!-- Manajemen Akses -->
              <li class="nav-item dropdown {{ request()->is('admin/access*') || request()->is('admin/users*') || request()->is('admin/roles*') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#navbar-access" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-shield-lock"></i>
                  </span>
                  <span class="nav-link-title">Manajemen Akses</span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item {{ request()->is('admin/users*') ? 'active' : '' }}" href="#" onclick="alert('Manajemen User belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-user-cog"></i>
                    </span>
                    User
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/roles*') ? 'active' : '' }}" href="#" onclick="alert('Manajemen Role belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-key"></i>
                    </span>
                    Role & Permission
                  </a>
                  <a class="dropdown-item {{ request()->is('admin/access/logs*') ? 'active' : '' }}" href="#" onclick="alert('Log Aktivitas belum tersedia')">
                    <span class="nav-link-icon d-inline-block me-2">
                      <i class="ti ti-history"></i>
                    </span>
                    Log Aktivitas
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </header>
      
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none text-white" aria-label="Page header">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col-xs-12 sm-12 col-auto me-auto">
              <!-- Page pre-title -->
              <div class="page-pretitle">@yield('title')</div>
              <h2 class="page-title">@yield('subtitle')</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-auto">
              @yield('right-header')
            </div>
          </div>
        </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
        
        <!-- Footer -->
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    <!-- <a href="/admin/help" class="link-secondary">Bantuan</a> -->
                  </li>
                  <li class="list-inline-item">
                    <!-- <a href="/admin/settings" class="link-secondary">Pengaturan</a> -->
                  </li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; 2025
                    <a href="." class="link-secondary">POS App</a>.
                    All rights reserved.
                  </li>
                  <li class="list-inline-item">
                    <a href="#" class="link-secondary" rel="noopener">v1.0.0</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Core JS (Tabler includes Bootstrap) -->
     <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('tabler')}}/dist/libs/litepicker/dist/litepicker.js?1748415868" defer></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
      localStorage.setItem('tabler-theme-base', 'light');
      localStorage.setItem('tabler-theme-radius', '2');
      localStorage.setItem('tabler-theme-font', 'sans-serif');
      localStorage.setItem('tabler-theme-primary', 'green');
    </script>
    {{-- <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script> --}}
    <script src="{{ asset('tabler/dist/js/tabler.min.js') }}"></script>
    <!-- DataTables Default Configuration -->
    <script>
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      // window.OneSignalDeferred = window.OneSignalDeferred || [];
      // OneSignalDeferred.push(async function(OneSignal) {
      //   await OneSignal.init({
      //     appId: "DUMMY_ONESIGNAL_APP_ID",
      //     safari_web_id: "web.onesignal.auto.29ad6177-53c3-46b5-8017-0e7b95131b37",
      //     notifyButton: {
      //       enable: true,
      //     },
      //   });
      // });
      
      // Konfigurasi default untuk toastr
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      
      // Show flash messages
      @if(session('swal_success'))
        @php $swal = session('swal_success'); @endphp
        Swal.fire({
          title: '{{ $swal['title'] ?? 'Berhasil!' }}',
          text: '{{ $swal['text'] ?? '' }}',
          icon: '{{ $swal['icon'] ?? 'success' }}',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      @elseif(session('success'))
        toastr.success('{{ session('success') }}');
      @endif
      
      @if(session('swal_error'))
        @php $swal = session('swal_error'); @endphp
        Swal.fire({
          title: '{{ $swal['title'] ?? 'Error!' }}',
          text: '{{ $swal['text'] ?? '' }}',
          icon: '{{ $swal['icon'] ?? 'error' }}',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      @elseif(session('error'))
        toastr.error('{{ session('error') }}');
      @endif
      
      @if(session('warning'))
        toastr.warning('{{ session('warning') }}');
      @endif
      
      @if(session('info'))
        toastr.info('{{ session('info') }}');
      @endif
      // Default DataTables configuration
      // $.extend(true, $.fn.dataTable.defaults, {
      //   language: {
      //     emptyTable: "Tidak ada data yang tersedia pada tabel ini",
      //     info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      //     infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
      //     infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
      //     infoPostFix: "",
      //     thousands: ".",
      //     lengthMenu: "Tampilkan _MENU_ entri",
      //     loadingRecords: "Sedang memuat...",
      //     processing: "Sedang memproses...",
      //     search: "Cari:",
      //     zeroRecords: "Tidak ditemukan data yang sesuai",
      //     paginate: {
      //       first: "Pertama",
      //       last: "Terakhir",
      //       next: "Selanjutnya",
      //       previous: "Sebelumnya"
      //     },
      //     aria: {
      //       sortAscending: ": aktifkan untuk mengurutkan kolom ke atas",
      //       sortDescending: ": aktifkan untuk mengurutkan kolom ke bawah"
      //     }
      //   },
      //   pagingType: "full_numbers",
      //   dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
      //        "<'row'<'col-sm-12'tr>>" +
      //        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      //   lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      //   drawCallback: function() {
      //     $('.dataTables_paginate > .pagination').addClass('pagination-sm');
      //   },
      //   initComplete: function() {
      //     // Menambahkan kelas Bootstrap pada elemen search dan length
      //     $('.dataTables_filter input').addClass('form-control');
      //     $('.dataTables_length select').addClass('form-select');
          
      //     // Menambahkan label yang lebih jelas
      //     $('.dataTables_filter label').contents().filter(function() {
      //       return this.nodeType === 3;
      //     }).replaceWith('<span class="me-2">Cari:</span>');
      //   }
      // });
      // Helper function untuk format tanggal dengan timezone Asia/Jakarta
      function formatDateTime(dateString) {
        if (!dateString) return '';
        
        // Buat objek Date dari string tanggal
        const date = new Date(dateString);
        
        // Opsi untuk format tanggal dan waktu Indonesia
        const options = {
          timeZone: 'Asia/Jakarta',
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        };
        
        // Format tanggal dengan timezone Asia/Jakarta
        return date.toLocaleString('id-ID', options);
      }
      
      // Helper function untuk render status (umum)
      function renderStatus(status) {
        return status ? 
          '<span class="badge bg-success text-white">Aktif</span>' : 
          '<span class="badge bg-danger text-white">Tidak Aktif</span>';
      }
      
      /**
       * Toast Helper Functions
       * Fungsi untuk menampilkan notifikasi toast dengan konfigurasi yang konsisten
       */
      
      // Konfigurasi dasar untuk semua toast
      const toastBaseConfig = {
        closeButton: true,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
      };
      
      /**
       * Menampilkan pesan sukses
       * @param {string} message - Pesan yang akan ditampilkan
       * @param {string} title - Judul pesan (opsional)
       */
      function showSuccessToast(message, title = 'Sukses') {
        toastr.options = {
          ...toastBaseConfig,
          timeOut: "3000", // Tampilkan selama 3 detik
        };
        toastr.success(message, title);
      }
      
      /**
       * Menampilkan pesan error
       * @param {string|object} message - Pesan error atau objek error
       * @param {string} title - Judul pesan (opsional)
       */
      function showErrorToast(message, title = 'Error') {
        // Jika message adalah objek (misalnya response error dari ajax)
        if (typeof message === 'object') {
          // Jika ada responseJSON dengan errors (format Laravel validation)
          if (message.responseJSON && message.responseJSON.errors) {
            const errors = message.responseJSON.errors;
            // Tampilkan semua pesan error
            for (const key in errors) {
              if (Object.hasOwnProperty.call(errors, key)) {
                const errorMsg = errors[key][0]; // Ambil pesan pertama
                toastr.options = {
                  ...toastBaseConfig,
                  timeOut: "0", // Tampilkan terus sampai user menutup
                };
                toastr.error(errorMsg, title);
              }
            }
            return;
          }
          
          // Jika ada responseJSON dengan message
          if (message.responseJSON && message.responseJSON.message) {
            message = message.responseJSON.message;
          } else if (message.statusText) {
            message = message.statusText;
          } else {
            message = 'Terjadi kesalahan. Silakan coba lagi.';
          }
        }
        
        toastr.options = {
          ...toastBaseConfig,
          timeOut: "0", // Tampilkan terus sampai user menutup
        };
        toastr.error(message, title);
      }
      
      /**
       * Menampilkan pesan informasi
       * @param {string} message - Pesan yang akan ditampilkan
       * @param {string} title - Judul pesan (opsional)
       */
      function showInfoToast(message, title = 'Informasi') {
        toastr.options = {
          ...toastBaseConfig,
          timeOut: "5000", // Tampilkan selama 5 detik
        };
        toastr.info(message, title);
      }
      
      /**
       * Menampilkan pesan peringatan
       * @param {string} message - Pesan yang akan ditampilkan
       * @param {string} title - Judul pesan (opsional)
       */
      function showWarningToast(message, title = 'Peringatan') {
        toastr.options = {
          ...toastBaseConfig,
          timeOut: "5000", // Tampilkan selama 5 detik
        };
        toastr.warning(message, title);
      }
    </script>


    
    <!-- JS per page -->
    @yield('scripts')
  </body>
</html>