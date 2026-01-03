<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SearchProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        if (request()->has('search') && request()->input('search.value') != '') {
            $search = request()->input('search.value');
            $query->where('nama', 'like', $search . '%');
        }
        return (new EloquentDataTable($query->orderBy('nama', 'asc')))
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
        })
        ->editColumn('harga_pokok', function ($row) {
            return number_format($row->harga_pokok);
        })
        ->editColumn('harga_jual', function ($row) {
            return number_format($row->harga_jual);
        })
        // ->editColumn('nama', function ($row) {
        //     return '<a href="' . route('master.product.show', enkrip($row->id)) . '">' . $row->nama . '/' . $row->unit_jual . '</a>';
        // })
        ->addColumn('tingkat', function ($row) {
            return $row->kode_sumber === null ? 'SUMBER' : 'ANAK';
        })
        ->addColumn('select', function ($row) {
            return '<input type="checkbox" class="row-checkbox" name="selected_ids[]" value="' . $row->id . '">';
        })
        ->addColumn('action', function ($row) {
            $btnEdit = '<a href="' . route('master.product.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-pen "></i></a>';
            $btnDelete = '<a href="#" class="btn btn-danger btn-sm" onclick="deleteData(' . $row->id . ')" ><i class="fa fa-trash"></i></a>';

            $button = '<form id="delete-form-' . $row->id . '" action="' . route('master.product.destroy', $row->id) . '" method="POST" style="display: none;">';
            $button .= csrf_field();
            $button .= method_field('DELETE');
            $button .= '</form>';
            $button .= '<div class="d-flex justify-content-center">';
            $button .= '<div style="margin-right: 5px;">' . $btnEdit . '</div>';
            $button .= $btnDelete . '</div>';
            return $button;
        })
        ->rawColumns(['nama', 'select', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('search-product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->language(['processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'])
            ->parameters([
                'pageLength' => 500,
                'createdRow' => "function(row, data, dataIndex) {
                    $(row).attr('data-id', data.encrypted_id);
                }"
            ])
            ->buttons([''])
            ->addTableClass('table align-middle table-rounded table-striped table-row-gray-300 fs-6 gy-5');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nama')->title('NAMA BARANG')->addClass('text-center'),
            // Column::make('kode')->title('NO BARANG')->addClass('text-center'),
            // Column::make('kode_sumber')->title('SUMBER')->addClass('text-center'),
            // Column::make('tingkat')->title('TINGKAT')->addClass('text-center'),
            Column::make('unit_beli')->title('UNIT BELI')->addClass('text-center'),
            Column::make('unit_jual')->title('UNIT JUAL')->addClass('text-center'),
            Column::make('stok')->title('STOK')->addClass('text-center'),
            Column::make('harga_pokok')->title('HARGA BELI')->addClass('text-end'),
            Column::make('harga_jual')->title('HARGA JUAL')->addClass('text-end'),
            Column::make('select')->title('PILIH')->addClass('text-center'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->title('ACTION')
            //     ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
