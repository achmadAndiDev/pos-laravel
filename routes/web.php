<?php

use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\ProductCategoryController;

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
    })->name('dashboard');

    // Outlets
    Route::resource('outlets', OutletController::class);
    Route::patch('outlets/{outlet}/toggle-status', [OutletController::class, 'toggleStatus'])->name('outlets.toggle-status');

    // Product Categories
    Route::resource('product-categories', ProductCategoryController::class);
    Route::patch('product-categories/{productCategory}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('product-categories.toggle-status');
    Route::delete('product-categories/{productCategory}/delete-image', [ProductCategoryController::class, 'deleteImage'])->name('product-categories.delete-image');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints'])->name('customers.add-points');
    Route::post('customers/{customer}/deduct-points', [CustomerController::class, 'deductPoints'])->name('customers.deduct-points');
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import');

});
