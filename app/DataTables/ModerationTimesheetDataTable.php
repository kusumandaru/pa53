<?php

namespace App\DataTables;

use Auth;
use App\Models\Timesheet;
use App\Models\ApprovalHistory;
use Form;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use DB;

class ModerationTimesheetDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
            $request = $_REQUEST;
            return $this->datatables
            ->collection($this->query())
            ->filter(function ($instance) use ($request) {
                if (array_key_exists('year', $request)){
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains($row['approvalstatus'], $request['approvalstatus']) ? true : false;
                    });
                }
            })
            ->addColumn('action', 'timesheets.moderation_datatables_actions')
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
        
        $request = $_REQUEST;
        if(empty($request['approvalStatus']))
        {
            $approvalStatus = 0;
        } else 
        {
            $approvalStatus = $request['approvalStatus'];
        }

        if(empty($request['search']['value']))
        {
            $name = '';
        } else
        {
            $name = $request['search']['value'];
        }
        
        $timesheets = Timesheet::getapprovalmoderation($user, $approvalStatus, $name);

        return $this->applyScopes($timesheets);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $html = $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'bFilter' => true,
                'dom' => 'Bfrtip',
                'scrollX' => true,
                'order'   => [[1, 'asc'],[2, 'asc']],
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
                ],

            ])         
            ;

            
            return $html;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'nama' => ['name' => 'name', 'data' => 'name'],
            'week' => ['name' => 'week', 'data' => 'week'],
            'month' => ['name' => 'month', 'data' => 'month'],
            'year' => ['name' => 'year', 'data' => 'year'],
            'jumlah_pengajuan_pa' => ['name' => 'count', 'data' => 'count'],
            'jumlah_pengajuan_tunjangan' => ['name' => 'insentif', 'data' => 'insentif']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Timesheets';
    }
}
