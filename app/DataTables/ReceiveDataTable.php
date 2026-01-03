<?php

namespace App\DataTables;

use App\Models\Preorder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReceiveDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->where('receive_type', 'B')->whereNotNull('is_cancel')->orderBy('created_at', 'asc')))
            ->addIndexColumn()
            ->addColumn('supplier_name', function ($row) {
                return $row->supplier->nama;
            })
            ->editColumn('receive_type', function ($row) {
                return $row->receive_type . 0;
            })
            ->editColumn('grand_total', function ($row) {
                return number_format($row->grand_total);
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('receive-po.create-detail', enkrip($row->id));
                // $doneUrl = route('receive-po.done-detail', enkrip($row->id));
                if ($row->nomor_bukti == null && $row->is_proses == null) {
                    return '<a href="' . $detailUrl . '" class="btn btn-primary btn-sm mx-2">DETAIL</a>';
                // } else if ($row->nomor_bukti == null && $row->is_proses == 1) {
                //     return '<a href="' . $doneUrl . '" class="btn btn-primary btn-sm mx-2">DETAIL</a>';
                } else {
                    return '<button disabled class="btn btn-primary btn-sm mx-2">DETAIL</button>';
                }
            })
            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->whereHas('supplier', function($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Preorder $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('receive-table')
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
            // Column::make('DT_RowIndex')->title('NO.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('supplier_name')->title('NAMA SUPPLIER'),
            Column::make('nomor_po')->title('NOMOR RECEIVE')->addClass('text-center'),
            Column::make('date_first')->title('TANGGAL')->addClass('text-center'),
            Column::make('grand_total')->title('TOTAL')->addClass('text-end'),
            // Column::make('receive_type')->title('REF')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->title('DETAIL')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Receive_' . date('YmdHis');
    }
}
