<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\SalesSummaryController;
use App\Http\Controllers\ItemImportController;
use App\Http\Controllers\ItemHistoryController;


    // Optional: Admin-only routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/cashiers/create', [CashierController::class, 'create'])->name('admin.cashiers.create');
        Route::post('/cashiers/store', [CashierController::class, 'store'])->name('admin.cashiers.store');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/password/change', [ProfileController::class, 'updatePassword'])->name('profile.change');
    });

    Route::get('/', [AddToCartController::class, 'view'])->name('cart.view');
    Route::post('/cart/clear', [AddToCartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/search', [AddToCartController::class, 'search'])->name('cart.search');
    Route::post('/cart/add', [AddToCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/ajax-add', [AddToCartController::class, 'ajaxAdd'])->name('cart.ajaxAdd');
    Route::post('/cart/update', [AddToCartController::class, 'update'])->name('cart.update');
    Route::post('/cart/ajax-scan', [AddToCartController::class, 'ajaxScan'])->name('cart.ajax-scan');
    Route::get('/cart/{barcode}', [ItemController::class, 'getByBarcode']);
    // Route::get('/cart', fn() => view('cart.mobile-scan'))->name('cart.scan');
    Route::post('/cart/checkout', [AddToCartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/remove', [AddToCartController::class, 'remove'])->name('cart.remove');
    

    //Route::put('/items/{id}', [ItemController::class, 'update']);
      // import item form the csv pr excel
    Route::get('/items/import_items', [ItemImportController::class, 'showForm'])->name('items.import_items');
    Route::post('/items/import/preview', [ItemImportController::class, 'preview'])->name('items.import.preview');
    Route::post('/items/confirmitems', [ItemImportController::class, 'confirm'])->name('items.confirmitems');

    // Route::get('/items/import', [ItemImportController::class, 'showUpload'])->name('items.import_items');
    // Route::post('/items/import/preview', [ItemImportController::class, 'preview'])->name('items.import.import-preview');
    // Route::post('/items/import/confirm', [ItemImportController::class, 'confirmImport'])->name('items.import.confirm');


    Route::put('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');
    Route::get('/items/data', [ItemController::class, 'getData'])->name('items.data');
    Route::get('/items/scan_add', [ItemController::class, 'scanAddForm'])->name('items.scan_add');
    Route::post('/items/store_barcode', [ItemController::class, 'storeFromBarcode'])->name('items.store_barcode');
    Route::resource('/items',ItemController::class);
    Route::get('/item_histories',[ItemHistoryController::class, 'index'])->name('item_histories.index');

    // Route::get('/items/{id}', [ItemController::class, 'edit'])->name('items.edit');

    Route::get('/pos', [CartController::class, 'index'])->name('cart.index');
    
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/summary', [SaleController::class, 'summary'])->name('sales.summary');
    Route::get('/sales/receipt/{id}', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::get('/sales/data', [SaleController::class, 'getData'])->name('sales.sales.getData');
    Route::get('/sales/summary', [SalesSummaryController::class, 'index'])->name('sales.summary');
    Route::get('/sales/output', [SalesSummaryController::class, 'topItemsCharts'])->name('sales.output');
    Route::get('/sales/summary_list', [SalesSummaryController::class, 'topItemsCharts'])->name('sales.summary_list');
    Route::get('/sales/cashiers', [SalesSummaryController::class, 'getCashiers'])->name('sales.cashiers');
    Route::get('/sales/filter_sales', [SalesSummaryController::class, 'filter_sales'])->name('sales.summary_list');
    
    Route::get('/sales/chart', [SalesSummaryController::class, 'salesChart']); // main page
    Route::get('/api/sales-data', [SalesSummaryController::class, 'index']); // ajax


    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');;
    Route::get('/admin/data', [AdminController::class, 'getData'])->name('admin.data');
    Route::get('/admin/chart', [AdminController::class, 'topItemsChart'])->name('admin.top-items.chart');
    Route::get('/admin/cashiers', [AdminController::class, 'index'])->name('cashier.dashboard');
    Route::get('/admin/cashiers', [AdminController::class, 'create'])->name('cashiers.create');
    Route::get('/admin/cashiers', [AdminController::class, 'index'])->name('admin.cashiers.list');
    Route::get('/admin/cashiers/list', function () {
        return \App\Models\User::where('role', 'cashier')->select('id', 'name')->get();
    });

    Route::get('/cashiers', [CashierController::class, 'store'])->name('cashiers.store');
    Route::get('/admin/by-cashier', [CashierController::class, 'topItemsByCashier'])->name('admin.top-items.by-cashier');



    // });

    Route::middleware(['auth'])->group(function () {
    Route::get('/cashiers', [CashierController::class, 'index'])->name('cashiers.index');
    Route::get('/cashiers/create', [CashierController::class, 'create'])->name('cashiers.create');
    Route::post('/cashiers', [CashierController::class, 'store'])->name('cashiers.store');
    });
  

    // Route::get('/', function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    // Route::post('/logout', function () {
    //     Auth::logout();
    //     return redirect('/login');
    // })->name('logout');

    // Profile & Password routes
    // Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    // Route::get('/password/change', [UserController::class, 'changePassword'])->name('password.change');

    // Logout (Breeze usually has this already)//
    //Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



    // Route::middleware('auth')->group(function () {
    //     Route::post('/cashier/logout', function () {
    //         Auth::logout();
    //         return redirect('/cashier/login');
    //     })->name('cashier.logout');
    // });



require __DIR__.'/auth.php';
