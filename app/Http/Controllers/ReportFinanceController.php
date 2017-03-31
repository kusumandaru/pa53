<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Flash;
use Input;
use Response;
use Auth;
use App\Models\Project;
use App\Models\Timesheet;
use Carbon\Carbon;
use Request as RequestFacade;
use Yajra\Datatables\Facades\Datatables;


class ReportFinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $request = RequestFacade::all();
        $columns = ["project_name","period","iwo_wbs_code","nama_konsultan","nama_bank","cabang_bank","nama_rekening","no_rekening","total"];
        if (RequestFacade::ajax()) {
            if(isset($request['type']) && isset($request['subtype'])){
              //  return '';
                $notes = Timesheet::getFinanceSummary($request['type'],$request['subtype']);
                return Datatables::of(collect($notes))->make(true);
            } else {
                return Datatables::of(collect(array()))->make(true);
            }
            
        }
        $html = Datatables::getHtmlBuilder()->columns($columns);
        $projects = array(0 => '') + Project::pluck('project_name', 'id')->all();
        return view('reports.finance',compact('projects','request','html'));
    }

    public function getProjectMemberJson($id)
    {
        $member = DB::table('project_members')
        ->join('users', 'users.id', 'project_members.user_id')
        ->where('project_id','=',$id)
        ->pluck('users.name','users.id');
        
         return response()->json($member);
    }

    public function ajax()
    {
        return response()->json(Timesheet::getFinanceSummary(1,1));
    }

    public function getExcel($project_id,$period) {
        $project = DB::table('projects')->where('id', $project_id)->first();
        $pm = DB::table('users')->where('id', $project->pm_user_id)->first();
        $data = Timesheet::getFinanceSummary($project_id,$period);
        Excel::create($project->project_name, function($excel) use($data,$project,$pm) {

        $excel->sheet('sheet', function($sheet) use($data,$pm) {
        $sheet->fromModel($data, null, 'A1', true);
        $sheet->appendRow(array('','','','','','','','Subtotal', collect($data)->sum('total')));
        $sheet->appendRow(array('PM','','','','','','','',''));
        $sheet->appendRow(array('','','','','','','','',''));
        $sheet->appendRow(array('','','','','','','','',''));
        $sheet->appendRow(array($pm->name,'','','','','','','',''));
    });
    })->export('csv');
       
    }
}
