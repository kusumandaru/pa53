<?php

namespace App\DataTables;

use Auth;
use App\Models\Leave;
use Form;
use Yajra\Datatables\Services\DataTable;

class LeaveDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
            ->addColumn('action', 'leaves.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = Auth::user();


        $leaves = $user->role == 7 ?
            Leave::with(['approvals','users','statuses'])->get() :
            Leave::with(['approvals','users','statuses'])->where('user_id', $user->id)->get();

        return $this->applyScopes($leaves);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => true,
                'buttons' => [
                    'print',

                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',

                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'user_id' => ['name' => 'users.name', 'data' => 'users.name'],
            'start_date' => ['name' => 'start_date', 'data' => 'start_date'],
            'end_date' => ['name' => 'end_date', 'data' => 'end_date'],
            'note' => ['name' => 'note', 'data' => 'note'],
            'approval_id' => ['name' => 'approvals.name', 'data' => 'approvals.name'],
            'status' => ['name' => 'statuses.name', 'data' => 'statuses.name']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'leaves';
    }
}
