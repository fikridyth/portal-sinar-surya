<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PpnController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name('auth.')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login-submit');
    Route::post('/register-submit', [AuthController::class, 'registerSubmit'])->name('register-submit');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    // Route::middleware('admin.transaction')->resource('/transaction', TransactionController::class, ['parameters' => ['transaction' => 'id']]);
    
    Route::resource('/preorder', PreOrderController::class, ['parameters' => ['preorder' => 'id']]);
    Route::get('/preorder-get-supplier-data', [PreOrderController::class, 'getSupplierData']);
    Route::prefix('preorder')->name('preorder.')->group(function () {
        Route::post('/get-list-barang', [PreOrderController::class, 'getListBarang'])->name('get-list-barang');
        Route::post('/process-barang', [PreOrderController::class, 'processBarang'])->name('process-barang');
        Route::post('/order-barang', [PreOrderController::class, 'orderBarang'])->name('order-barang');
    });
    Route::get('/daftar-po', [PreOrderController::class, 'daftarPo'])->name('daftar-po');
    Route::get('/daftar-po/{id}', [PreOrderController::class, 'showDaftarPo'])->name('daftar-po.show');
    Route::get('/daftar-po/{id}/edit', [PreOrderController::class, 'editDaftarPo'])->name('daftar-po.edit');
    Route::post('/update-edited-data', [PreOrderController::class, 'updateEditedData'])->name('daftar-po.update');
    Route::post('/store-new-data', [PreOrderController::class, 'storeNewData'])->name('daftar-po.store');
    Route::delete('/destroy-current-data', [PreOrderController::class, 'destroyCurrentData'])->name('daftar-po.destroy');
    Route::post('/set-ppn/{id}', [PreOrderController::class, 'setPpn'])->name('daftar-po.set-ppn');
    Route::post('/set-diskon/{id}', [PreOrderController::class, 'setDiskon'])->name('daftar-po.set-diskon');
    Route::post('/set-bonus/{id}', [PreOrderController::class, 'setBonus'])->name('daftar-po.set-bonus');

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('/unit', UnitController::class, ['parameters' => ['unit' => 'id']]);
        Route::resource('/departemen', DepartemenController::class, ['parameters' => ['departemen' => 'id']]);
        Route::get('/get-departemen', [DepartemenController::class, 'getDepartemenByUnit'])->name('get-departemen');
        Route::resource('/supplier', SupplierController::class, ['parameters' => ['supplier' => 'id']]);
        Route::resource('/ppn', PpnController::class, ['parameters' => ['ppn' => 'id']]);
        Route::resource('/product', ProductController::class, ['parameters' => ['product' => 'id']]);
        Route::get('/product/child/{id}', [ProductController::class, 'productChild'])->name('product.child');
        Route::get('/product/create/{id}', [ProductController::class, 'create'])->name('product.create');
        Route::get('/product/parent/{id}', [ProductController::class, 'productParent'])->name('product.parent');
        Route::get('/product/child-view/{id}', [ProductController::class, 'productChildView'])->name('product.child-view');
        Route::post('/store-product-child', [ProductController::class, 'storeProductChild']);
        Route::post('/store-product-parent', [ProductController::class, 'storeProductParent']);
    });
});
