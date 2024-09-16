<?php

namespace App\DataTables;

use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HistoryPembayaranDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->whereNotNull('id_parent')->whereNotNull('is_bayar')))
        ->addIndexColumn()
        ->addColumn('supplier_nama', function ($row) {
            return $row->supplier->nama;
        })
        ->editColumn('date', function ($row) {
            $date = Carbon::parse($row->date);
            return $date->format('d/m/Y');
        })
        ->editColumn('grand_total', function ($row) {
            return number_format($row->grand_total, 0);
        })
        ->addColumn('action', function ($row) {
            $btnDelete = '<a href="#" class="btn btn-danger btn-sm" onclick="deleteData(' . $row->id . ')" >HAPUS</a>';

            // Create a form for deleting the item
            $form = '<form id="delete-form-' . $row->id . '" action="' . route('pembayaran.destroy-history', $row->id) . '" method="POST" style="display: none;">';
            $form .= csrf_field();
            $form .= method_field('DELETE');
            $form .= '</form>';

            return $btnDelete . $form;
        })
        ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Pembayaran $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pembayaran-table')
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
            // Column::make('DT_RowIndex')->title('No.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nomor_bukti')->title('No Bukti')->addClass('text-center'),
            Column::make('date')->title('Tanggal')->addClass('text-center'),
            Column::make('supplier_nama')->title('Nama Supplier'),
            Column::make('nomor_giro')->title('Pembayaran'),
            Column::make('grand_total')->title('Jumlah')->addClass('text-end'),
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
        return 'Pembayaran_' . date('YmdHis');
    }
}
