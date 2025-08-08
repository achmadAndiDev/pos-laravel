<?php

use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ProfitCalculationController;
use App\Http\Controllers\Admin\SalesCalculationController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index']);

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

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


    // Purchase Reports
    Route::get('report/purchases', [PurchaseController::class, 'report'])->name('purchases.report');
    Route::get('report/purchases/print-report', [PurchaseController::class, 'printReport'])->name('purchases.print-report');
    Route::get('report/purchases/export-pdf', [PurchaseController::class, 'exportPdf'])->name('purchases.export-pdf');

    // Sales Reports
    Route::get('report/sales', [SaleController::class, 'report'])->name('sales.report');
    Route::get('report/sales/print-report', [SaleController::class, 'printReport'])->name('sales.print-report');
    Route::get('report/sales/export-pdf', [SaleController::class, 'exportPdf'])->name('sales.export-pdf');

    // Profit Reports
    Route::get('report/profit', [ProfitCalculationController::class, 'report'])->name('profit-calculation.report');
    Route::get('report/profit/print-report', [ProfitCalculationController::class, 'printReport'])->name('profit-calculation.print-report');
    Route::get('report/profit/export-pdf', [ProfitCalculationController::class, 'exportPdf'])->name('profit-calculation.export-pdf');


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

    // Sales
    Route::resource('sales', SaleController::class);
    Route::post('sales/{sale}/complete', [SaleController::class, 'complete'])->name('sales.complete');
    Route::post('sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    Route::get('sales/products/by-outlet', [SaleController::class, 'getProductsByOutlet'])->name('sales.products-by-outlet');

    // Sales Calculation
    Route::get('sales-calculation', [SalesCalculationController::class, 'index'])->name('sales-calculation.index');
    Route::get('sales-calculation/export', [SalesCalculationController::class, 'export'])->name('sales-calculation.export');
    Route::get('sales-calculation/data', [SalesCalculationController::class, 'getData'])->name('sales-calculation.data');

    // Profit Calculation
    Route::get('profit-calculation', [ProfitCalculationController::class, 'index'])->name('profit-calculation.index');
    Route::get('profit-calculation/export', [ProfitCalculationController::class, 'export'])->name('profit-calculation.export');
    Route::get('profit-calculation/data', [ProfitCalculationController::class, 'getData'])->name('profit-calculation.data');

});
