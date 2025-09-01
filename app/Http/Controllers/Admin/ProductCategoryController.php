<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::ordered()->get();
        return view('admin.product-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:product_categories,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
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
                $imagePath = $image->storeAs('categories', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            // Set default sort order if not provided
            if (!$request->sort_order) {
                $data['sort_order'] = ProductCategory::max('sort_order') + 1;
            }

            ProductCategory::create($data);
            
            return redirect()->route('kasir.product-categories.index')
                ->with('success', 'Kategori produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        $productCategory->load('products');
        return view('admin.product-categories.show', compact('productCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-categories.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:product_categories,code,' . $productCategory->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
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
                if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
                    Storage::disk('public')->delete($productCategory->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('categories', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $productCategory->update($data);
            
            return redirect()->route('kasir.product-categories.index')
                ->with('success', 'Kategori produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        try {
            // Check if category has products
            if ($productCategory->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
            }

            // Delete image if exists
            if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
                Storage::disk('public')->delete($productCategory->image);
            }

            $productCategory->delete();
            
            return redirect()->route('kasir.product-categories.index')
                ->with('success', 'Kategori produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(ProductCategory $productCategory)
    {
        try {
            $productCategory->update([
                'status' => $productCategory->status === 'active' ? 'inactive' : 'active'
            ]);

            $status = $productCategory->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->back()
                ->with('success', "Kategori berhasil {$status}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete category image
     */
    public function deleteImage(ProductCategory $productCategory)
    {
        try {
            if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
                Storage::disk('public')->delete($productCategory->image);
                $productCategory->update(['image' => null]);
                
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
}