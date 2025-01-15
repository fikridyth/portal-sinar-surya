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

class KunjunganDataTable extends DataTable
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
        ->addColumn('senin', function ($row) {
            $checked = $row->hari === 'SENIN' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('selasa', function ($row) {
            $checked = $row->hari === 'SELASA' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('rabu', function ($row) {
            $checked = $row->hari === 'RABU' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('kamis', function ($row) {
            $checked = $row->hari === 'KAMIS' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('jumat', function ($row) {
            $checked = $row->hari === 'JUMAT' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('sabtu', function ($row) {
            $checked = $row->hari === 'SABTU' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('minggu', function ($row) {
            $checked = $row->hari === 'MINGGU' ? 'checked' : '';
            return '<div style="text-align: center;"><input type="checkbox" ' . $checked . '></div>';
        })
        ->addColumn('action', function ($row) {
            $editUrl = route('master.materai.update', $row->id);
            $csrfToken = csrf_token();
            $methodPut = method_field('PUT');

            $button = '<button type="button" class="btn btn-sm btn-primary" onclick="handleUpdate(' . $row->id . ', \'' . $csrfToken . '\')">SAVE</button>';
            $button .= '<form id="update-form-' . $row->id . '" action="' . $editUrl . '" method="POST" style="display: none;">';
            $button .= '<input type="hidden" name="_token" value="' . $csrfToken . '">';
            $button .= $methodPut;
            $button .= '</form>';
            return $button;
        })
        ->rawColumns(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'action']);
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
            ->setTableId('materai-table')
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
                'pageLength' => 2000
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
            Column::make('DT_RowIndex')->title('No.')->searchable(false)->orderable(false)->hidden(),
            Column::make('nama'),
            Column::make('senin')->addClass('text-center'),
            Column::make('selasa')->addClass('text-center'),
            Column::make('rabu')->addClass('text-center'),
            Column::make('kamis')->addClass('text-center'),
            Column::make('jumat')->addClass('text-center'),
            Column::make('sabtu')->addClass('text-center'),
            Column::make('minggu')->addClass('text-center'),
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
