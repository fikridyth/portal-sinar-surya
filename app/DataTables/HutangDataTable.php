<?php

namespace App\DataTables;

use App\Models\Hutang;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HutangDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->select('id_supplier')->whereNull('nomor_bukti')->where('total', '!=', 0)->groupBy('id_supplier')))
            ->addIndexColumn()
            ->addColumn('supplier_name', function ($row) {
                $detailUrl = route('pembayaran-hutang.show', $row->id_supplier);
                return '<a href="' . $detailUrl . '" class="mx-2">' . $row->supplier->nama . '</a>';
            })
            ->addColumn('alamat1', function ($row) {
                return $row->supplier->alamat1;
            })
            ->addColumn('alamat2', function ($row) {
                return $row->supplier->alamat2;
            })
            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->whereHas('supplier', function($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['supplier_name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Hutang $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('hutang-table')
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
            // Column::make('DT_RowIndex')->title('NO.')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('supplier_name')->title('NAMA SUPPLIER'),
            Column::make('alamat1')->title('ALAMAT -1'),
            Column::make('alamat2')->title('ALAMAT -2'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Hutang_' . date('YmdHis');
    }
}
