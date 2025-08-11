@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('css')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    .role-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .outlet-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    .outlet-tag {
        font-size: 0.7rem;
        padding: 0.125rem 0.375rem;
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
                    <div class="page-pretitle">
                        Manajemen
                    </div>
                    <h2 class="page-title">
                        User
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @if(user_can('users.create'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="ti ti-plus"></i>
                            Tambah User
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-sm-none btn-icon">
                            <i class="ti ti-plus"></i>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar User</h3>
                            <div class="card-actions">
                                <!-- Filter Form -->
                                <form method="GET" class="d-flex gap-2">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="ti ti-search"></i>
                                        </button>
                                    </div>
                                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Semua Role</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                                            {{ $role->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @if(request()->hasAny(['search', 'role', 'status']))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="ti ti-x"></i>
                                    </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Outlet</th>
                                        <th>Status</th>
                                        <th>Bergabung</th>
                                        <th class="w-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3" style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }}">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                    @if($user->phone)
                                                    <div class="text-muted small">{{ $user->phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge role-badge 
                                                @if($user->role->value === 'super_admin') bg-red-lt text-red
                                                @elseif($user->role->value === 'admin') bg-blue-lt text-blue
                                                @elseif($user->role->value === 'staf_pembelian') bg-green-lt text-green
                                                @else bg-yellow-lt text-yellow
                                                @endif">
                                                {{ $user->role->label() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="outlet-tags">
                                                @forelse($user->outlets as $outlet)
                                                <span class="outlet-tag">{{ $outlet->code }}</span>
                                                @empty
                                                <span class="text-muted small">Tidak ada outlet</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                            <span class="badge bg-green-lt text-green">Aktif</span>
                                            @else
                                            <span class="badge bg-red-lt text-red">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-muted small">{{ $user->created_at->format('d M Y') }}</div>
                                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                @if(user_can('users.view'))
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                @endif
                                                
                                                @if(user_can('users.edit'))
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                @endif

                                                @if(auth()->user()->hasPermission('users.edit') && !$user->isSuperAdmin() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                            onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                        <i class="ti ti-{{ $user->is_active ? 'user-off' : 'user-check' }}"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                @if(auth()->user()->hasPermission('users.delete') && !$user->isSuperAdmin() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-img"><img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt=""></div>
                                                <p class="empty-title">Tidak ada user ditemukan</p>
                                                <p class="empty-subtitle text-muted">
                                                    @if(request()->hasAny(['search', 'role', 'status']))
                                                    Coba ubah filter pencarian Anda.
                                                    @else
                                                    Mulai dengan menambahkan user pertama.
                                                    @endif
                                                </p>
                                                @if(user_can('users.create'))
                                                <div class="empty-action">
                                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                                        <i class="ti ti-plus"></i>
                                                        Tambah User Pertama
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($users->hasPages())
                        <div class="card-footer">
                            {{ $users->withQueryString()->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Auto submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        // Add any additional JavaScript if needed
    });
</script>
@endsection