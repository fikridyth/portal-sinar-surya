<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Departemen;
use App\Models\Product;
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

        return $dataTable->render('master.product.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = dekrip($id);
        $title = 'Create Product';
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

        return view('master/product/create', compact('title', 'units', 'departemens', 'suppliers', 'newCode', 'product'));
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
            'nama' => $request->nama_barang,
            'unit_beli' => $request->unit_beli,
            'unit_jual' => $request->unit_jual,
            'konversi' => 1,
            'harga_pokok' => preg_replace('/[^0-9]/', '', $request->harga_pokok),
            'harga_jual' => preg_replace('/[^0-9]/', '', $request->harga_jual),
            'profit' => $request->profit,
            'is_ppn' => $ppn,
            'kode_alternatif' => $request->kode_alternatif,
            'merek' => $request->merek,
            'label' => $request->label, 
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
        $product = Product::find($id);
        $parentProduct = Product::where('kode', $product->kode_sumber)->first();
        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();

        return view('master/product/show', compact('title', 'product', 'parentProduct', 'units', 'departemens', 'suppliers'));
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
                'nama' => $data['nama'],
                'unit_beli' => 'P' . $data['unit_beli'] ?? null,
                'unit_jual' => 'P' . $validatedData['unit_jual'] ?? null,
                'harga_pokok' => $data['harga_pokok'] ?? 0,
                'harga_jual' => $validatedData['harga_jual'] ?? 0,
                'profit' => $data['profit'] ?? 0,
                'konversi' => $data['konversi'] ?? null,
                'kode_sumber' => $data['kode_sumber'] ?? null,
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
                'harga_pokok' => ($data['harga_beli'] / $data['unit_jual']) * $data['unit_beli'] ?? 0,
                'harga_jual' => ($data['harga_jual'] / $data['unit_jual']) * $data['unit_beli'] ?? 0,
                'profit' => $data['profit'] ?? 0,
                'konversi' => $data['konversi'] ?? null,
                'kode_sumber' => null,
            ]);

            $parentProduct->update([
                'kode_sumber' => $newProduct->kode,
                'unit_beli' => $newProduct->unit_beli,
                'konversi' => str_replace('P', '', $newProduct->unit_beli) / str_replace('P', '', $parentProduct->unit_jual),
            ]);

            foreach($childProduct as $child) {
                $child->kode_sumber = $newProduct->kode;
                $child->unit_beli = $newProduct->unit_beli;
                $child->konversi = str_replace('P', '', $newProduct->unit_beli) / str_replace('P', '', $child->unit_jual);
                $child->save();
            }

            return response()->json(['success' => true, 'id' => enkrip($newProduct->id)]);
        } catch (\Exception $e) {
            // Log error
            \Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = dekrip($id);
        $title = 'Edit Product';
        $product = Product::find($id);
        $parentProduct = Product::where('kode', $product->kode_sumber)->first();
        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();

        return view('master/product/edit', compact('title', 'product', 'parentProduct', 'units', 'departemens', 'suppliers'));
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
            $child->harga_pokok = (preg_replace('/[^0-9]/', '', $request->harga_pokok) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual);
            $child->harga_jual = (preg_replace('/[^0-9]/', '', $request->harga_pokok) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual);
            $child->profit = 0;
            $child->save();
        }

        $namaBarang = explode('/', $request->nama_barang);
        $konversi = preg_replace('/[^0-9]/', '', $request->unit_beli) / preg_replace('/[^0-9]/', '', $request->unit_jual);
        $data = [
            'id_supplier' => $idSupplier->id,
            'id_unit' => $request->unit,
            'id_departemen' => $request->departemen,
            'kode' => $request->kode,
            'nama' => $namaBarang[0],
            'unit_beli' => $request->unit_beli,
            'unit_jual' => $request->unit_jual,
            'konversi' => $konversi,
            'harga_jual' => preg_replace('/[^0-9]/', '', $request->harga_jual),
            'profit' => $request->profit,
            'is_ppn' => $ppn,
            'kode_alternatif' => $request->kode_alternatif,
            'merek' => $request->merek,
            'label' => $request->label, 
        ];
        if ($product->kode_sumber == null) {
            $data['harga_pokok'] = preg_replace('/[^0-9]/', '', $request->harga_pokok);
        } else {
            $data['harga_pokok'] = preg_replace('/[^0-9]/', '', $request->harga_pokok_rata);
        }
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
                if ($product) {
                    $product->stok = $stok;
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
}
