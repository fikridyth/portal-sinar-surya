<?php

namespace App\Http\Controllers;

use App\DataTables\HistoryPoDataTable;
use App\DataTables\MateraiDataTable;
use App\DataTables\SupplierDataTable;
use App\Models\Promosi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SupplierDataTable $dataTable)
    {
        $title = 'Master Supplier';

        return $dataTable->render('master.supplier.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Supplier';

        return view('master/supplier/create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
        ];

        $supplier = Supplier::create($data);

        return Redirect::route('master.supplier.show', $supplier->id)
            ->with('alert.status', '00')
            ->with('alert.message', "Add Supplier Success!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Show Supplier';
        $supplier = Supplier::find($id);

        return view('master/supplier/show', compact('title', 'supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Supplier';
        $supplier = Supplier::find($id);

        return view('master/supplier/edit', compact('title', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        $data = [
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
        ];

        $supplier->update($data);

        return Redirect::route('master.supplier.show', $supplier->id)
            ->with('alert.status', '00')
            ->with('alert.message', "Update Supplier Success!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Supplier::find($id)->delete();

        return Redirect::route('master.supplier.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Supplier Success!");
    }
    
    public function indexPromosi()
    {
        $title = 'Master Promosi';
        $promosi = Promosi::whereNull('nomor_bukti')->get();
        $suppliers = Supplier::all();
        $now = now()->format('Y-m-d');

        return view('master/supplier/promosi/index', compact('title', 'promosi', 'suppliers', 'now'));
    }
    
    public function indexAllPromosi()
    {
        $title = 'Master Promosi';
        $promosi = Promosi::all();

        return view('master/supplier/promosi/index-all', compact('title', 'promosi'));
    }

    public function storePromosi(Request $request)
    {
        // dd($request->all());

        // get nomor promosi
        $sequence = '0001';
        $dateNow = now()->format('ym');
        $getLastPo = Promosi::max("nomor_promosi");
        if ($getLastPo) {
            $explodeLastPo = explode('-', $getLastPo);
            if ($explodeLastPo[1] == $dateNow) {
                $sequence = (int) $explodeLastPo[2] + 1;
            } else {
                (int) $sequence;
            }
        } else {
            (int) $sequence;
        } 
        $getNomorPromo = 'AM-' . $dateNow . '-' . str_pad($sequence, 4, 0, STR_PAD_LEFT);

        Promosi::create([
            'id_supplier' => $request->supplier_id,
            'nomor_promosi' => $getNomorPromo,
            'tipe' => $request->tipe,
            'total' => $request->total,
            'date_first' => $request->date_first,
            'date_last' => $request->date_last,
        ]);

        return Redirect::route('master.promosi.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Tambah Promosi Success!");
    }

    public function updatePromosi(Request $request, $id)
    {
        // dd($request->id_supplier, $id);
        Promosi::find($id)->update([
            'id_supplier' => $request->id_supplier,
            'tipe' => $request->tipe,
            'total' => $request->total,
            'date_first' => $request->date_first,
            'date_last' => $request->date_last,
        ]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function destroyPromosi(string $id)
    {
        Promosi::find($id)->delete();

        return Redirect::route('master.promosi.index')
            ->with('alert.status', '00')
            ->with('alert.message', "Delete Promosi Success!");
    }
    
    public function indexMaterai(MateraiDataTable $dataTable)
    {
        $title = 'Master Materai';

        return $dataTable->render('master.supplier.index-materai', compact('title'));
    }

    public function updateMaterai($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->materai == 10000) $supplier->update(['materai' => 0]);
        else $supplier->update(['materai' => 10000]);

        return response()->json(['success' => true, 'message' => 'Data saved successfully.']);
    }

    public function indexHistoryPo(HistoryPoDataTable $dataTable)
    {
        $title = 'Master History Preorder';

        return $dataTable->render('master.supplier.index-history', compact('title'));
    }
}
