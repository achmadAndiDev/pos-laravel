<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['outlet', 'customer'])
            ->orderBy('created_at', 'desc');

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
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        // Search by code or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $sales = $query->paginate(15);
        $outlets = Outlet::where('status', 'active')->get();

        return view('admin.sales.index', compact('sales', 'outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();
        
        return view('admin.sales.create', compact('outlets', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,transfer,e_wallet',
            'paid_amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                // Create sale
                $sale = Sale::create([
                    'code' => Sale::generateCode(),
                    'outlet_id' => $request->outlet_id,
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date,
                    'payment_method' => $request->payment_method,
                    'paid_amount' => $request->paid_amount,
                    'tax_amount' => $request->tax_amount ?? 0,
                    'discount_amount' => $request->discount_amount ?? 0,
                    'notes' => $request->notes,
                    'created_by' => auth()->user()->name ?? 'System',
                ]);

                // Create sale items
                foreach ($request->items as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }

                // Calculate change amount
                $sale->change_amount = max(0, $sale->paid_amount - $sale->total_amount);
                $sale->save();

                // Complete sale if requested
                if ($request->has('complete_sale')) {
                    $sale->complete();
                }
            });

            $message = $request->has('complete_sale') ? 'Penjualan berhasil dibuat dan diselesaikan' : 'Penjualan berhasil dibuat';
            return redirect()->route('admin.sales.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat penjualan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['outlet', 'customer', 'saleItems.product']);
        
        return view('admin.sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        if (!$sale->canBeEdited()) {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Penjualan tidak dapat diedit');
        }

        $outlets = Outlet::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();
        $sale->load(['saleItems.product']);
        
        return view('admin.sales.edit', compact('sale', 'outlets', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        if (!$sale->canBeEdited()) {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Penjualan tidak dapat diedit');
        }

        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,transfer,e_wallet',
            'paid_amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $sale) {
                // Update sale
                $sale->update([
                    'outlet_id' => $request->outlet_id,
                    'customer_id' => $request->customer_id,
                    'sale_date' => $request->sale_date,
                    'payment_method' => $request->payment_method,
                    'paid_amount' => $request->paid_amount,
                    'tax_amount' => $request->tax_amount ?? 0,
                    'discount_amount' => $request->discount_amount ?? 0,
                    'notes' => $request->notes,
                ]);

                // Delete existing items
                $sale->saleItems()->delete();

                // Create new sale items
                foreach ($request->items as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }

                // Calculate change amount
                $sale->change_amount = max(0, $sale->paid_amount - $sale->total_amount);
                $sale->save();

                // Complete sale if requested
                if ($request->has('complete_sale')) {
                    $sale->complete();
                }
            });

            $message = $request->has('complete_sale') ? 'Penjualan berhasil diperbarui dan diselesaikan' : 'Penjualan berhasil diperbarui';
            return redirect()->route('admin.sales.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui penjualan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        if ($sale->status === 'completed') {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Penjualan yang sudah selesai tidak dapat dihapus');
        }

        try {
            $sale->delete();
            
            return redirect()->route('admin.sales.index')
                ->with('success', 'Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Complete sale
     */
    public function complete(Sale $sale)
    {
        if (!$sale->canBeCompleted()) {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Penjualan tidak dapat diselesaikan');
        }

        if ($sale->complete()) {
            return redirect()->route('admin.sales.index')
                ->with('success', 'Penjualan berhasil diselesaikan');
        } else {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Gagal menyelesaikan penjualan');
        }
    }

    /**
     * Cancel sale
     */
    public function cancel(Sale $sale)
    {
        if ($sale->cancel()) {
            return redirect()->route('admin.sales.index')
                ->with('success', 'Penjualan berhasil dibatalkan');
        } else {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Gagal membatalkan penjualan');
        }
    }

    /**
     * Get products by outlet for AJAX
     */
    public function getProductsByOutlet(Request $request)
    {
        $outletId = $request->outlet_id;
        
        if (!$outletId) {
            return response()->json([]);
        }

        $products = Product::where('outlet_id', $outletId)
            ->where('status', 'active')
            ->where('is_sellable', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'Tanpa Kategori',
                    'selling_price' => $product->selling_price,
                    'stock' => $product->stock,
                    'unit' => $product->unit,
                    'image' => $product->image,
                ];
            });

        return response()->json($products);
    }

    /**
     * Display sales report
     */
    public function report(Request $request)
    {
        $query = Sale::with(['outlet', 'customer', 'saleItems.product']);

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
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        // Filter by customer
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        // Calculate totals
        $totalSales = $sales->count();
        $totalAmount = $sales->sum('total_amount');
        $totalPaidAmount = $sales->sum('paid_amount');
        $completedSales = $sales->where('status', 'completed')->count();
        $draftSales = $sales->where('status', 'draft')->count();

        $outlets = Outlet::active()->get();

        return view('admin.sales.report', compact(
            'sales', 
            'outlets', 
            'totalSales', 
            'totalAmount', 
            'totalPaidAmount',
            'completedSales', 
            'draftSales'
        ));
    }

    /**
     * Print sales report
     */
    public function printReport(Request $request)
    {
        $query = Sale::with(['outlet', 'customer', 'saleItems.product']);

        // Apply same filters as report
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        // Calculate totals
        $totalSales = $sales->count();
        $totalAmount = $sales->sum('total_amount');
        $totalPaidAmount = $sales->sum('paid_amount');
        $completedSales = $sales->where('status', 'completed')->count();
        $draftSales = $sales->where('status', 'draft')->count();

        return view('admin.sales.print', compact(
            'sales', 
            'totalSales', 
            'totalAmount', 
            'totalPaidAmount',
            'completedSales', 
            'draftSales'
        ));
    }

    /**
     * Export sales report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Sale::with(['outlet', 'customer', 'saleItems.product']);

        // Apply same filters as report
        if ($request->filled('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        // Calculate totals
        $totalSales = $sales->count();
        $totalAmount = $sales->sum('total_amount');
        $totalPaidAmount = $sales->sum('paid_amount');
        $completedSales = $sales->where('status', 'completed')->count();
        $draftSales = $sales->where('status', 'draft')->count();

        $pdf = Pdf::loadView('admin.sales.pdf', compact(
            'sales', 
            'totalSales', 
            'totalAmount', 
            'totalPaidAmount',
            'completedSales', 
            'draftSales'
        ));

        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Print receipt for web version
     */
    public function printReceipt(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Hanya penjualan yang sudah selesai yang dapat dicetak');
        }

        $sale->load(['outlet', 'customer', 'saleItems.product']);
        
        return view('admin.sales.receipt-print', compact('sale'));
    }

    /**
     * Generate receipt data for thermal printer (58mm)
     */
    public function thermalReceipt(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penjualan yang sudah selesai yang dapat dicetak'
            ]);
        }

        $sale->load(['outlet', 'customer', 'saleItems.product']);
        
        // Format data untuk thermal printer 58mm
        $receiptData = [
            'outlet_name' => $sale->outlet->name,
            'sale_code' => $sale->code,
            'sale_date' => $sale->sale_date->format('d/m/Y H:i'),
            'cashier' => $sale->created_by ?? 'System',
            'customer' => $sale->customer ? $sale->customer->name : 'Walk-in Customer',
            'items' => $sale->saleItems->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ];
            }),
            'subtotal' => $sale->subtotal,
            'tax_amount' => $sale->tax_amount,
            'discount_amount' => $sale->discount_amount,
            'total_amount' => $sale->total_amount,
            'paid_amount' => $sale->paid_amount,
            'change_amount' => $sale->change_amount,
            'payment_method' => $sale->payment_method_text,
        ];

        return response()->json([
            'success' => true,
            'data' => $receiptData
        ]);
    }

    /**
     * Send receipt to WhatsApp
     */
    public function sendToWhatsApp(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penjualan yang sudah selesai yang dapat dikirim'
            ]);
        }

        if (!$sale->customer || !$sale->customer->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak memiliki nomor WhatsApp'
            ]);
        }

        $sale->load(['outlet', 'customer', 'saleItems.product']);
        
        // Format pesan WhatsApp
        $message = "*STRUK PENJUALAN*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*{$sale->outlet->name}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "No: {$sale->code}\n";
        $message .= "Tanggal: {$sale->sale_date->format('d/m/Y H:i')}\n";
        $message .= "Kasir: {$sale->created_by}\n";
        $message .= "Customer: {$sale->customer->name}\n\n";
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*DETAIL PEMBELIAN*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        foreach ($sale->saleItems as $item) {
            $message .= "{$item->product->name}\n";
            $message .= "{$item->quantity} x " . number_format($item->unit_price, 0, ',', '.') . " = " . number_format($item->total_price, 0, ',', '.') . "\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Subtotal: Rp " . number_format($sale->subtotal, 0, ',', '.') . "\n";
        
        if ($sale->tax_amount > 0) {
            $message .= "Pajak: Rp " . number_format($sale->tax_amount, 0, ',', '.') . "\n";
        }
        
        if ($sale->discount_amount > 0) {
            $message .= "Diskon: -Rp " . number_format($sale->discount_amount, 0, ',', '.') . "\n";
        }
        
        $message .= "*TOTAL: Rp " . number_format($sale->total_amount, 0, ',', '.') . "*\n\n";
        
        $message .= "Bayar ({$sale->payment_method_text}): Rp " . number_format($sale->paid_amount, 0, ',', '.') . "\n";
        
        if ($sale->change_amount > 0) {
            $message .= "Kembalian: Rp " . number_format($sale->change_amount, 0, ',', '.') . "\n";
        }
        
        $message .= "\nTerima kasih atas kunjungan Anda! 🙏";
        
        // Format nomor WhatsApp (hapus karakter non-digit dan tambahkan 62 jika dimulai dengan 0)
        $phone = preg_replace('/[^0-9]/', '', $sale->customer->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'customer_name' => $sale->customer->name,
            'customer_phone' => $sale->customer->phone
        ]);
    }
}
