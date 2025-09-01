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
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('admin.customers.index', compact('customers'));
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
            'code' => 'required|string|max:50|unique:customers,code',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'status' => 'required|in:active,inactive',
            'total_points' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Set default points if not provided
            if (!isset($data['total_points']) || $data['total_points'] === '') {
                $data['total_points'] = 0;
            }

            $customer = Customer::create($data);
            
            return redirect()->route('kasir.customers.index')
                ->with('success', "Customer {$customer->name} berhasil ditambahkan!")
                ->with('swal_success', [
                    'title' => 'Berhasil!',
                    'text' => "Customer {$customer->name} berhasil ditambahkan",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
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
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:customers,code,' . $customer->id,
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'status' => 'required|in:active,inactive',
            'total_points' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Set default points if not provided
            if (!isset($data['total_points']) || $data['total_points'] === '') {
                $data['total_points'] = 0;
            }

            $customer->update($data);
            
            return redirect()->route('kasir.customers.index')
                ->with('success', "Customer {$customer->name} berhasil diperbarui!")
                ->with('swal_success', [
                    'title' => 'Berhasil!',
                    'text' => "Customer {$customer->name} berhasil diperbarui",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
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
            // For example, check if customer has orders, etc.
            
            $customerName = $customer->name;
            $customer->delete();
            
            return redirect()->route('kasir.customers.index')
                ->with('success', "Customer {$customerName} berhasil dihapus!")
                ->with('swal_success', [
                    'title' => 'Berhasil Dihapus!',
                    'text' => "Customer {$customerName} berhasil dihapus",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
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
            
            return redirect()->back()
                ->with('success', "Customer {$customer->name} berhasil {$status}!")
                ->with('swal_success', [
                    'title' => 'Berhasil!',
                    'text' => "Customer {$customer->name} berhasil {$status}",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique customer code
     */
    private function generateCustomerCode()
    {
        do {
            $code = 'CUST' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Customer::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Add points to customer
     */
    public function addPoints(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $customer->addPoints($request->points);
            
            return redirect()->back()
                ->with('success', "Berhasil menambahkan {$request->points} poin ke customer {$customer->name}!")
                ->with('swal_success', [
                    'title' => 'Poin Ditambahkan!',
                    'text' => "Berhasil menambahkan {$request->points} poin ke customer {$customer->name}",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Deduct points from customer
     */
    public function deductPoints(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            if (!$customer->hasEnoughPoints($request->points)) {
                return redirect()->back()
                    ->with('error', 'Poin customer tidak mencukupi!')
                    ->with('swal_error', [
                        'title' => 'Poin Tidak Mencukupi!',
                        'text' => "Poin customer saat ini hanya {$customer->total_points}. Tidak dapat mengurangi {$request->points} poin.",
                        'icon' => 'error'
                    ]);
            }

            $customer->deductPoints($request->points);
            
            return redirect()->back()
                ->with('success', "Berhasil mengurangi {$request->points} poin dari customer {$customer->name}!")
                ->with('swal_success', [
                    'title' => 'Poin Dikurangi!',
                    'text' => "Berhasil mengurangi {$request->points} poin dari customer {$customer->name}",
                    'icon' => 'success'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}