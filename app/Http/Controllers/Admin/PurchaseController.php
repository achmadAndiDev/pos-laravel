<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['outlet', 'purchaseItems'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::active()->get();
        $products = Product::with(['outlet', 'productCategory'])
            ->active()
            ->orderBy('name')
            ->get();
        
        return view('admin.purchases.create', compact('outlets', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:20',
            'supplier_address' => 'nullable|string',
            'purchase_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                // Create purchase
                $purchase = Purchase::create([
                    'code' => Purchase::generateCode(),
                    'outlet_id' => $request->outlet_id,
                    'supplier_name' => $request->supplier_name,
                    'supplier_phone' => $request->supplier_phone,
                    'supplier_address' => $request->supplier_address,
                    'purchase_date' => $request->purchase_date,
                    'invoice_number' => $request->invoice_number,
                    'tax_amount' => $request->tax_amount ?? 0,
                    'discount_amount' => $request->discount_amount ?? 0,
                    'notes' => $request->notes,
                    'created_by' => 'Admin', // You can change this to auth user
                ]);

                // Create purchase items
                foreach ($request->items as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            });

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['outlet', 'purchaseItems.product']);
        
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        if (!$purchase->canBeEdited()) {
            return redirect()->route('admin.purchases.show', $purchase)
                ->with('error', 'Pembelian tidak dapat diedit karena sudah selesai atau dibatalkan!');
        }

        $outlets = Outlet::active()->get();
        $products = Product::with(['outlet', 'productCategory'])
            ->active()
            ->orderBy('name')
            ->get();
        
        $purchase->load(['purchaseItems.product']);
        
        return view('admin.purchases.edit', compact('purchase', 'outlets', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if (!$purchase->canBeEdited()) {
            return redirect()->route('admin.purchases.show', $purchase)
                ->with('error', 'Pembelian tidak dapat diedit karena sudah selesai atau dibatalkan!');
        }

        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:20',
            'supplier_address' => 'nullable|string',
            'purchase_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $purchase) {
                // Update purchase
                $purchase->update([
                    'outlet_id' => $request->outlet_id,
                    'supplier_name' => $request->supplier_name,
                    'supplier_phone' => $request->supplier_phone,
                    'supplier_address' => $request->supplier_address,
                    'purchase_date' => $request->purchase_date,
                    'invoice_number' => $request->invoice_number,
                    'tax_amount' => $request->tax_amount ?? 0,
                    'discount_amount' => $request->discount_amount ?? 0,
                    'notes' => $request->notes,
                ]);

                // Delete existing items
                $purchase->purchaseItems()->delete();

                // Create new purchase items
                foreach ($request->items as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            });

            return redirect()->route('admin.purchases.show', $purchase)
                ->with('success', 'Pembelian berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        if ($purchase->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Pembelian yang sudah selesai tidak dapat dihapus!');
        }

        try {
            $purchase->delete();
            
            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Complete purchase and update stock
     */
    public function complete(Purchase $purchase)
    {
        if (!$purchase->canBeCompleted()) {
            return redirect()->back()
                ->with('error', 'Pembelian tidak dapat diselesaikan!');
        }

        try {
            if ($purchase->complete()) {
                return redirect()->back()
                    ->with('success', 'Pembelian berhasil diselesaikan dan stok produk telah diupdate!');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal menyelesaikan pembelian!');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel purchase
     */
    public function cancel(Purchase $purchase)
    {
        try {
            if ($purchase->cancel()) {
                return redirect()->back()
                    ->with('success', 'Pembelian berhasil dibatalkan!');
            } else {
                return redirect()->back()
                    ->with('error', 'Pembelian yang sudah selesai tidak dapat dibatalkan!');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get products by outlet (AJAX)
     */
    public function getProductsByOutlet(Request $request)
    {
        $outletId = $request->outlet_id;
        
        $products = Product::with('productCategory')
            ->where('outlet_id', $outletId)
            ->active()
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'category' => $product->productCategory->name,
                    'current_stock' => $product->stock,
                    'unit' => $product->unit,
                    'purchase_price' => $product->purchase_price,
                ];
            });

        return response()->json($products);
    }

    /**
     * Display purchase report
     */
    public function report(Request $request)
    {
        $query = Purchase::with(['outlet', 'purchaseItems.product']);

        // Filter by outlet
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        // Filter by supplier
        if ($request->filled('supplier_name')) {
            $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%');
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->get();

        // Calculate totals
        $totalPurchases = $purchases->count();
        $totalAmount = $purchases->sum('total_amount');
        $completedPurchases = $purchases->where('status', 'completed')->count();
        $draftPurchases = $purchases->where('status', 'draft')->count();

        $outlets = Outlet::active()->get();

        return view('admin.purchases.report', compact(
            'purchases', 
            'outlets', 
            'totalPurchases', 
            'totalAmount', 
            'completedPurchases', 
            'draftPurchases'
        ));
    }

    /**
     * Print purchase report
     */
    public function printReport(Request $request)
    {
        $query = Purchase::with(['outlet', 'purchaseItems.product']);

        // Apply same filters as report
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }
        if ($request->filled('supplier_name')) {
            $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%');
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->get();

        // Calculate totals
        $totalPurchases = $purchases->count();
        $totalAmount = $purchases->sum('total_amount');
        $completedPurchases = $purchases->where('status', 'completed')->count();
        $draftPurchases = $purchases->where('status', 'draft')->count();

        return view('admin.purchases.print', compact(
            'purchases', 
            'totalPurchases', 
            'totalAmount', 
            'completedPurchases', 
            'draftPurchases'
        ));
    }

    /**
     * Export purchase report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Purchase::with(['outlet', 'purchaseItems.product']);

        // Apply same filters as report
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }
        if ($request->filled('supplier_name')) {
            $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%');
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->get();

        // Calculate totals
        $totalPurchases = $purchases->count();
        $totalAmount = $purchases->sum('total_amount');
        $completedPurchases = $purchases->where('status', 'completed')->count();
        $draftPurchases = $purchases->where('status', 'draft')->count();

        $pdf = Pdf::loadView('admin.purchases.pdf', compact(
            'purchases', 
            'totalPurchases', 
            'totalAmount', 
            'completedPurchases', 
            'draftPurchases'
        ));

        return $pdf->download('laporan-pembelian-' . date('Y-m-d') . '.pdf');
    }
}
