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

class KartuStokDataTable extends DataTable
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
        
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', $search . '%');
            });
        }
        return (new EloquentDataTable($query->whereNull('kode_sumber')->orderBy('created_at', 'desc')))
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
        })
        ->editColumn('stok', function ($row) {
            return number_format($row->stok, 0);
        })
        ->editColumn('nama', function ($row) {
            return '<a href="' . route('master.kartu-stok.show', enkrip($row->id)) . '">' . $row->nama . '/' . $row->unit_jual . '</a>';
        })
        ->addColumn('tingkat', function ($row) {
            return $row->kode_sumber === null ? 'SUMBER' : 'ANAK';
        })
        ->addColumn('status', function ($row) {
            $status = null;
            if ($row->stok <= 0) $status = '<span class="badge badge-danger p-2">TIDAK AKTIF</span>';
            else $status = '<span class="badge badge-primary p-2">AKTIF</span>';
            return $status;
        })
        ->addColumn('action', function ($row) {
            $btnShow = '<a href="' . route('master.kartu-stok.show', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-pen "></i></a>';
            $button = '<div class="d-flex justify-content-center">' . $btnShow . '</div>';
            return $button;
        })
        ->rawColumns(['nama', 'status', 'action']);
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
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1, 'asc')
            ->language(['processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'])
            // ->parameters([
            //     'paging' => false,
            //     // 'searching' => false,
            //     'dom' => '<"top"f>rt<"bottom"ilp><"clear">',
            //     'ordering' => false,
            //     'lengthMenu' => [[-1], ['All']],
            //     'info' => false
            // ])
            ->parameters([
                "lengthMenu" => [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                'pageLength' => 100
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
            Column::make('nama')->title('NAMA BARANG'),
            Column::make('unit_jual')->title('UNIT JUAL')->addClass('text-center'),
            Column::make('stok')->title('STOK')->addClass('text-center'),
            Column::make('status')->title('STATUS')->addClass('text-center'),
            Column::make('tanggal')->title('TANGGAL')->addClass('text-center'),
            Column::make('jam')->title('JAM')->addClass('text-center'),
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
