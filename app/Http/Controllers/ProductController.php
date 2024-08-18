<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Departemen;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
    public function create()
    {
        $title = 'Create Product';
        $units = Unit::all();
        $departemens = Departemen::all();
        $suppliers = Supplier::all();
        $maxCode = Product::max('kode');

        if (!$maxCode) {
            $newCode = '00000001';
        } else {
            $newCode = str_pad((int)$maxCode + 1, 8, '0', STR_PAD_LEFT);
        }

        return view('master/product/create', compact('title', 'units', 'departemens', 'suppliers', 'newCode'));
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
            'ppn' => $request->ppn,
            'kode_alternatif' => $request->kode_alternatif,
            'merek' => $request->merek,
            'label' => $request->label, 
        ];
        // dd($data);

        $dataProduct = Product::create($data);

        return Redirect::route('master.product.show', $dataProduct->id)
            ->with('alert.status', '00')
            ->with('alert.message', "Add Product Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        $title = 'Kelompok Product';
        $product = Product::find($id);
        $lastKode = Product::max('kode');
        $nextKode = str_pad($lastKode + 1, 8, '0', STR_PAD_LEFT);
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();

        return view('master/product/child-view', compact('title', 'product', 'childProduct', 'nextKode'));
    }

    public function productParent(string $id)
    {
        $title = 'Parent Product';
        $product = Product::find($id);
        $lastKode = Product::max('kode');
        $nextKode = str_pad($lastKode + 1, 8, '0', STR_PAD_LEFT);
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();

        return view('master/product/parent', compact('title', 'product', 'childProduct', 'nextKode'));
    }

    public function productChild(string $id)
    {
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
            ]);

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

            return response()->json(['success' => true, 'id' => $newProduct->id]);
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
        $product = Product::find($id);
        $idSupplier = Supplier::where('nomor', $request->supplier)->first();
        
        if ($product->kode_sumber == null) {
            if (preg_replace('/[^0-9]/', '', $request->harga_pokok) > preg_replace('/[^0-9]/', '', $request->harga_jual)) {
                return Redirect::Back()->with('alert.status', '99')->with('alert.message', "HARGA JUAL LEBIH KECIL DARI HARGA POKOK")->withInput();
            }
        }
        
        $childProduct = Product::where('kode_sumber', $product->kode)->orderBy('harga_pokok', 'desc')->get();
        foreach($childProduct as $child) {
            $child->harga_pokok = (preg_replace('/[^0-9]/', '', $request->harga_pokok) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual);
            $child->harga_jual = (preg_replace('/[^0-9]/', '', $request->harga_pokok) / preg_replace('/[^0-9]/', '', $request->unit_beli)) * preg_replace('/[^0-9]/', '', $child->unit_jual);
            $child->profit = 0;
            $child->save();
        }

        $konversi = preg_replace('/[^0-9]/', '', $request->unit_beli) / preg_replace('/[^0-9]/', '', $request->unit_jual);
        $data = [
            'id_supplier' => $idSupplier->id,
            'id_unit' => $request->unit,
            'id_departemen' => $request->departemen,
            'kode' => $request->kode,
            'nama' => $request->nama_barang,
            'unit_beli' => $request->unit_beli,
            'unit_jual' => $request->unit_jual,
            'konversi' => $konversi,
            'harga_jual' => preg_replace('/[^0-9]/', '', $request->harga_jual),
            'profit' => $request->profit,
            'ppn' => $request->ppn,
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

        return Redirect::route('master.product.show', $product->id)
            ->with('alert.status', '00')
            ->with('alert.message', "Update Product Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::find($id)->delete();

        return Redirect::route('master.product.index')
            ->with('alert.status', '01')
            ->with('alert.message', "Delete Product Success!");
    }
}
