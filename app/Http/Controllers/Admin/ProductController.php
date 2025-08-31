<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedOutletId = function_exists('selected_outlet_id') ? selected_outlet_id() : null;

        $query = Product::with(['outlet', 'productCategory'])
            ->orderBy('created_at', 'desc');

        if ($selectedOutletId) {
            $query->where('outlet_id', $selectedOutletId);
        }

        $products = $query->get();
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::active()->get();
        $categories = ProductCategory::active()->ordered()->get();
        
        return view('admin.products.create', compact('outlets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'product_category_id' => 'required|exists:product_categories,id',
            'code' => 'required|string|max:255|unique:products,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|unique:products,barcode',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'is_sellable' => 'required|boolean',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            Product::create($data);
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['outlet', 'productCategory']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $outlets = Outlet::active()->get();
        $categories = ProductCategory::active()->ordered()->get();
        
        return view('admin.products.edit', compact('product', 'outlets', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required|exists:outlets,id',
            'product_category_id' => 'required|exists:product_categories,id',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'is_sellable' => 'required|boolean',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $product->update($data);
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        try {
            $product->update([
                'status' => $product->status === 'active' ? 'inactive' : 'active'
            ]);

            $status = $product->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->back()
                ->with('success', "Produk berhasil {$status}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product sellable status
     */
    public function toggleSellable(Product $product)
    {
        try {
            $product->update([
                'is_sellable' => !$product->is_sellable
            ]);

            $status = $product->is_sellable ? 'dapat dijual' : 'tidak dapat dijual';
            
            return redirect()->back()
                ->with('success', "Produk berhasil diubah menjadi {$status}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage(Product $product)
    {
        try {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                $product->update(['image' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add stock to product
     */
    public function addStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $product->addStock($request->quantity);
            
            return redirect()->back()
                ->with('success', "Stok berhasil ditambahkan sebanyak {$request->quantity} {$product->unit}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reduce stock from product
     */
    public function reduceStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            if ($product->reduceStock($request->quantity)) {
                return redirect()->back()
                    ->with('success', "Stok berhasil dikurangi sebanyak {$request->quantity} {$product->unit}!");
            } else {
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi!');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}