<?php

namespace App\DataTables;

use App\Models\HistoryHutang;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HistoryHutangDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->orderBy('nomor_bukti', 'desc')))
        ->addIndexColumn()
        ->addColumn('supplier_nama', function ($row) {
            return $row->supplier->nama;    
        })
        ->editColumn('date', function ($row) {
            $date = Carbon::parse($row->date);
            return $date->format('d/m/Y');   
        })
        ->editColumn('total', function ($row) {
            return number_format($row->total, 0);
        })
        ->addColumn('action', function ($row) {
            // $button = '<div class="d-flex justify-content-center"><button disabled class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></button></div>';
            // if ($row->is_bayar == null) {
            // $btnDetail = '<a href="' . route('pembayaran-hutang.detail-hapus', $row->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
            // $button = '<div class="d-flex justify-content-center">' . $btnDetail . '</div>';
            // // }
            // return $button;
        })
        ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(HistoryHutang $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('history-hutang-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1, 'asc')
            ->language(['processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'])
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
            Column::make('DT_RowIndex')->title('No.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nomor_bukti')->title('No Bukti'),
            Column::make('date')->title('Tanggal'),
            Column::make('nomor')->title('Supplier'),
            Column::make('supplier_nama')->title('Nama Supplier'),
            Column::make('total')->title('Jumlah')->addClass('text-end'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'HistoryHutang_' . date('YmdHis');
    }
}
