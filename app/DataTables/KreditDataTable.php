<?php

namespace App\DataTables;

use App\Models\Kredit;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KreditDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->whereNull('nomor')))
            ->addIndexColumn()
            ->addColumn('nama', function ($row) {
                return '<a href="' . route('kredit.edit',  enkrip($row->id)) . '">' . $row->langganan->nama . '</a>';
            })
            ->addColumn('zona', function ($row) {
                return $row->langganan->zona;
            })
            ->editColumn('total', function ($row) {
                return number_format($row->total);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s');
            })
            ->rawColumns(['nama']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Kredit $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kredit-table')
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
            Column::make('nama'),
            Column::make('zona'),
            Column::make('total')->addClass('text-end'),
            Column::make('created_at')->title('Tanggal Dibuat')->addClass('text-center'),
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
        return 'Kredit_' . date('YmdHis');
    }
}
