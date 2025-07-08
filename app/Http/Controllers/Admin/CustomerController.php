<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getCustomersData($request);
        }

        return view('admin.customers.index');
    }

    /**
     * Get customers data for AJAX requests
     */
    private function getCustomersData(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        switch ($request->sort_by) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $customers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'pagination' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:customers,code',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'status' => 'required|in:active,inactive',
            'total_points' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Generate customer code if not provided
            if (empty($data['code'])) {
                $data['code'] = $this->generateCustomerCode();
            }

            $customer = Customer::create($data);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil ditambahkan!',
                    'data' => $customer
                ]);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer berhasil ditambahkan!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        }

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:customers,code,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'status' => 'required|in:active,inactive',
            'total_points' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Generate customer code if not provided
            if (empty($data['code'])) {
                $data['code'] = $this->generateCustomerCode();
            }

            $customer->update($data);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil diperbarui!',
                    'data' => $customer->fresh()
                ]);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            // You can add additional checks here if needed
            // For example, check if customer has orders
            
            $customer->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus(Customer $customer)
    {
        try {
            $customer->update([
                'status' => $customer->status === 'active' ? 'inactive' : 'active'
            ]);

            $status = $customer->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Customer berhasil {$status}!",
                    'data' => $customer->fresh()
                ]);
            }
            
            return redirect()->back()
                ->with('success', "Customer berhasil {$status}!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Add points to customer
     */
    public function addPoints(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer->addPoints($request->points);
            
            return response()->json([
                'success' => true,
                'message' => 'Poin berhasil ditambahkan!',
                'data' => $customer->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deduct points from customer
     */
    public function deductPoints(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!$customer->hasEnoughPoints($request->points)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Poin customer tidak mencukupi!'
                ], 400);
            }

            $success = $customer->deductPoints($request->points);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Poin berhasil dikurangi!',
                    'data' => $customer->fresh()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengurangi poin!'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique customer code
     */
    private function generateCustomerCode(): string
    {
        do {
            $code = 'CUST' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Customer::where('code', $code)->exists());

        return $code;
    }

    /**
     * Export customers data
     */
    public function export(Request $request)
    {
        // This method can be implemented later for CSV/Excel export
        return response()->json([
            'success' => false,
            'message' => 'Export feature will be implemented soon'
        ]);
    }

    /**
     * Import customers data
     */
    public function import(Request $request)
    {
        // This method can be implemented later for CSV/Excel import
        return response()->json([
            'success' => false,
            'message' => 'Import feature will be implemented soon'
        ]);
    }
}