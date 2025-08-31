<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OutletSelectionController extends Controller
{
    /**
     * Store selected outlet into session
     */
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        $user = Auth::user();
        $outletId = $request->input('outlet_id');

        // If not provided, clear selection
        if (!$outletId) {
            Session::forget('selected_outlet_id');
            return back()->with('success', 'Filter outlet dihapus.');
        }

        // Verify access: super admin can select any, others must have relation
        if (!$user->canAccessOutlet((int)$outletId)) {
            return back()->with('error', 'Anda tidak memiliki akses ke outlet yang dipilih.');
        }

        Session::put('selected_outlet_id', (int)$outletId);
        return back()->with('success', 'Outlet berhasil dipilih.');
    }
}