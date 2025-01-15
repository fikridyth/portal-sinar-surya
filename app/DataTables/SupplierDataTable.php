<?php

namespace App\DataTables;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SupplierDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->orderBy('id', 'asc')))
        ->addIndexColumn()
        ->editColumn('created_at', function ($row) {
            return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
        })
        ->editColumn('nama', function ($row) {
            return '<a href="' . route('master.supplier.show', enkrip($row->id)) . '">' . $row->nama . '</a>';
        })
        ->addColumn('encrypted_id', function ($row) {
            return enkrip($row->id);
        })
        ->addColumn('action', function ($row) {
            $btnEdit = '<a href="' . route('master.supplier.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-pen "></i></a>';
            $btnDelete = '<a href="#" class="btn btn-danger btn-sm" onclick="deleteData(' . $row->id . ')" ><i class="fa fa-trash"></i></a>';

            $button = '<form id="delete-form-' . $row->id . '" action="' . route('master.supplier.destroy', $row->id) . '" method="POST" style="display: none;">';
            $button .= csrf_field();
            $button .= method_field('DELETE');
            $button .= '</form>';
            $button .= '<div class="d-flex justify-content-center">';
            $button .= '<div style="margin-right: 5px;">' . $btnEdit . '</div>';
            $button .= $btnDelete . '</div>';
            return $button;
        })
        ->rawColumns(['nama', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Supplier $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->language(['processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'])
            ->parameters([
                'pageLength' => 2000,
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
            Column::make('DT_RowIndex')->title('No.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nama'),
            Column::make('nomor')->addClass('text-center'),
            Column::make('alamat1')->addClass('text-center'),
            Column::make('alamat2')->addClass('text-center'),
            // Column::make('created_at')->title('Tanggal Dibuat')->addClass('text-center'),
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
        return 'Supplier_' . date('YmdHis');
    }
}
