@extends('admin.layouts.auth')

@section('title', 'Login')

@section('content')
<div class="container-tight py-4">
  <div class="card card-md auth-card">
    <div class="card-body">
      <div class="auth-logo">
        <a href="#" class="navbar-brand navbar-brand-autodark"><img src="{{ asset('logo.svg') }}" height="36" alt=""></a>
      </div>
      <h2 class="auth-title">Masuk ke Akun</h2>
      <p class="auth-subtitle">Silakan login untuk mengakses dashboard.</p>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('login.attempt') }}" method="POST" autocomplete="on" novalidate>
        @csrf
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-2">
          <label class="form-label">
            Password
            <span class="form-label-description">
              <a href="#" onclick="return false;">Lupa password?</a>
            </span>
          </label>
          <div class="input-group input-group-flat">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <span class="input-group-text">
              <a href="#" class="link-secondary" data-bs-toggle="tooltip" aria-label="Show password" onclick="event.preventDefault(); const i=this.parentNode.previousElementSibling; i.type=i.type==='password'?'text':'password';"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
              </a>
            </span>
          </div>
        </div>
        <div class="mb-2">
          <label class="form-check">
            <input type="checkbox" class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span class="form-check-label">Ingat saya</span>
          </label>
        </div>
        <div class="form-footer">
          <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </div>
      </form>
    </div>
    <div class="card-footer auth-footer">
      &copy; {{ date('Y') }} {{ setting('site_name', 'Pindon Outdoor') }}
    </div>
  </div>
</div>
@endsection