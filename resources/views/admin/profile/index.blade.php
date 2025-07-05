@extends('admin.layouts.app')

@section('title', 'Profil Admin')
@section('subtitle', 'Kelola Profil Anda')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profil Admin</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="avatar avatar-xl mb-3">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->role }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Lengkap:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Role:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-primary">{{ $user->role }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Bergabung:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Profil
                    </a>
                    <a href="{{ route('admin.profile.password') }}" class="btn btn-outline-primary">
                        <i class="bi bi-key me-2"></i>Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
