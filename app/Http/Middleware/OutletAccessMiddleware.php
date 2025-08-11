<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OutletAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Get outlet ID from route parameter
        $outletId = $request->route('outlet') ?? $request->route('outlet_id');
        
        if ($outletId) {
            // Check if user can access this outlet
            if (!$user->canAccessOutlet($outletId)) {
                abort(403, 'Anda tidak memiliki akses ke outlet ini.');
            }
        }

        return $next($request);
    }
}
