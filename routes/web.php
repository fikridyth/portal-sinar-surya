<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GiroController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\PembayaranController;
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
    
    // Preorder
    Route::resource('/preorder', PreOrderController::class, ['parameters' => ['preorder' => 'id']]);
    Route::get('/preorder-get-supplier-data', [PreOrderController::class, 'getSupplierData']);
    Route::get('/get-products-by-kode-po/{kode}', [PreOrderController::class, 'getProductsByKodePo']);
    Route::prefix('preorder')->name('preorder.')->group(function () {
        Route::post('/get-list-barang', [PreOrderController::class, 'getListBarang'])->name('get-list-barang');
        Route::post('/process-barang', [PreOrderController::class, 'processBarang'])->name('process-barang');
        Route::post('/order-barang', [PreOrderController::class, 'orderBarang'])->name('order-barang');
    });
    Route::get('/daftar-po', [PreOrderController::class, 'daftarPo'])->name('daftar-po');
    Route::get('/daftar-po/{id}', [PreOrderController::class, 'showDaftarPo'])->name('daftar-po.show');
    Route::get('/daftar-po/{id}/edit', [PreOrderController::class, 'editDaftarPo'])->name('daftar-po.edit');
    Route::get('/daftar-po/{id}/cetak', [PreOrderController::class, 'cetakDaftarPo'])->name('daftar-po.cetak');

    // Receive
    Route::get('/receive-po/{id}/show', [PreOrderController::class, 'receivePo'])->name('receive-po');
    Route::get('/receive-po/create', [PreOrderController::class, 'createReceivePo'])->name('receive-po.create');
    Route::get('/receive-get-preorder-data', [PreOrderController::class, 'getPreorderData']);
    Route::post('/store-receive-data', [PreOrderController::class, 'storeReceiveData'])->name('receive-po.store');
    Route::delete('/destroy-receive-data', [PreOrderController::class, 'destroyReceiveData'])->name('receive-po.destroy');
    Route::get('/receive-po/create-detail/{id}', [PreOrderController::class, 'createDetailReceivePo'])->name('receive-po.create-detail');
    Route::get('/daftar-receive-po', [PreOrderController::class, 'daftarReceivePo'])->name('daftar-receive-po');
    Route::get('/persetujuan-harga-jual', [PreOrderController::class, 'persetujuanHargaJual'])->name('persetujuan-harga-jual');
    Route::get('/persetujuan-harga-jual/{id}/edit', [PreOrderController::class, 'editPersetujuanHargaJual'])->name('persetujuan-harga-jual-edit');
    Route::put('/persetujuan-harga-jual/{id}/update', [PreOrderController::class, 'updatePersetujuanHargaJual'])->name('persetujuan-harga-jual-update');
    Route::get('/get-products-by-kode/{kode}', [PreOrderController::class, 'getProductsByKode']);

    // Return
    Route::get('/return-po', [PreOrderController::class, 'returnPo'])->name('return-po');
    Route::post('/store-return-data', [PreOrderController::class, 'storeReturnData'])->name('return-po.store');
    Route::get('/daftar-return-po', [PreOrderController::class, 'daftarReturnPo'])->name('daftar-return-po');
    Route::delete('/destroy-return-data/{id}', [PreOrderController::class, 'destroyReturnData'])->name('return-po.destroy');

    // Func in PO & Receive
    Route::post('/store-new-data', [PreOrderController::class, 'storeNewData'])->name('daftar-po.store');
    Route::post('/store-new-receive', [PreOrderController::class, 'storeNewReceive'])->name('create-receive.store');
    Route::post('/update-edited-data', [PreOrderController::class, 'updateEditedData'])->name('daftar-po.update');
    Route::post('/update-receive-data', [PreOrderController::class, 'updateReceiveData'])->name('create-receive.update');
    Route::delete('/destroy-current-data', [PreOrderController::class, 'destroyCurrentData'])->name('daftar-po.destroy');
    Route::post('/set-ppn/{id}', [PreOrderController::class, 'setPpn'])->name('daftar-po.set-ppn');
    Route::post('/set-ppn-receive/{id}', [PreOrderController::class, 'setPpnReceive'])->name('create-receive.set-ppn-receive');
    Route::post('/set-diskon/{id}', [PreOrderController::class, 'setDiskon'])->name('daftar-po.set-diskon');
    Route::post('/set-bonus/{id}', [PreOrderController::class, 'setBonus'])->name('daftar-po.set-bonus');
    Route::post('/store-pembayaran', [PreOrderController::class, 'storePembayaran'])->name('daftar-po.store-pembayaran');

    // Pembayaran Hutang
    Route::get('/pembayaran-hutang', [PembayaranController::class, 'indexHutang'])->name('pembayaran-hutang.index');
    Route::get('/pembayaran-hutang/{id}', [PembayaranController::class, 'showHutang'])->name('pembayaran-hutang.show');
    Route::post('/pembayaran-hutang/{id}/process', [PembayaranController::class, 'processHutang'])->name('pembayaran-hutang.process');
    Route::post('/pembayaran-hutang/{id}/process-final', [PembayaranController::class, 'processFinalHutang'])->name('pembayaran-hutang.process-final');
    Route::post('/pembayaran-hutang/{id}/store', [PembayaranController::class, 'storeHutang'])->name('pembayaran-hutang.store');
    Route::get('/pembayaran-hutang-hapus', [PembayaranController::class, 'indexHutangHapus'])->name('pembayaran-hutang.index-hapus');
    Route::get('/pembayaran-hutang-hapus/{id}/detail', [PembayaranController::class, 'detailHutangHapus'])->name('pembayaran-hutang.detail-hapus');
    Route::delete('/destroy-hutang/{id}', [PembayaranController::class, 'destroyHutang'])->name('pembayaran-hutang.destroy-hutang');

    // Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran-konfirmasi', [PembayaranController::class, 'indexKonfirmasi'])->name('pembayaran.index-konfirmasi');
    Route::delete('/pembayaran-history/{id}/destroy', [PembayaranController::class, 'destroyHistory'])->name('pembayaran.destroy-history');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran-gabung/{id}', [PembayaranController::class, 'showGabung'])->name('pembayaran.show-gabung');
    Route::put('/pembayaran/{id}/update', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::put('/pembayaran-gabung/{id}/update', [PembayaranController::class, 'updateGabung'])->name('pembayaran.update-gabung');
    Route::delete('/pembayaran/destroy-payment/{ids}', [PembayaranController::class, 'destroyPayment'])->name('pembayaran.destroy-payment');
    Route::get('/pembayaran/param-cetak-payment/{ids}', [PembayaranController::class, 'paramCetakPayment'])->name('pembayaran.param-cetak-payment');
    Route::get('/pembayaran/cetak-payment/{ids}', [PembayaranController::class, 'cetakPayment'])->name('pembayaran.cetak-payment');
    Route::get('/pembayaran/konfirmasi-payment/{ids}', [PembayaranController::class, 'konfirmasiPayment'])->name('pembayaran.konfirmasi-payment');
    // Route::get('/default-bank', [PembayaranController::class, 'defaultBank'])->name('pembayaran.default-bank');

    // Laporan
    Route::get('/daftar-harga-jual-kecil', [PreOrderController::class, 'daftarHargaJualKecil'])->name('daftar-harga-jual-kecil');
    
    // Master
    Route::prefix('master')->name('master.')->group(function () {
        // Unit
        Route::resource('/unit', UnitController::class, ['parameters' => ['unit' => 'id']]);

        // Departemen
        Route::resource('/departemen', DepartemenController::class, ['parameters' => ['departemen' => 'id']]);
        Route::get('/get-departemen', [DepartemenController::class, 'getDepartemenByUnit'])->name('get-departemen');

        // Supplier
        Route::resource('/supplier', SupplierController::class, ['parameters' => ['supplier' => 'id']]);

        // Kunjungan
        Route::get('/kunjungan', [SupplierController::class, 'indexKunjungan'])->name('kunjungan.index');

        // Promosi
        Route::get('/promosi', [SupplierController::class, 'indexPromosi'])->name('promosi.index');
        Route::get('/promosi-all', [SupplierController::class, 'indexAllPromosi'])->name('promosi.index-all');
        Route::post('/promosi/store', [SupplierController::class, 'storePromosi'])->name('promosi.store');
        Route::put('/promosi/{id}/update', [SupplierController::class, 'updatePromosi'])->name('promosi.update');
        Route::delete('/promosi/{id}/destroy', [SupplierController::class, 'destroyPromosi'])->name('promosi.destroy');

        // Materai
        Route::get('/materai', [SupplierController::class, 'indexMaterai'])->name('materai.index');
        Route::put('/materai/{id}/update', [SupplierController::class, 'updateMaterai'])->name('materai.update');

        // History PO
        Route::get('/history-preorder/{id}', [SupplierController::class, 'indexHistoryPo'])->name('history-preorder.index');

        // Bank
        Route::get('/bank', [GiroController::class, 'indexBank'])->name('bank.index');
        Route::post('/bank/store', [GiroController::class, 'storeBank'])->name('bank.store');
        Route::put('/bank/{id}/update', [GiroController::class, 'updateBank'])->name('bank.update');
        Route::delete('/bank/{id}/destroy', [GiroController::class, 'destroyBank'])->name('bank.destroy');

        // Giro
        Route::get('/giro', [GiroController::class, 'index'])->name('giro.index');
        Route::get('/giro/create/{id}', [GiroController::class, 'create'])->name('giro.create');
        Route::post('/giro/store/{id}', [GiroController::class, 'store'])->name('giro.store');
        Route::get('/giro/show/{id}', [GiroController::class, 'show'])->name('giro.show');
        Route::get('/get-data-giro', [GiroController::class, 'getData']);
        Route::get('/get-bayar-giro', [GiroController::class, 'getDataBayar']);

        // PPN
        Route::resource('/ppn', PpnController::class, ['parameters' => ['ppn' => 'id']]);

        // Product
        Route::resource('/product', ProductController::class, ['parameters' => ['product' => 'id'], 'except' => ['create']]);
        Route::get('/product/child/{id}', [ProductController::class, 'productChild'])->name('product.child');
        Route::get('/product/create/{id}', [ProductController::class, 'create'])->name('product.create');
        Route::get('/product/parent/{id}', [ProductController::class, 'productParent'])->name('product.parent');
        Route::get('/product/child-view/{id}', [ProductController::class, 'productChildView'])->name('product.child-view');
        Route::post('/store-product-child', [ProductController::class, 'storeProductChild']);
        Route::post('/store-product-parent', [ProductController::class, 'storeProductParent']);

        // Stock Opname
        Route::get('/product-opname', [ProductController::class, 'stockOpname'])->name('opname');
        Route::post('/product-opname/update', [ProductController::class, 'updateStockOpname'])->name('opname.update');

        // Harga
        Route::get('/harga', [HargaController::class, 'index'])->name('harga.index');
        Route::get('/harga/{id}', [HargaController::class, 'show'])->name('harga.show');

        // Kartu Stok
        Route::resource('/kartu-stok', KartuStokController::class, ['parameters' => ['kartu-stok' => 'id']]);
    });
});
