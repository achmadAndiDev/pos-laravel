<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('outlets');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by outlet for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $query->whereHas('outlets', function($q) use ($accessibleOutletIds) {
                $q->whereIn('outlet_id', $accessibleOutletIds);
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = UserRole::cases();
        $outlets = Outlet::active()->get();

        return view('admin.users.index', compact('users', 'roles', 'outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = UserRole::cases();
        $outlets = Outlet::active()->get();

        // Non-super admin can only create users for their outlets
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $outlets = $outlets->whereIn('id', $accessibleOutletIds);
        }

        return view('admin.users.create', compact('roles', 'outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(UserRole::values())],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'outlets' => ['required', 'array', 'min:1'],
            'outlets.*' => ['exists:outlets,id'],
        ]);

        // Validate outlet assignment based on role
        $role = UserRole::from($request->role);
        if (!$role->canAccessMultipleOutlets() && count($request->outlets) > 1) {
            return back()->withErrors([
                'outlets' => 'Role ' . $role->label() . ' hanya dapat memiliki satu outlet.'
            ])->withInput();
        }

        // Check if non-super admin is trying to assign outlets they don't have access to
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $invalidOutlets = array_diff($request->outlets, $accessibleOutletIds);
            if (!empty($invalidOutlets)) {
                return back()->withErrors([
                    'outlets' => 'Anda tidak memiliki akses untuk menugaskan outlet tersebut.'
                ])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Assign outlets
        $user->outlets()->sync($request->outlets);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('outlets');
        
        // Check access for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $userOutletIds = $user->outlets->pluck('id')->toArray();
            
            if (empty(array_intersect($userOutletIds, $accessibleOutletIds))) {
                abort(403, 'Anda tidak memiliki akses untuk melihat user ini.');
            }
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('outlets');
        
        // Check access for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $userOutletIds = $user->outlets->pluck('id')->toArray();
            
            if (empty(array_intersect($userOutletIds, $accessibleOutletIds))) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit user ini.');
            }
        }

        $roles = UserRole::cases();
        $outlets = Outlet::active()->get();

        // Non-super admin can only assign outlets they have access to
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $outlets = $outlets->whereIn('id', $accessibleOutletIds);
        }

        return view('admin.users.edit', compact('user', 'roles', 'outlets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check access for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $userOutletIds = $user->outlets->pluck('id')->toArray();
            
            if (empty(array_intersect($userOutletIds, $accessibleOutletIds))) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit user ini.');
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(UserRole::values())],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'outlets' => ['required', 'array', 'min:1'],
            'outlets.*' => ['exists:outlets,id'],
        ]);

        // Validate outlet assignment based on role
        $role = UserRole::from($request->role);
        if (!$role->canAccessMultipleOutlets() && count($request->outlets) > 1) {
            return back()->withErrors([
                'outlets' => 'Role ' . $role->label() . ' hanya dapat memiliki satu outlet.'
            ])->withInput();
        }

        // Check if non-super admin is trying to assign outlets they don't have access to
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $invalidOutlets = array_diff($request->outlets, $accessibleOutletIds);
            if (!empty($invalidOutlets)) {
                return back()->withErrors([
                    'outlets' => 'Anda tidak memiliki akses untuk menugaskan outlet tersebut.'
                ])->withInput();
            }
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Update outlets
        $user->outlets()->sync($request->outlets);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin tidak dapat dihapus.');
        }

        // Prevent self deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Check access for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $userOutletIds = $user->outlets->pluck('id')->toArray();
            
            if (empty(array_intersect($userOutletIds, $accessibleOutletIds))) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus user ini.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Status Super Admin tidak dapat diubah.');
        }

        // Prevent self deactivation
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        // Check access for non-super admin
        if (!auth()->user()->isSuperAdmin()) {
            $accessibleOutletIds = auth()->user()->getAccessibleOutletIds();
            $userOutletIds = $user->outlets->pluck('id')->toArray();
            
            if (empty(array_intersect($userOutletIds, $accessibleOutletIds))) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah status user ini.');
            }
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}.");
    }
}
