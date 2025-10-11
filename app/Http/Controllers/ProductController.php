<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Departemen;
use App\Models\HargaSementaraPos;
use App\Models\HargaSementaraPos1;
use App\Models\HargaSementaraPos2;
use App\Models\HargaSementaraPos3;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\ProductPos;
use App\Models\ProductPos1;
use App\Models\ProductPos2;
use App\Models\ProductPos3;
use App\Models\SupplierPos1;
use App\Models\Supplier;
use App\Models\Unit;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        $title = 'Master Product';
        $titleHeader = 'MASTER PERSEDIAAN';

        return $dataTable->render('master.product.index', compact('title', 'titleHeader'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = dekrip($id);
        $title = 'Create Product';
        $titleHeader = 'MASTER PRODUCT';
        $units = Unit::all();
        $product = Product::find($id);
        $departemens = Departemen::all();
        $suppliers = Supplier::all();
        $maxCode = Product::max('kode');

        if (!$maxCode) {
            $newCode = '00000001';
        } else {
            $newCode = str_pad((int)$maxCode + 1, 8, '0', STR_PAD_LEFT);
        }

        return view('master/product/create', compact('title', 'units', 'departemens', 'suppliers', 'newCode', 'product', 'titleHeader'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idSupplier = Supplier::where('nomor', $request->supplier)->first();

        if (preg_replace('/[^0-9]/', '', $request->harga_pokok) > preg_replace('/[^0-9]/', '', $request->harga_jual)) {
            return Redirect::Back()->with('alert.status', '99')->with('alert.message', "HARGA JUAL LEBIH KECIL DARI HARGA POKOK")->withInput();
        }

        if ($request->ppn == 'on') {
            $ppn = 1;
        } else {
            $ppn = 0;
        }

        $data = [
            'id_supplier' => $idSupplier->id,
            'id_unit' => $request->unit,
            'id_departemen' => $request->departemen,
            'kode' => $request->kode,
            'nama' => strtoupper($request->nama_barang),
            'unit_beli' => strtoupper($request->unit_beli),
            'unit_jual' => strtoupper($request->unit_jual),
            'konversi' => 1,
            'harga_pokok' => preg_replace('/[^0-9]/', '', $request->harga_pokok),
            'harga_jual' => round($request->harga_jual),
            'profit' => $request->profit,
            'is_ppn' => $ppn,
            'kode_alternatif' => $request->kode_alternatif,
            'merek' => strtoupper($request->merek),
            'label' => strtoupper($request->label),
            'isi' => str_replace('P', '', $request->unit_jual),
            'harga_lama' => preg_replace('/[^0-9]/', '', $request->harga_pokok),
            'status' => 1,
        ];
        // dd($data);

        $dataProduct = Product::create($data);

        return Redirect::route('master.product.show', enkrip($dataProduct->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Add Product Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = dekrip($id);
        $title = 'Show Product';
        $titleHeader = 'MASTER PRODUCT';
        $product = Product::find($id);
        $parentProduct = Product::where('kode', $product->kode_sumber)->first();
        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();

        $lastOrder = null;
        $preorders = Preorder::orderBy('updated_at', 'desc')->get();
        foreach ($preorders as $preorder) {
            foreach (json_decode($preorder->detail, true) as $detail) {
                if ($detail['kode'] == $product->kode) {
                    $lastOrder = $preorder->updated_at;
                    break 2;
                }
            }
        }
        // dd($lastOrder, $product->kode);

        return view('master/product/show', compact('title', 'product', 'parentProduct', 'units', 'departemens', 'suppliers', 'titleHeader', 'lastOrder'));
    }

    public function productChildView(string $id)
    {
        $id = dekrip($id);
        $title = 'Kelompok Product';
        $product = Product::find($id);
        $lastKode = Product::max('kode');
        $nextKode = str_pad($lastKode + 1, 8, '0', STR_PAD_LEFT);
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();

        return view('master/product/child-view', compact('title', 'product', 'childProduct', 'nextKode'));
    }

    public function productParent(string $id)
    {
        $id = dekrip($id);
        $title = 'Parent Product';
        $product = Product::find($id);
        $lastKode = Product::max('kode');
        $nextKode = str_pad($lastKode + 1, 8, '0', STR_PAD_LEFT);
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();

        return view('master/product/parent', compact('title', 'product', 'childProduct', 'nextKode'));
    }

    public function productChild(string $id)
    {
        $id = dekrip($id);
        $title = 'Child Product';
        $product = Product::find($id);
        $lastKode = Product::max('kode');
        $nextKode = str_pad($lastKode + 1, 8, '0', STR_PAD_LEFT);
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();

        return view('master/product/child', compact('title', 'product', 'childProduct', 'nextKode'));
    }

    public function storeProductChild(Request $request)
    {
        try {
            $data = $request->json()->all();
            $parentProduct = Product::where('kode', $data['kode_sumber'])->first();

            $validatedData = $request->validate([
                'unit_jual' => 'nullable|string|max:255',
                'harga_jual' => 'nullable|numeric',
                'harga_pokok' => 'nullable|numeric', // Ensure harga_pokok is validated
            ]);

            if (isset($data['harga_pokok']) && isset($validatedData['harga_jual']) && $validatedData['harga_jual'] < $data['harga_pokok']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harga jual tidak boleh kurang dari harga pokok.'
                ], 400);
            }

            $newProduct = Product::create([
                'id_supplier' => $parentProduct->id_supplier,
                'id_unit' => $parentProduct->id_unit,
                'id_departemen' => $parentProduct->id_departemen,
                'kode' => $data['kode'],
                'kode_alternatif' => $data['kode_alternatif'],
                'nama' => $data['nama'],
                'unit_beli' => 'P' . $data['unit_beli'] ?? null,
                'unit_jual' => 'P' . $validatedData['unit_jual'] ?? null,
                'harga_pokok' => $data['harga_pokok'] ?? 0,
                'harga_jual' => $validatedData['harga_jual'] ?? 0,
                'profit' => $data['profit'] ?? 0,
                'konversi' => $data['konversi'] ?? null,
                'kode_sumber' => $data['kode_sumber'] ?? null,
                'isi' => str_replace('P', '', $validatedData['unit_jual']) ?? null,
                'harga_lama' => $data['harga_pokok'] ?? 0,
                'status' => 1,
            ]);

            return response()->json(['success' => true, 'data' => $newProduct]);
        } catch (\Exception $e) {
            // Log error
            \Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    public function storeProductParent(Request $request)
    {
        try {
            $data = $request->json()->all();

            if (!isset($data['unit_beli'])) {
                throw new \Exception('Missing required data');
            }

            $parentProduct = Product::where('kode', $data['kode_sumber'])->first();
            $childProduct = Product::where('kode_sumber', $data['kode_sumber'])->orderBy('harga_pokok', 'desc')->get();

            $newProduct = Product::create([
                'id_supplier' => $parentProduct->id_supplier,
                'id_unit' => $parentProduct->id_unit,
                'id_departemen' => $parentProduct->id_departemen,
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'unit_beli' => 'P' . $data['unit_beli'] ?? null,
                'unit_jual' => 'P' . $data['unit_beli'] ?? null,
                'harga_pokok' => (int)$data['harga_beli_input'] !== 0 ? $data['harga_beli_input'] : ($data['harga_beli'] / $data['unit_jual']) * $data['unit_beli'] ?? 0,
                'harga_jual' => (int)$data['harga_jual_input'] !== 0 ? $data['harga_jual_input'] : ($data['harga_jual'] / $data['unit_jual']) * $data['unit_beli'] ?? 0,
                'profit' => $data['profit'] ?? 0,
                'konversi' => $data['konversi'] ?? null,
                'kode_sumber' => null,
                'isi' => str_replace('P', '', $data['unit_jual']) ?? null,
                'harga_lama' => (int)$data['harga_beli_input'] !== 0 ? $data['harga_beli_input'] : ($data['harga_beli'] / $data['unit_jual']) * $data['unit_beli'] ?? 0,
                'status' => 1,
            ]);

            $parentProduct->update([
                'kode_sumber' => $newProduct->kode,
                'unit_beli' => $newProduct->unit_beli,
                'konversi' => str_replace('P', '', $newProduct->unit_beli) / str_replace('P', '', $parentProduct->unit_jual),
                'is_transfer' => null
            ]);

            foreach($childProduct as $child) {
                $child->kode_sumber = $newProduct->kode;
                $child->unit_beli = $newProduct->unit_beli;
                $child->konversi = str_replace('P', '', $newProduct->unit_beli) / str_replace('P', '', $child->unit_jual);
                $child->is_transfer = null;
                $child->save();
            }

            return response()->json(['success' => true, 'id' => enkrip($newProduct->id)]);
        } catch (\Exception $e) {
            // Log error
            \Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    public function storeToPos()
    {
        $products = Product::whereNull('is_transfer')->get();
        if ($products) {
            foreach ($products as $product) {
                $existingProductPos = ProductPos::where('kode', $product->kode)->first();
                $existingSupplierProductPos = ProductPos::where('id_supplier', $product->id_supplier)->first();
                if ($existingProductPos && $existingSupplierProductPos) {
                    $existingProductPos->update([
                        'id_supplier' => $product->id_supplier,
                        'id_unit' => $product->id_unit,
                        'id_departemen' => $product->id_departemen,
                        'kode_alternatif' => $product->kode_alternatif,
                        'kode_sumber' => $product->kode_sumber,
                        'kode_alternatif_2' => $product->kode_alternatif_2,
                        'nama' => $product->nama,
                        'merek' => $product->merek,
                        'label' => $product->label,
                        'unit_beli' => $product->unit_beli,
                        'unit_jual' => $product->unit_jual,
                        'konversi' => $product->konversi,
                        'harga_pokok' => $product->harga_pokok,
                        'harga_jual' => $product->harga_jual,
                        'diskon1' => $product->diskon1,
                        'diskon2' => $product->diskon2,
                        'diskon3' => $product->diskon3,
                        'isi' => $product->isi,
                        'stok' => $product->stok,
                        'status' => $product->status,
                        'harga_sementara' => $product->harga_sementara,
                        'tanggal_awal' => $product->tanggal_awal,
                        'tanggal_akhir' => $product->tanggal_akhir,
                        'harga_lama' => $product->harga_pokok,
                    ]);
                } else {
                    ProductPos::create([
                        'id_supplier' => $product->id_supplier,
                        'id_unit' => $product->id_unit,
                        'id_departemen' => $product->id_departemen,
                        'kode' => $product->kode,
                        'kode_alternatif' => $product->kode_alternatif,
                        'kode_sumber' => $product->kode_sumber,
                        'kode_alternatif_2' => $product->kode_alternatif_2,
                        'nama' => $product->nama,
                        'merek' => $product->merek,
                        'label' => $product->label,
                        'unit_beli' => $product->unit_beli,
                        'unit_jual' => $product->unit_jual,
                        'konversi' => $product->konversi,
                        'harga_pokok' => $product->harga_pokok,
                        'harga_jual' => $product->harga_jual,
                        'diskon1' => $product->diskon1,
                        'diskon2' => $product->diskon2,
                        'diskon3' => $product->diskon3,
                        'isi' => $product->isi,
                        'stok' => $product->stok,
                        'status' => $product->status,
                        'harga_sementara' => $product->harga_sementara,
                        'tanggal_awal' => $product->tanggal_awal,
                        'tanggal_akhir' => $product->tanggal_akhir,
                        'harga_lama' => $product->harga_pokok,
                    ]);
                }

                try {
                    // Cek koneksi ke database client
                    DB::connection('mysql_pos_1')->getPdo();

                    // Lanjutkan jika koneksi berhasil
                    $existingProductPos1 = ProductPos1::where('kode', $product->kode)->first();
                    $existingSupplierProductPos1 = ProductPos1::where('id_supplier', $product->id_supplier)->first();
                    if ($existingProductPos1 && $existingSupplierProductPos1) {
                        $existingProductPos1->update([
                            'id_supplier' => $product->id_supplier,
                            'id_unit' => $product->id_unit,
                            'id_departemen' => $product->id_departemen,
                            'kode_alternatif' => $product->kode_alternatif,
                            'kode_sumber' => $product->kode_sumber,
                            'kode_alternatif_2' => $product->kode_alternatif_2,
                            'nama' => $product->nama,
                            'merek' => $product->merek,
                            'label' => $product->label,
                            'unit_beli' => $product->unit_beli,
                            'unit_jual' => $product->unit_jual,
                            'konversi' => $product->konversi,
                            'harga_pokok' => $product->harga_pokok,
                            'harga_jual' => $product->harga_jual,
                            'diskon1' => $product->diskon1,
                            'diskon2' => $product->diskon2,
                            'diskon3' => $product->diskon3,
                            'isi' => $product->isi,
                            'stok' => $product->stok,
                            'status' => $product->status,
                            'harga_sementara' => $product->harga_sementara,
                            'tanggal_awal' => $product->tanggal_awal,
                            'tanggal_akhir' => $product->tanggal_akhir,
                            'harga_lama' => $product->harga_pokok,
                        ]);
                    } else {
                        ProductPos1::create([
                            'id_supplier' => $product->id_supplier,
                            'id_unit' => $product->id_unit,
                            'id_departemen' => $product->id_departemen,
                            'kode' => $product->kode,
                            'kode_alternatif' => $product->kode_alternatif,
                            'kode_sumber' => $product->kode_sumber,
                            'kode_alternatif_2' => $product->kode_alternatif_2,
                            'nama' => $product->nama,
                            'merek' => $product->merek,
                            'label' => $product->label,
                            'unit_beli' => $product->unit_beli,
                            'unit_jual' => $product->unit_jual,
                            'konversi' => $product->konversi,
                            'harga_pokok' => $product->harga_pokok,
                            'harga_jual' => $product->harga_jual,
                            'diskon1' => $product->diskon1,
                            'diskon2' => $product->diskon2,
                            'diskon3' => $product->diskon3,
                            'isi' => $product->isi,
                            'stok' => $product->stok,
                            'status' => $product->status,
                            'harga_sementara' => $product->harga_sementara,
                            'tanggal_awal' => $product->tanggal_awal,
                            'tanggal_akhir' => $product->tanggal_akhir,
                            'harga_lama' => $product->harga_pokok,
                        ]);
                    }
                } catch (\PDOException $e) {
                    return Redirect::route('index')
                        ->with('alert.status', '99')
                        ->with('alert.message', "Transfer Product Ke POS 1 Gagal! (Koneksi Jaringan)");
                } catch (QueryException $e) {
                    return Redirect::route('index')
                        ->with('alert.status', '99')
                        ->with('alert.message', "Transfer Product Ke POS 1 Gagal! (Gagal Query)");
                }

                // try {
                //     // Cek koneksi ke database client
                //     DB::connection('mysql_pos_2')->getPdo();

                //     // Lanjutkan jika koneksi berhasil
                //     $existingProductPos2 = ProductPos2::where('kode', $product->kode)->first();
                //     if ($existingProductPos2) {
                //         $existingProductPos2->update([
                //             'id_supplier' => $product->id_supplier,
                //             'id_unit' => $product->id_unit,
                //             'id_departemen' => $product->id_departemen,
                //             'kode_alternatif' => $product->kode_alternatif,
                //             'kode_sumber' => $product->kode_sumber,
                //             'kode_alternatif_2' => $product->kode_alternatif_2,
                //             'nama' => $product->nama,
                //             'merek' => $product->merek,
                //             'label' => $product->label,
                //             'unit_beli' => $product->unit_beli,
                //             'unit_jual' => $product->unit_jual,
                //             'konversi' => $product->konversi,
                //             'harga_pokok' => $product->harga_pokok,
                //             'harga_jual' => $product->harga_jual,
                //             'diskon1' => $product->diskon1,
                //             'diskon2' => $product->diskon2,
                //             'diskon3' => $product->diskon3,
                //             'isi' => $product->isi,
                //             'status' => $product->status,
                //             'harga_sementara' => $product->harga_sementara,
                //             'tanggal_awal' => $product->tanggal_awal,
                //             'tanggal_akhir' => $product->tanggal_akhir,
                //             'harga_lama' => $product->harga_pokok,
                //         ]);
                //     } else {
                //         ProductPos2::create([
                //             'id_supplier' => $product->id_supplier,
                //             'id_unit' => $product->id_unit,
                //             'id_departemen' => $product->id_departemen,
                //             'kode' => $product->kode,
                //             'kode_alternatif' => $product->kode_alternatif,
                //             'kode_sumber' => $product->kode_sumber,
                //             'kode_alternatif_2' => $product->kode_alternatif_2,
                //             'nama' => $product->nama,
                //             'merek' => $product->merek,
                //             'label' => $product->label,
                //             'unit_beli' => $product->unit_beli,
                //             'unit_jual' => $product->unit_jual,
                //             'konversi' => $product->konversi,
                //             'harga_pokok' => $product->harga_pokok,
                //             'harga_jual' => $product->harga_jual,
                //             'diskon1' => $product->diskon1,
                //             'diskon2' => $product->diskon2,
                //             'diskon3' => $product->diskon3,
                //             'isi' => $product->isi,
                //             'status' => $product->status,
                //             'harga_sementara' => $product->harga_sementara,
                //             'tanggal_awal' => $product->tanggal_awal,
                //             'tanggal_akhir' => $product->tanggal_akhir,
                //             'harga_lama' => $product->harga_pokok,
                //         ]);
                //     }
                // } catch (\PDOException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Product Ke POS 2 Gagal! (Koneksi Jaringan)");
                // } catch (QueryException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Product Ke POS 2 Gagal! (Gagal Query)");
                // }

                // try {
                //     // Cek koneksi ke database client
                //     DB::connection('mysql_pos_3')->getPdo();

                //     // Lanjutkan jika koneksi berhasil
                //     $existingProductPos3 = ProductPos3::where('kode', $product->kode)->first();
                //     if ($existingProductPos3) {
                //         $existingProductPos3->update([
                //             'id_supplier' => $product->id_supplier,
                //             'id_unit' => $product->id_unit,
                //             'id_departemen' => $product->id_departemen,
                //             'kode_alternatif' => $product->kode_alternatif,
                //             'kode_sumber' => $product->kode_sumber,
                //             'kode_alternatif_2' => $product->kode_alternatif_2,
                //             'nama' => $product->nama,
                //             'merek' => $product->merek,
                //             'label' => $product->label,
                //             'unit_beli' => $product->unit_beli,
                //             'unit_jual' => $product->unit_jual,
                //             'konversi' => $product->konversi,
                //             'harga_pokok' => $product->harga_pokok,
                //             'harga_jual' => $product->harga_jual,
                //             'diskon1' => $product->diskon1,
                //             'diskon2' => $product->diskon2,
                //             'diskon3' => $product->diskon3,
                //             'isi' => $product->isi,
                //             'status' => $product->status,
                //             'harga_sementara' => $product->harga_sementara,
                //             'tanggal_awal' => $product->tanggal_awal,
                //             'tanggal_akhir' => $product->tanggal_akhir,
                //             'harga_lama' => $product->harga_pokok,
                //         ]);
                //     } else {
                //         ProductPos3::create([
                //             'id_supplier' => $product->id_supplier,
                //             'id_unit' => $product->id_unit,
                //             'id_departemen' => $product->id_departemen,
                //             'kode' => $product->kode,
                //             'kode_alternatif' => $product->kode_alternatif,
                //             'kode_sumber' => $product->kode_sumber,
                //             'kode_alternatif_2' => $product->kode_alternatif_2,
                //             'nama' => $product->nama,
                //             'merek' => $product->merek,
                //             'label' => $product->label,
                //             'unit_beli' => $product->unit_beli,
                //             'unit_jual' => $product->unit_jual,
                //             'konversi' => $product->konversi,
                //             'harga_pokok' => $product->harga_pokok,
                //             'harga_jual' => $product->harga_jual,
                //             'diskon1' => $product->diskon1,
                //             'diskon2' => $product->diskon2,
                //             'diskon3' => $product->diskon3,
                //             'isi' => $product->isi,
                //             'status' => $product->status,
                //             'harga_sementara' => $product->harga_sementara,
                //             'tanggal_awal' => $product->tanggal_awal,
                //             'tanggal_akhir' => $product->tanggal_akhir,
                //             'harga_lama' => $product->harga_pokok,
                //         ]);
                //     }
                // } catch (\PDOException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Product Ke POS 3 Gagal! (Koneksi Jaringan)");
                // } catch (QueryException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Product Ke POS 3 Gagal! (Gagal Query)");
                // }

                // update product status
                $product->update(['is_transfer' => 1]);
            }
        }

        $hargaSementara = HargaSementaraPos::whereNull('is_transfer')->get();
        if ($hargaSementara) {
            foreach ($hargaSementara as $harga) {
                try {
                    // Cek koneksi ke database client
                    DB::connection('mysql_pos_1')->getPdo();
                    // $existingSupplierProductPos = SupplierPos1::where('id_supplier', $harga->id_supplier)->first();
                    // Lanjutkan jika koneksi berhasil
                    // if ($existingSupplierProductPos) {
                        HargaSementaraPos1::create([
                            'id_supplier' => $harga->id_supplier,
                            'id_product' => $harga->id_product,
                            'nomor' => $harga->nomor,
                            'nama' => $harga->nama,
                            'harga_lama' => $harga->harga_lama,
                            'harga_pokok' => $harga->harga_pokok,
                            'profit_pokok' => $harga->profit_pokok,
                            'harga_jual' => $harga->harga_jual,
                            'profit_jual' => $harga->profit_jual,
                            'harga_sementara' => $harga->harga_sementara,
                            'date_first' => $harga->date_first,
                            'date_last' => $harga->date_last,
                            'naik' => $harga->naik
                        ]);
                    // }
                } catch (\PDOException $e) {
                    return Redirect::route('index')
                        ->with('alert.status', '99')
                        ->with('alert.message', "Transfer Harga Ke POS 1 Gagal! (Koneksi Jaringan)");
                } catch (QueryException $e) {
                    return Redirect::route('index')
                        ->with('alert.status', '99')
                        ->with('alert.message', "Transfer Harga Ke POS 1 Gagal! (Gagal Query)");
                }

                // try {
                //     // Cek koneksi ke database client
                //     DB::connection('mysql_pos_2')->getPdo();

                //     // Lanjutkan jika koneksi berhasil
                //     HargaSementaraPos2::create([
                //         'id_supplier' => $hargaSementara->id_supplier,
                //         'id_product' => $hargaSementara->id_product,
                //         'nomor' => $hargaSementara->nomor,
                //         'nama' => $hargaSementara->nama,
                //         'harga_lama' => $hargaSementara->harga_lama,
                //         'harga_pokok' => $hargaSementara->harga_pokok,
                //         'profit_pokok' => $hargaSementara->profit_pokok,
                //         'harga_jual' => $hargaSementara->harga_jual,
                //         'profit_jual' => $hargaSementara->profit_jual,
                //         'harga_sementara' => $hargaSementara->harga_sementara,
                //         'date_first' => $hargaSementara->date_first,
                //         'date_last' => $hargaSementara->date_last,
                //         'naik' => $hargaSementara->naik
                //     ]);
                // } catch (\PDOException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Harga Ke POS 2 Gagal! (Koneksi Jaringan)");
                // } catch (QueryException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Harga Ke POS 2 Gagal! (Gagal Query)");
                // }

                // try {
                //     // Cek koneksi ke database client
                //     DB::connection('mysql_pos_3')->getPdo();

                //     // Lanjutkan jika koneksi berhasil
                //     HargaSementaraPos3::create([
                //         'id_supplier' => $hargaSementara->id_supplier,
                //         'id_product' => $hargaSementara->id_product,
                //         'nomor' => $hargaSementara->nomor,
                //         'nama' => $hargaSementara->nama,
                //         'harga_lama' => $hargaSementara->harga_lama,
                //         'harga_pokok' => $hargaSementara->harga_pokok,
                //         'profit_pokok' => $hargaSementara->profit_pokok,
                //         'harga_jual' => $hargaSementara->harga_jual,
                //         'profit_jual' => $hargaSementara->profit_jual,
                //         'harga_sementara' => $hargaSementara->harga_sementara,
                //         'date_first' => $hargaSementara->date_first,
                //         'date_last' => $hargaSementara->date_last,
                //         'naik' => $hargaSementara->naik
                //     ]);
                // } catch (\PDOException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Harga Ke POS 3 Gagal! (Koneksi Jaringan)");
                // } catch (QueryException $e) {
                //     return Redirect::route('index')
                //         ->with('alert.status', '99')
                //         ->with('alert.message', "Transfer Harga Ke POS 3 Gagal! (Gagal Query)");
                // }

                // update harga status
                $harga->update(['is_transfer' => 1]);
            }
        }

        return Redirect::route('index')
            ->with('alert.status', '00')
            ->with('alert.message', "Transfer Ke POS Success!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = dekrip($id);
        $title = 'Edit Product';
        $titleHeader = 'MASTER PRODUCT';
        $product = Product::find($id);
        $parentProduct = Product::where('kode', $product->kode_sumber)->first();
        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();

        return view('master/product/edit', compact('title', 'product', 'parentProduct', 'units', 'departemens', 'suppliers', 'titleHeader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = dekrip($id);
        // dd($request->all());
        $product = Product::find($id);
        $idSupplier = Supplier::where('nomor', $request->supplier)->first();

        // if ($product->kode_sumber == null) {
        if (preg_replace('/[^0-9]/', '', $request->harga_pokok_rata) > preg_replace('/[^0-9]/', '', $request->harga_jual)) {
            return Redirect::Back()->with('alert.status', '99')->with('alert.message', "HARGA JUAL LEBIH KECIL DARI HARGA POKOK")->withInput();
        }
        // }

        if ($request->ppn == 'on') {
            $ppn = 1;
        } else {
            $ppn = 0;
        }

        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();
        foreach($childProduct as $child) {
            $child->harga_pokok = round((preg_replace('/[^0-9]/', '', $request->harga_pokok) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual));
            $child->harga_jual = round((preg_replace('/[^0-9]/', '', $request->harga_jual) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual));
            $child->profit = $request->profit;
            $child->save();
        }

        $namaBarang = explode('/', $request->nama_barang);
        $konversi = preg_replace('/[^0-9]/', '', $request->unit_beli) / preg_replace('/[^0-9]/', '', $request->unit_jual);
        $data = [
            'id_supplier' => $idSupplier->id,
            'id_unit' => $request->unit,
            'id_departemen' => $request->departemen,
            'kode' => $request->kode,
            'nama' => strtoupper($namaBarang[0]),
            'unit_beli' => strtoupper($request->unit_beli),
            'unit_jual' => strtoupper($request->unit_jual),
            'konversi' => $konversi,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'profit' => $request->profit,
            'is_ppn' => $ppn,
            'kode_alternatif' => $request->kode_alternatif,
            'merek' => strtoupper($request->merek),
            'label' => strtoupper($request->label),
            'isi' => str_replace('P', '', $request->unit_jual),
            'status' => 1,
            'is_transfer' => null,
        ];
        // if ($product->kode_sumber == null) {
        //     $data['harga_pokok'] = preg_replace('/[^0-9]/', '', $request->harga_pokok);
        // } else {
        //     $data['harga_pokok'] = preg_replace('/[^0-9]/', '', $request->harga_pokok_rata);
        // }
        // dd($data);

        $product->update($data);

        return Redirect::route('master.product.show', enkrip($product->id))
            ->with('alert.status', '00')
            ->with('alert.message', "Update Product Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = dekrip($id);
        Product::find($id)->delete();

        return Redirect::route('master.product.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Product Success!");
    }

    public function stockOpname()
    {
        $title = 'Stock Opname';

        $filters = request(['unit', 'departemen', 'supplier']);
        if (array_filter($filters)) {
            $products = Product::where('status', 1)->where('stok', '>', 0)->Filter($filters)->orderBy('nama', 'asc')->get();
        } else {
            $products = collect();
        }

        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();

        return view('master/product/stock-opname', compact('title', 'products', 'units', 'departemens', 'suppliers'));
    }

    public function updateStockOpname(Request $request)
    {
        $orderData = $request->input('order');
        // dd($orderData);

        // Loop melalui data order dan update stok setiap produk
        if (isset($orderData)) {
            foreach ($orderData as $productId => $stok) {
                $product = Product::find($productId);
                $productStok = explode('.', $product->stok);
                if ($productStok[0] !== $stok) {
                    $product->stok = $stok;
                    $product->is_transfer = null;
                    $product->save();
                }
            }
        } else {
            return Redirect::Back()->with('alert.status', '99')->with('alert.message', "FILTER DATA DAHULU");
        }

        return Redirect::back()
        ->with('alert.status', '00')
        ->with('alert.message', "Update Stock Opname Success!");
    }

    // public function generateQrCode()
    // {
    //     // dd('test qr');
    //     $writer = new PngWriter();

    //     // Create QR code
    //     $qrCode = QrCode::create('Life is too short to be generating QR codes')
    //         ->setEncoding(new Encoding('UTF-8'))
    //         ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
    //         ->setSize(300)
    //         ->setMargin(10)
    //         ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
    //         ->setForegroundColor(new Color(0, 0, 0))
    //         ->setBackgroundColor(new Color(255, 255, 255));

    //     // Create generic logo
    //     // $logo = Logo::create(public_path('assets/symfony.png'))
    //     //     ->setResizeToWidth(50)
    //     //     ->setPunchoutBackground(true)
    //     // ;

    //     // Create generic label
    //     $label = Label::create('Label')
    //         ->setTextColor(new Color(255, 0, 0));

    //     $result = $writer->write($qrCode, null, $label);

    //     // Validate the result
    //     $writer->validateResult($result, 'Life is too short to be generating QR codes');

    //     // Directly output the QR code
    //     header('Content-Type: '.$result->getMimeType());
    //     echo $result->getString();

    //     // Save it to a file
    //     $result->saveToFile(public_path('qrcode.png'));

    //     // Generate a data URI to include image data inline (i.e. inside an <img> tag)
    //     $result->getDataUri();

    //     return Redirect::route('index')
    //         ->with('alert.status', '00')
    //         ->with('alert.message', "QR Code Berhasil Generate!");
    // }

    public function generateBarcode()
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode('8992770011114', $generator::TYPE_EAN_13, 3, 100);

        // Create a PNG image from the barcode string
        $barcodeImage = imagecreatefromstring($barcode);
        $barcodeWidth = imagesx($barcodeImage);
        $barcodeHeight = imagesy($barcodeImage);

        // Set font properties
        $text = '6901279851338'; // Barcode value
        $fontPath = public_path('arial.ttf'); // Path to your font file
        $fontSize = 12; // Font size

        // Calculate the bounding box of the text
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[1] - $textBox[7]; // Height of the text

        // Calculate the final image dimensions
        $width = max($barcodeWidth, $textWidth);
        $height = $barcodeHeight + $textHeight + 30; // Add some padding

        // Create the final image
        $image = imagecreate($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255); // White background
        imagefill($image, 0, 0, $white);

        // Center the barcode
        $x = ($width - $barcodeWidth) / 2;
        $y = ($height - $barcodeHeight - $textHeight) / 2; // Center vertically

        // Copy the barcode image onto the new image
        imagecopy($image, $barcodeImage, $x, $y, 0, 0, $barcodeWidth, $barcodeHeight);

        // Position the text below the barcode
        $textY = $y + $barcodeHeight + 20; // Position below the barcode

        // Allocate a color for the text (black)
        $black = imagecolorallocate($image, 0, 0, 0);
        imagettftext($image, $fontSize, 0, ($width - $textWidth) / 2, $textY, $black, $fontPath, $text);

        // Save the final image
        imagepng($image, public_path('barcode.png'));
        imagedestroy($image);
        imagedestroy($barcodeImage);

        return Redirect::route('index')
            ->with('alert.status', '00')
            ->with('alert.message', "Barcode Berhasil Generate!");
    }

    public function getDetailProducts($kode)
    {
        // $product = Product::where('kode_alternatif', $kode)->first();
        $product = Product::where('kode_alternatif', $kode)->first();
        if (isset($product)) {
            $idProduct = enkrip($product->id);
            return response()->json(['product' => $idProduct]);
        } else {
            return response()->json(['error' => 'KODE BELUM TERSEDIA!']);
        }

        return response()->json(['product' => $product]);
    }

    public function updateStatus($id)
    {
        $product = Product::find($id);

        if ($product->status == 1) {
            $product->update(['status' => 0]);
        } else {
            $product->update(['status' => 1]);
        }

        return response()->json(['message' => 'Product status updated successfully']);
    }

    public function indexHistoryProduct($id)
    {
        $id = dekrip($id);
        $title = 'Master History Product';
        $titleHeader = 'MASTER PERSEDIAAN';

        $product = Product::find($id);
        $orderProduct = [];
        $preorders = Preorder::where('receive_type', 'B')->orderBy('updated_at', 'asc')->get();

        foreach ($preorders as $preorder) {
            foreach (json_decode($preorder->detail, true) as $detail) {
                if ($detail['kode'] == $product->kode) {
                    $alreadyExists = false;
                    foreach ($orderProduct as $existingOrder) {
                        if ($existingOrder->nomor_receive == $preorder->nomor_receive) {
                            $alreadyExists = true;
                            break;
                        }
                    }

                    if (!$alreadyExists) {
                        $orderProduct[] = $preorder;
                    }
                }
            }
        }
        // dd($orderProduct);

        return view('master.product.index-history', compact('title', 'titleHeader', 'orderProduct', 'product'));
    }

    public function editNama($id) {
        $title = 'Edit Nama';
        $titleHeader = 'EDIT NAMA PRODUCT';
        $id = dekrip($id);
        // dd($request->all());

        $product = Product::findOrFail($id);

        $rawProducts = Product::where('nama', '>=', $product->nama)
                   ->orderBy('nama', 'asc')
                   ->limit(1001)
                   ->get();

        // Hilangkan karakter '/' dari nama produk dan ambil yang unik berdasarkan hasil replace
        $products = $rawProducts->unique(function ($item) {
            return str_replace('/', '', $item->nama);
        });

        // Jika ingin reset array index (opsional)
        $products = $products->values();

        return view('master.product.edit-name', compact('title', 'titleHeader', 'products', 'product'));
    }

    public function updateEditNama(request $request) {
        $hasil = [];

        foreach ($request->input('selected_ids', []) as $id) {
            $hasil[] = [
                'id' => $id,
                'nama' => $request->input("fisik.$id"),
                'nama_lama' => $request->input("old_fisik.$id"),
            ];
        }

        foreach ($hasil as $data) {
            $products = Product::where('nama', $data['nama_lama'])->get();
            foreach ($products as $product) {
                $product->update([
                    'nama' => $data['nama']
                ]);
            }
        }

        return Redirect::route('master.product.show', enkrip(1))
            ->with('alert.status', '00')
            ->with('alert.message', "Ubah Nama Berhasil!");
    }

    // public function getHistoryProduct(Request $request)
    // {
    //     // dd($request->all());
    //     $nomor = $request->input('nomor');

    //     // Fetch data from database
    //     $detail = HistoryPreorderDetail::where('nomor_receive', $nomor)->get();
    //     $detailData = $detail->map(function($item) {
    //         $item->product_nama = $item->product->nama;
    //         $item->product_unit_jual = $item->product->unit_jual;
    //         return $item;
    //     });
    //     // dd($detail[0]->product->nama);

    //     return response()->json(['dataDetail' => $detailData]);
    // }
}
