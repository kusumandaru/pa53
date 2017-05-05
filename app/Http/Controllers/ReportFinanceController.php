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
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_Border;
use PHPExcel_Style_Color;
use PHPExcel_Style_Alignment;


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
        $this->download($project_id,$period,'xls');
    }

    public function getPdf($project_id,$period) {
        $this->download($project_id,$period,'pdf');
    }

    private function download($project_id,$period,$type) {
        $project = DB::table('projects')->where('id', $project_id)->first();
        $pm = DB::table('users')->where('id', $project->pm_user_id)->first();
        $pmo = 'Oky Gustiawan';
        $data = Timesheet::getFinanceSummary($project_id,$period);
        Excel::create($project->project_name, function($excel) use($data,$project,$pm,$pmo,$type) {

            $excel->sheet('sheet', function($sheet) use($data,$pm,$pmo,$type) {
                $sheet->setColumnFormat(array(
                    'I' => '###,###,###,##0.00'
                ));
                $sheet->fromModel($data, null, 'A1', true);
                $sheet->appendRow(array('','','','','','','','Subtotal', collect($data)->sum('total')));
                $sheet->appendRow(array('','','','','','','','',''));
                $sheet->appendRow(array('','','PM','PMO','','','','',''));
                $sheet->appendRow(array('','','Approved','Approved','','','','',''));
                $sheet->appendRow(array('','','','','','','','',''));
                $sheet->appendRow(array('','','','','','','','',''));
                $sheet->appendRow(array('','','','','','','','',''));
                $sheet->appendRow(array('','',date('d-m-Y H:i'),date('d-m-Y H:i'),'','','','',''));
                $sheet->appendRow(array('','',$pm->name,$pmo,'','','','',''));
                $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                //'style' => PHPExcel_Style_Border::BORDER_NONE,
                                'color' => array('rgb' => PHPExcel_Style_Color::COLOR_WHITE)
                            ),
                        )
                    );

$sheet->setBorder('A1:I'.(collect($data)->count()+2));
$style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getStyle('A'.(collect($data)->count()+4).':I'.(collect($data)->count()+10))->applyFromArray($style);


                if($type=='pdf'){

                    /**
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                //'style' => PHPExcel_Style_Border::BORDER_NONE,
                                'color' => array('rgb' => PHPExcel_Style_Color::COLOR_WHITE)
                            ),
                        )
                    );



                    $sheet->getStyle('A1:B5')->applyFromArray($styleArray);
                    $sheet->cells('A1:B5', function($cell) {
                        $cell->setBorder('none', 'none', 'none', 'none');
                        $cell->setBackground('#FFFFFF');

                    });

                    $sheet->setAllBorders('none');
                    **/

                    $sheet->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    $sheet->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B4);
                }
            });
            set_time_limit(0);
            ini_set('memory_limit', '1G');
            ob_end_clean();
        })->export($type);
    }
}
