<?php

use App\Http\Controllers\AdjustmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GiroController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PpnController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\Preorder;

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
    Route::resource('/preorder', PreOrderController::class, ['parameters' => ['preorder' => 'id']])->except(['show']);
    Route::get('/preorder-get-supplier-data', [PreOrderController::class, 'getSupplierData']);
    Route::get('/get-products-by-kode-po/{kode}', [PreOrderController::class, 'getProductsByKodePo']);
    Route::prefix('preorder')->name('preorder.')->group(function () {
        Route::post('/get-data-penjualan', [PreOrderController::class, 'getDataPenjualan'])->name('get-data-penjualan');
        Route::post('/get-list-barang', [PreOrderController::class, 'getListBarang'])->name('get-list-barang');
        Route::post('/process-barang', [PreOrderController::class, 'processBarang'])->name('process-barang');
        Route::get('/add-po/cetak', [PreOrderController::class, 'cetakTambahPo'])->name('add-po.cetak');
        Route::post('/order-barang', [PreOrderController::class, 'orderBarang'])->name('order-barang');
    });
    Route::get('/daftar-po', [PreOrderController::class, 'daftarPo'])->name('daftar-po');
    Route::get('/daftar-po/{id}', [PreOrderController::class, 'showDaftarPo'])->name('daftar-po.show');
    Route::get('/daftar-po/{id}/edit', [PreOrderController::class, 'editDaftarPo'])->name('daftar-po.edit');
    Route::get('/daftar-po/{id}/cetak', [PreOrderController::class, 'cetakDaftarPo'])->name('daftar-po.cetak');

    // Receive
    Route::get('/receive-po/{id}/show', [PreOrderController::class, 'receivePo'])->name('receive-po');
    Route::get('/receive-po/create', [PreOrderController::class, 'createReceivePo'])->name('receive-po.create');
    Route::get('/receive-po/add-product/{id}', [PreOrderController::class, 'addProductReceivePo'])->name('receive-po.add-product');
    Route::put('/receive-po/add-product/update/{id}', [PreOrderController::class, 'updateProductReceivePo'])->name('receive-po.add-product.update');
    Route::get('/receive-get-preorder-data', [PreOrderController::class, 'getPreorderData']);
    Route::post('/store-receive-data', [PreOrderController::class, 'storeReceiveData'])->name('receive-po.store');
    Route::delete('/destroy-receive-data', [PreOrderController::class, 'destroyReceiveData'])->name('receive-po.destroy');
    Route::get('/daftar-receive-po', [PreOrderController::class, 'daftarReceivePo'])->name('daftar-receive-po');
    Route::get('/daftar-receive-done-po', [PreOrderController::class, 'daftarReceiveDonePo'])->name('daftar-receive-done-po');
    Route::get('/receive-po/create-detail/{id}', [PreOrderController::class, 'createDetailReceivePo'])->name('receive-po.create-detail');
    Route::get('/receive-po/done-detail/{id}', [PreOrderController::class, 'doneDetailReceivePo'])->name('receive-po.done-detail');
    Route::get('/receive-po/preview-data/{id}', [PreOrderController::class, 'previewDataReceivePo'])->name('receive-po.preview-data');
    Route::put('/receive-po/update/{id}', [PreOrderController::class, 'updateReceivePo'])->name('receive-po.update');
    Route::get('/receive-po/cetak/{id}', [PreOrderController::class, 'cetakReceivePo'])->name('receive-po.cetak');
    Route::get('/receive-po/cetak-done-data/{id}', [PreOrderController::class, 'cetakReceivePoDoneData'])->name('receive-po.cetak-done-data');

    // Persetujuan Harga Jual
    Route::get('/persetujuan-harga-jual', [PreOrderController::class, 'persetujuanHargaJual'])->name('persetujuan-harga-jual');
    Route::get('/persetujuan-harga-jual/{id}/edit', [PreOrderController::class, 'editPersetujuanHargaJual'])->name('persetujuan-harga-jual-edit');
    Route::put('/persetujuan-harga-jual/{id}/update', [PreOrderController::class, 'updatePersetujuanHargaJual'])->name('persetujuan-harga-jual-update');
    Route::get('/get-products-by-kode/{kode}', [PreOrderController::class, 'getProductsByKode']);
    Route::get('/daftar-harga-jual-kecil', [PreOrderController::class, 'daftarHargaJualKecil'])->name('daftar-harga-jual-kecil');
    Route::get('/batal-persetujuan-harga-jual', [PreOrderController::class, 'batalPersetujuanHargaJual'])->name('batal-persetujuan-harga-jual');
    Route::get('/batal-persetujuan-harga-jual/{id}/update', [PreOrderController::class, 'updateBatalPersetujuanHargaJual'])->name('batal-persetujuan-harga-jual-update');

    // Return
    Route::get('/return-po', [PreOrderController::class, 'returnPo'])->name('return-po');
    Route::post('/create-return-po', [PreOrderController::class, 'createReturnPo'])->name('create-return-po');
    Route::get('/daftar-return-po', [PreOrderController::class, 'daftarReturnPo'])->name('daftar-return-po');
    Route::get('/daftar-history-return-po', [PreOrderController::class, 'daftarHistoryReturnPo'])->name('daftar-history-return-po');
    Route::get('/return-po/{id}', [PreOrderController::class, 'showReturnPo'])->name('return-po.show');
    Route::get('/return-po/edit/{id}', [PreOrderController::class, 'editReturnPo'])->name('return-po.edit');
    Route::get('/daftar-receive-supplier/{id}/{sup}', [PreOrderController::class, 'daftarReceiveSupplier'])->name('daftar-receive-supplier');
    Route::put('/update-nomor-receive/{id}', [PreOrderController::class, 'updateNomorReceive'])->name('update-nomor-receive');
    Route::get('/daftar-return-product/{id}', [PreOrderController::class, 'daftarReturnProduct'])->name('daftar-return-product');
    Route::put('/daftar-return-product/update/{id}', [PreOrderController::class, 'updateDaftarReturnProduct'])->name('daftar-return-product.update');
    Route::post('/get-data-return-barcode', [PreOrderController::class, 'getDataReturnBarcode'])->name('daftar-po.get-data-return-barcode');
    Route::post('/store-return-data/{id}', [PreOrderController::class, 'storeReturnData'])->name('return-po.store');
    Route::post('/destroy-return-item', [PreOrderController::class, 'destroyReturnItem'])->name('return-po.destroy-return-item');
    Route::post('/save-return-item', [PreOrderController::class, 'saveReturnItem'])->name('return-po.save-return-item');
    Route::delete('/destroy-return-data/{id}', [PreOrderController::class, 'destroyReturnData'])->name('return-po.destroy');

    // Func in PO & Receive
    Route::post('/store-new-data', [PreOrderController::class, 'storeNewData'])->name('daftar-po.store');
    Route::post('/store-new-receive', [PreOrderController::class, 'storeNewReceive'])->name('create-receive.store');
    Route::post('/update-edited-data', [PreOrderController::class, 'updateEditedData'])->name('daftar-po.update');
    Route::post('/update-receive-data', [PreOrderController::class, 'updateReceiveData'])->name('create-receive.update');
    Route::delete('/destroy-current-data', [PreOrderController::class, 'destroyCurrentData'])->name('daftar-po.destroy');
    Route::post('/set-ppn/{id}', [PreOrderController::class, 'setPpn'])->name('daftar-po.set-ppn');
    Route::post('/set-ppn-receive/{id}', [PreOrderController::class, 'setPpnReceive'])->name('create-receive.set-ppn-receive');
    Route::put('/cancel-receive/{id}', [PreOrderController::class, 'cancelReceive'])->name('create-receive.cancel-receive');
    Route::post('/set-diskon/{id}', [PreOrderController::class, 'setDiskon'])->name('daftar-po.set-diskon');
    Route::post('/set-bonus/{id}', [PreOrderController::class, 'setBonus'])->name('daftar-po.set-bonus');
    Route::post('/store-pembayaran', [PreOrderController::class, 'storePembayaran'])->name('daftar-po.store-pembayaran');
    Route::post('/get-data-from-barcode', [PreOrderController::class, 'getDataFromBarcode'])->name('daftar-po.get-data-from-barcode');
    Route::post('/store-data-from-barcode', [PreOrderController::class, 'storeDataFromBarcode'])->name('daftar-po.store-data-from-barcode');
    Route::post('/update-supplier-receive-data', [PreOrderController::class, 'updateSupplierReceiveData'])->name('update-supplier-receive-data');

    // Hutang
    Route::get('/pembayaran-hutang', [PembayaranController::class, 'indexHutang'])->name('pembayaran-hutang.index');
    Route::get('/pembayaran-hutang/{id}', [PembayaranController::class, 'showHutang'])->name('pembayaran-hutang.show');
    Route::post('/pembayaran-hutang/{id}/process', [PembayaranController::class, 'processHutang'])->name('pembayaran-hutang.process');
    Route::post('/pembayaran-hutang/{id}/process-final', [PembayaranController::class, 'processFinalHutang'])->name('pembayaran-hutang.process-final');
    Route::post('/pembayaran-hutang/{id}/store', [PembayaranController::class, 'storeHutang'])->name('pembayaran-hutang.store');
    Route::get('/pembayaran-hutang-history', [PembayaranController::class, 'indexHistoryHutangHapus'])->name('pembayaran-hutang.index-history');
    Route::get('/pembayaran-hutang-hapus', [PembayaranController::class, 'indexHutangHapus'])->name('pembayaran-hutang.index-hapus');
    Route::get('/pembayaran-hutang-hapus/{id}/detail', [PembayaranController::class, 'detailHutangHapus'])->name('pembayaran-hutang.detail-hapus');
    Route::delete('/destroy-hutang/{id}', [PembayaranController::class, 'destroyHutang'])->name('pembayaran-hutang.destroy-hutang');
    Route::get('/api/receive-po/detail/{nomor}', function ($nomor) {
        $getId = Preorder::where('nomor_receive', $nomor)->first();
        if ($getId) { 
            $encryptedId = enkrip($getId->id);
            return response()->json(['encryptedId' => $encryptedId]);
        }
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    });

    // Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran-gabung/{id}', [PembayaranController::class, 'showGabung'])->name('pembayaran.show-gabung');
    Route::put('/pembayaran/{id}/update', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::put('/pembayaran-gabung/{id}/update', [PembayaranController::class, 'updateGabung'])->name('pembayaran.update-gabung');
    Route::delete('/pembayaran/destroy-payment/{ids}', [PembayaranController::class, 'destroyPayment'])->name('pembayaran.destroy-payment');
    Route::get('/pembayaran/list-cetak-payment/data', [PembayaranController::class, 'listCetakPayment'])->name('pembayaran.list-cetak-payment.index');
    Route::get('/pembayaran/param-cetak-payment/{ids}', [PembayaranController::class, 'paramCetakPayment'])->name('pembayaran.param-cetak-payment');
    Route::get('/pembayaran/cetak-payment/{ids}', [PembayaranController::class, 'cetakPayment'])->name('pembayaran.cetak-payment');
    Route::get('/pembayaran/konfirmasi-payment/{ids}', [PembayaranController::class, 'konfirmasiPayment'])->name('pembayaran.konfirmasi-payment');
    Route::get('/pembayaran-cabang', [PembayaranController::class, 'indexCabang'])->name('pembayaran.cabang-index');
    Route::get('/pembayaran/cabang/browse', [PembayaranController::class, 'browseCabang'])->name('pembayaran.cabang.browse');
    Route::get('/pembayaran/cabang/show/{id}', [PembayaranController::class, 'showCabang'])->name('pembayaran.cabang.show');
    Route::post('/pembayaran/cabang/store', [PembayaranController::class, 'storeCabang'])->name('pembayaran.cabang.store');
    Route::post('/pembayaran/cabang/update', [PembayaranController::class, 'updateCabang'])->name('pembayaran.cabang.update');

    // Konfirmasi
    Route::get('/pembayaran-konfirmasi', [PembayaranController::class, 'indexKonfirmasi'])->name('pembayaran-konfirmasi.index');
    Route::get('/pembayaran-konfirmasi/show', [PembayaranController::class, 'showKonfirmasi'])->name('pembayaran-konfirmasi.show');

    // Piutang
    Route::get('/pembayaran-piutang', [PiutangController::class, 'index'])->name('pembayaran-piutang.index');
    Route::get('/api/getPembayaranData', [PiutangController::class, 'getPembayaranData']);
    Route::post('/pembayaran-piutang/store', [PiutangController::class, 'store'])->name('pembayaran-piutang.store');
    Route::get('/daftar-tagihan-langganan', [PiutangController::class, 'indexTagihan'])->name('daftar-tagihan.index');
    Route::get('/daftar-tagihan-langganan/{id}', [PiutangController::class, 'showTagihan'])->name('daftar-tagihan.show');
    Route::get('/daftar-tagihan-langganan/{id}/cetak', [PiutangController::class, 'cetakTagihan'])->name('daftar-tagihan.cetak');
    Route::get('/daftar-tagihan-langganan/{id}/proses', [PiutangController::class, 'prosesTagihan'])->name('daftar-tagihan.proses');
    Route::post('/daftar-tagihan-langganan/{id}/final-proses', [PiutangController::class, 'prosesFinalTagihan'])->name('daftar-tagihan.final-proses');
    Route::delete('/daftar-tagihan-langganan/{id}/destroy', [PiutangController::class, 'destroyTagihan'])->name('daftar-tagihan.destroy');
    Route::get('/history-tagihan-langganan', [PiutangController::class, 'historyTagihan'])->name('history-tagihan.index');
    Route::get('/history-tagihan-langganan/{id}', [PiutangController::class, 'showHistoryTagihan'])->name('history-tagihan.show');
    Route::get('/daftar-tagihan-history', [PiutangController::class, 'indexHistoryPiutang'])->name('daftar-tagihan.index-history');
    // new show piutang
    Route::get('/pembayaran-piutang/{id}', [PiutangController::class, 'show'])->name('pembayaran-piutang.show');

    // Kredit
    Route::get('/kredit', [PiutangController::class, 'indexKredit'])->name('kredit.index');
    Route::post('/create-data-kredit', [PiutangController::class, 'createDataKredit'])->name('create-data-kredit');
    Route::get('/kredit/edit/{id}', [PiutangController::class, 'editKredit'])->name('kredit.edit');
    Route::get('/kredit/list', [PiutangController::class, 'listKredit'])->name('kredit.list');
    Route::get('/daftar-kredit-product/{id}', [PiutangController::class, 'daftarKreditProduct'])->name('daftar-kredit-product');
    Route::put('/daftar-kredit-product/update/{id}', [PiutangController::class, 'updateDaftarKreditProduct'])->name('daftar-kredit-product.update');
    Route::post('/get-data-kredit-barcode', [PiutangController::class, 'getDataKreditBarcode'])->name('kredit.get-data-kredit-barcode');
    Route::post('/store-kredit-data/{id}', [PiutangController::class, 'storeKreditData'])->name('kredit.store');
    Route::post('/destroy-kredit-item', [PiutangController::class, 'destroyKreditItem'])->name('kredit.destroy-kredit-item');
    Route::post('/save-kredit-item', [PiutangController::class, 'saveKreditItem'])->name('kredit.save-kredit-item');
    Route::get('/kredit/history/{id}', [PiutangController::class, 'indexKreditHistory'])->name('kredit.index-history');
    Route::get('/kredit/list-history', [PiutangController::class, 'listKreditHistory'])->name('kredit.list-history');
    Route::get('/kredit/cetak-history/{id}', [PiutangController::class, 'cetakKreditHistory'])->name('kredit.cetak-history');
    Route::get('/kredit/retur/{id}', [PiutangController::class, 'returKredit'])->name('kredit.retur');
    Route::get('/kredit/store-cabang/{id}', [PiutangController::class, 'storeCabang'])->name('kredit.store-cabang');

    // Master
    Route::prefix('master')->name('master.')->group(function () {
        // Unit
        Route::resource('/unit', UnitController::class, ['parameters' => ['unit' => 'id']]);

        // Departemen
        Route::resource('/departemen', DepartemenController::class, ['parameters' => ['departemen' => 'id']]);
        Route::get('/get-departemen', [DepartemenController::class, 'getDepartemenByUnit'])->name('get-departemen');

        // Supplier
        Route::resource('/supplier', SupplierController::class, ['parameters' => ['supplier' => 'id']]);
        Route::get('/cetak-faktur-supplier/{id}/{nama}/{dari}/{sampai}', [SupplierController::class, 'cetakFakturSupplier'])->name('cetak-faktur-supplier');
        Route::get('/cetak-barang-supplier/{id}/{nama}/{dari}/{sampai}', [SupplierController::class, 'cetakBarangSupplier'])->name('cetak-barang-supplier');

        // Change Supplier
        Route::get('/change-supplier', [SupplierController::class, 'indexChangeSupplier'])->name('change-supplier.index');
        Route::get('/change-supplier/{id}', [SupplierController::class, 'showChangeSupplier'])->name('change-supplier.show');
        Route::put('/change-supplier/{id}/update', [SupplierController::class, 'updateChangeSupplier'])->name('change-supplier.update');

        // Kunjungan
        Route::get('/kunjungan', [SupplierController::class, 'indexKunjungan'])->name('kunjungan.index');
        Route::put('/kunjungan/{id}/update', [SupplierController::class, 'updateWaktuKunjungan'])->name('kunjungan.update');

        // Promosi
        Route::get('/promosi', [SupplierController::class, 'indexPromosi'])->name('promosi.index');
        Route::get('/promosi-list', [SupplierController::class, 'indexListPromosi'])->name('promosi.index-list');
        Route::get('/promosi-all', [SupplierController::class, 'indexAllPromosi'])->name('promosi.index-all');
        Route::get('/promosi-print', [SupplierController::class, 'promosiPrint'])->name('promosi.print');
        Route::post('/promosi/store', [SupplierController::class, 'storePromosi'])->name('promosi.store');
        Route::put('/promosi/{id}/update', [SupplierController::class, 'updatePromosi'])->name('promosi.update');
        Route::delete('/promosi/{id}/destroy', [SupplierController::class, 'destroyPromosi'])->name('promosi.destroy');

        // Materai
        Route::get('/materai', [SupplierController::class, 'indexMaterai'])->name('materai.index');
        Route::put('/materai/{id}/update', [SupplierController::class, 'updateMaterai'])->name('materai.update');

        // History PO
        Route::get('/history-preorder/{id}', [SupplierController::class, 'indexHistoryPo'])->name('history-preorder.index');
        Route::get('/get-history-po', [SupplierController::class, 'getHistoryPo']);

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
        Route::put('/giro/update/{id}', [GiroController::class, 'update'])->name('giro.update');
        Route::put('/giro/update-reserve/{id}', [GiroController::class, 'updateReserve'])->name('giro.update-reserve');
        Route::get('/get-data-giro', [GiroController::class, 'getData']);
        Route::get('/get-bayar-giro', [GiroController::class, 'getDataBayar']);
        Route::get('/cek-giro', [GiroController::class, 'indexCekGiro'])->name('cek-giro.index');
        Route::get('/cek-giro/show', [GiroController::class, 'showCekGiro'])->name('cek-giro.show');

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
        Route::post('/product/update-status/{id}', [ProductController::class, 'updateStatus']);
        Route::get('/product/data/edit-nama/{id}', [ProductController::class, 'editNama'])->name('product.edit-nama');
        Route::post('/product/data/update-edit-nama', [ProductController::class, 'updateEditNama'])->name('product.update-edit-nama');
        
        // History Product
        Route::get('/history-product/{id}', [ProductController::class, 'indexHistoryProduct'])->name('history-product.index');
        // Route::get('/get-history-product', [ProductController::class, 'getHistoryProduct']);

        // store product update to pos
        Route::get('/store-to-pos', [ProductController::class, 'storeToPos'])->name('store-to-pos');

        // scan barcode on master product
        Route::get('/get-detail-products/{kode}', [ProductController::class, 'getDetailProducts']);

        // Stock Opname
        Route::get('/product-opname', [ProductController::class, 'stockOpname'])->name('opname');
        Route::post('/product-opname/update', [ProductController::class, 'updateStockOpname'])->name('opname.update');

        // Harga
        Route::get('/harga', [HargaController::class, 'index'])->name('harga.index');
        Route::post('/harga/store', [HargaController::class, 'store'])->name('harga.store');
        Route::get('/harga/{id}', [HargaController::class, 'show'])->name('harga.show');
        Route::put('/harga/{id}/update', [HargaController::class, 'update'])->name('harga.update');
        Route::get('/harga-sementara', [HargaController::class, 'indexHargaSementara'])->name('harga-sementara.index');
        Route::get('/harga-sementara/{id}', [HargaController::class, 'showHargaSementara'])->name('harga-sementara.show');

        // Kartu Stok
        Route::resource('/kartu-stok', KartuStokController::class, ['parameters' => ['kartu-stok' => 'id']]);

        // User
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
        
        // Role
        Route::get('/role', [UserController::class, 'indexRole'])->name('role.index');
        Route::get('/role/{id}/edit', [UserController::class, 'editRole'])->name('role.edit');
        Route::put('/role/{id}/update', [UserController::class, 'updateRole'])->name('role.update');
        
        // Barcode
        // Route::get('/generate-qrcode', [ProductController::class, 'generateQrCode'])->name('generate-qrcode');
        Route::get('/generate-barcode', [ProductController::class, 'generateBarcode'])->name('generate-barcode');

        // Adjustment
        Route::get('/adjustment', [AdjustmentController::class, 'index'])->name('adjustment.index');
        Route::get('/adjustment-edit', [AdjustmentController::class, 'indexEdit'])->name('adjustment.index-edit');
        Route::post('/adjustment/show', [AdjustmentController::class, 'show'])->name('adjustment.show');
        Route::post('/adjustment/password', [AdjustmentController::class, 'password'])->name('adjustment.password');
        Route::post('/adjustment/edit', [AdjustmentController::class, 'edit'])->name('adjustment.edit');
        Route::post('/adjustment/update', [AdjustmentController::class, 'update'])->name('adjustment.update');
        Route::post('/adjustment/update-edit', [AdjustmentController::class, 'updateEdit'])->name('adjustment.update-edit');
        Route::get('/adjustment/cetak', [AdjustmentController::class, 'cetak'])->name('adjustment.cetak');
        Route::get('/adjustment/cetak-rokok', [AdjustmentController::class, 'cetakRokok'])->name('adjustment.cetak-rokok');
        Route::get('/adjustment/history', [AdjustmentController::class, 'indexHistory'])->name('adjustment.history.index');
        
        // Langganan
        Route::resource('/langganan', LanggananController::class, ['parameters' => ['langganan' => 'id']]);
    });
});
