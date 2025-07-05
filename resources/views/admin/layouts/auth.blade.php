<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ setting('site_name', 'Pindon Outdoor') }} - @yield('title', 'Login')</title>
  <meta name="description" content="{{ setting('site_description', 'Toko perlengkapan outdoor terpercaya di Indonesia.') }}">
  <meta name="theme-color" content="#206bc4">
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" type="image/x-icon">
  
  <!-- CSS files -->
  <link href="{{ asset('tabler/dist/css/tabler.min.css') }}" rel="stylesheet">
  <link href="{{ asset('tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet">
  <link href="{{ asset('tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet">
  <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  
  @yield('css')
  
  <style>
    @import url('https://rsms.me/inter/inter.css');
    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }
    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
    
    .page-body-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    
    .page-body-wrapper::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NiIgaGVpZ2h0PSIxMDAiPgo8cmVjdCB3aWR0aD0iNTYiIGhlaWdodD0iMTAwIiBmaWxsPSIjZjhmOWZhIj48L3JlY3Q+CjxwYXRoIGQ9Ik0yOCA2NkwwIDUwTDAgMTZMMjggMEw1NiAxNkw1NiA1MEwyOCA2NkwyOCAxMDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2VlZWVlZSIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+CjxwYXRoIGQ9Ik0yOCAwTDI4IDY2TDAgNTBMMCA1MEwyOCA2NkwyOCA2Nkw1NiA1MEw1NiA1MEwyOCA2NkwyOCA2NkwyOCAwTDI4IDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2VlZWVlZSIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+Cjwvc3ZnPg==');
      opacity: 0.3;
      z-index: -1;
    }
    
    .auth-card {
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      border-radius: 10px;
    }
    
    .auth-logo {
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .auth-logo img {
      height: 36px;
    }
    
    .auth-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .auth-subtitle {
      color: var(--tblr-muted);
      text-align: center;
      margin-bottom: 1.5rem;
    }
    
    .auth-footer {
      text-align: center;
      margin-top: 2rem;
      color: var(--tblr-muted);
    }
  </style>
</head>
<body class="d-flex flex-column">
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="page-body-wrapper">
        @yield('content')
      </div>
    </div>
  </div>
  
  <!-- Core JS -->
  <script src="{{ asset('tabler/dist/js/tabler.min.js') }}" defer></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    // Add entrance animation to auth card
    $(document).ready(function() {
      setTimeout(function() {
        $('.auth-card').addClass('animate__animated animate__fadeInUp');
      }, 200);
    });
  </script>
  
  @yield('js')
</body>
</html>