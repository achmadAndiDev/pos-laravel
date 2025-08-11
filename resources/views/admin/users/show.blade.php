@extends('admin.layouts.app')

@section('title', 'Detail User')

@section('css')
<style>
    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 2rem;
    }
    .info-card {
        border-left: 4px solid var(--tblr-primary);
    }
    .outlet-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.15s ease-in-out;
    }
    .outlet-card:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .permission-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        border-radius: 0.25rem;
        background-color: var(--tblr-blue-lt);
        color: var(--tblr-blue);
        border: 1px solid var(--tblr-blue-lt);
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <div class="page-pretitle">
                        Manajemen User
                    </div>
                    <h2 class="page-title">
                        Detail User
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @if(auth()->user()->hasPermission('users.edit'))
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i>
                            Edit User
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <!-- User Profile -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="user-avatar-large mx-auto mb-3" style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }}">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <p class="text-muted">{{ $user->email }}</p>
                            
                            <div class="mb-3">
                                <span class="badge 
                                    @if($user->role->value === 'super_admin') bg-red-lt text-red
                                    @elseif($user->role->value === 'admin') bg-blue-lt text-blue
                                    @elseif($user->role->value === 'staf_pembelian') bg-green-lt text-green
                                    @else bg-yellow-lt text-yellow
                                    @endif">
                                    {{ $user->role->label() }}
                                </span>
                            </div>

                            <div class="mb-3">
                                @if($user->is_active)
                                <span class="badge bg-green-lt text-green">
                                    <i class="ti ti-check"></i> Aktif
                                </span>
                                @else
                                <span class="badge bg-red-lt text-red">
                                    <i class="ti ti-x"></i> Tidak Aktif
                                </span>
                                @endif
                            </div>

                            @if(auth()->user()->hasPermission('users.edit') && !$user->isSuperAdmin() && $user->id !== auth()->id())
                            <div class="btn-list">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}" 
                                            onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                        <i class="ti ti-{{ $user->is_active ? 'user-off' : 'user-check' }}"></i>
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Informasi User</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="ti ti-mail text-muted"></i>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">Email</div>
                                        <div class="text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            @if($user->phone)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="ti ti-phone text-muted"></i>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">Telepon</div>
                                        <div class="text-muted">{{ $user->phone }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="ti ti-calendar text-muted"></i>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">Bergabung</div>
                                        <div class="text-muted">{{ $user->created_at->format('d M Y H:i') }}</div>
                                        <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="ti ti-clock text-muted"></i>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">Terakhir Update</div>
                                        <div class="text-muted">{{ $user->updated_at->format('d M Y H:i') }}</div>
                                        <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="col-md-8">
                    <!-- Role & Permissions -->
                    <div class="card info-card">
                        <div class="card-header">
                            <h3 class="card-title">Role & Permissions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ $user->role->label() }}</h4>
                                    <p class="text-muted">{{ $user->role->description() }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Permissions</h5>
                                    <div class="d-flex flex-wrap">
                                        @foreach($user->role->permissions() as $permission)
                                        <span class="permission-badge">{{ $permission }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Outlets -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Outlet yang Dapat Diakses</h3>
                            <div class="card-actions">
                                <span class="badge bg-blue-lt text-blue">{{ $user->outlets->count() }} Outlet</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse($user->outlets as $outlet)
                            <div class="outlet-card">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar avatar-sm" style="background-color: var(--tblr-primary);">
                                            {{ strtoupper(substr($outlet->code, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">{{ $outlet->full_name }}</div>
                                        <div class="text-muted small">{{ $outlet->address }}</div>
                                        @if($outlet->manager)
                                        <div class="text-muted small">Manager: {{ $outlet->manager }}</div>
                                        @endif
                                        @if($outlet->phone)
                                        <div class="text-muted small">Telepon: {{ $outlet->phone }}</div>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge {{ $outlet->isActive() ? 'bg-green-lt text-green' : 'bg-red-lt text-red' }}">
                                            {{ $outlet->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty">
                                <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_void_3ggu.svg') }}" height="128" alt=""></div>
                                <p class="empty-title">Tidak ada outlet</p>
                                <p class="empty-subtitle text-muted">
                                    User ini belum ditugaskan ke outlet manapun.
                                </p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Activity Log (Optional - if you want to add activity tracking) -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Aktivitas Terakhir</h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="status-dot status-dot-animated bg-green d-block"></span>
                                        </div>
                                        <div class="col">
                                            <div class="fw-bold">User dibuat</div>
                                            <div class="text-muted">{{ $user->created_at->format('d M Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($user->created_at != $user->updated_at)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="status-dot status-dot-animated bg-blue d-block"></span>
                                        </div>
                                        <div class="col">
                                            <div class="fw-bold">User diperbarui</div>
                                            <div class="text-muted">{{ $user->updated_at->format('d M Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection