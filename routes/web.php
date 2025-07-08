<?php

use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;

Route::get('/', function () {
    return view('admin/dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // Outlets
    Route::resource('outlets', OutletController::class);
    Route::patch('outlets/{outlet}/toggle-status', [OutletController::class, 'toggleStatus'])->name('outlets.toggle-status');

    // Product Categories
    Route::resource('product-categories', ProductCategoryController::class);
    Route::patch('product-categories/{productCategory}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('product-categories.toggle-status');
    Route::delete('product-categories/{productCategory}/delete-image', [ProductCategoryController::class, 'deleteImage'])->name('product-categories.delete-image');

    // Products
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::patch('products/{product}/toggle-sellable', [ProductController::class, 'toggleSellable'])->name('products.toggle-sellable');
    Route::delete('products/{product}/delete-image', [ProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::post('products/{product}/add-stock', [ProductController::class, 'addStock'])->name('products.add-stock');
    Route::post('products/{product}/reduce-stock', [ProductController::class, 'reduceStock'])->name('products.reduce-stock');

    // Purchases
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');
    Route::post('purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    Route::get('purchases/products/by-outlet', [PurchaseController::class, 'getProductsByOutlet'])->name('purchases.products-by-outlet');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints'])->name('customers.add-points');
    Route::post('customers/{customer}/deduct-points', [CustomerController::class, 'deductPoints'])->name('customers.deduct-points');

});
