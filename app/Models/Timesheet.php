<?php

namespace App\Models;

use DB;
use Eloquent as Model;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Timesheet",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="periode",
 *          description="periode",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="approval_id",
 *          description="approval_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="approval_status",
 *          description="approval_status",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="moderated_at",
 *          description="moderated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Timesheet extends Model
{
    use SoftDeletes;

    use Auditable;

    use Moderatable;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'timesheets';
    public $fillable = [
        'id',
        'month',
        'week',
        'year',
        'user_id',
        'periode',
        'approval_id',
        'approval_status',
        'moderated_at'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'approval_id' => 'integer',
        'approval_status' => 'integer'
    ];
    protected $appends = ['total', 'monthname', 'status', 'link', 'approval','submitted'];

    public static function getapprovalmoderation($approval, $approvalStatus, $name)
    {
        $result = Timesheet::getwaitingtimesheet($approval, $approvalStatus, $name);
        foreach ($result as $r) {
            $r->count = Timesheet::getapprovalcounttimesheet($r->user_id, $r->id, $approval, $approvalStatus);
            $total = Timesheet::gettotaltunjangantimesheet($r->user_id, $r->id, $approval, $approvalStatus);
            $r->insentif = "Rp ". number_format($total, 0 , ',' , '.' );
        }

        return $result;
    }

    public static function getwaitingtimesheet($approval, $approvalStatus, $name)
    {
        $result = DB::table('timesheets')
            ->select('timesheets.id','timesheets.week', 'timesheets.month', 'timesheets.year', 'approval_histories.user_id', 'users.name', 'approval_histories.approval_id', 'approval_histories.approval_status')
            ->join('timesheet_details', 'timesheet_details.timesheet_id', 'timesheets.id')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->where('transaction_type', '=', 2)
            ->where('users.name', 'like', '%'.$name.'%')
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->groupBy('timesheets.user_id', 'timesheets.id')
            ->get();

        return $result;
    }

    public static function getwaitingname($approval, $approvalStatus)
    {
        $result = DB::table('timesheet_details')
            ->select('approval_histories.user_id', 'users.name', 'approval_histories.approval_id', 'approval_histories.approval_status')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->groupBy('user_id')
            ->get();

        return $result;
    }

    public static function getapprovalcounttimesheet($userId, $timesheetId, $approval, $approvalStatus)
    {
        $result = DB::select(DB::raw("
            select count(*) AS count_approval
                FROM approval_histories ah1
                JOIN timesheet_details ON timesheet_details.id = ah1.transaction_id
                WHERE ah1.transaction_type = 2 
                AND timesheet_details.timesheet_id = :timesheetId
                AND ah1.approval_status = :approvalStatus
                AND (ah1.approval_id = :approvalId or ah1.group_approval_id = :roleId)
                AND ah1.user_id = :userId
            "), array(
                'roleId' => $approval->role,
                'approvalId' => $approval->id,
                'userId' => $userId,
                'approvalStatus' => $approvalStatus,
                'timesheetId' => $timesheetId
            )
        );
        if (!isset($result)) {
            return 0;
        } else {
            return $result[0]->count_approval;
        }
    }

    public static function getapprovalcount($userId, $approval, $approvalStatus)
    {
        $result = DB::select(DB::raw("
            select count(*) AS count_approval
                FROM approval_histories ah1
                JOIN timesheet_details ON timesheet_details.id = ah1.transaction_id
                WHERE ah1.transaction_type = 2 
                AND ah1.approval_status = :approvalStatus
                AND (ah1.approval_id = :approvalId or ah1.group_approval_id = :roleId)
                AND ah1.user_id = :userId
            "), array(
                'roleId' => $approval->role,
                'approvalId' => $approval->id,
                'userId' => $userId,
                'approvalStatus' => $approvalStatus
            )
        );
        if (!isset($result)) {
            return 0;
        } else {
            return $result[0]->count_approval;
        }
    }

    public static function gettotaltunjangantimesheet($userId, $timesheetId, $approval, $approvalStatus)
    {
        return
            Timesheet::getTotalMandaysTimesheet($userId, $timesheetId, $approval, $approvalStatus) +
            Timesheet::getTotalInsentifTimesheet($userId, $timesheetId, $approval, $approvalStatus) +
            Timesheet::getTotalTransportTimesheet($userId, $timesheetId, $approval, $approvalStatus);
    }

    public static function gettotaltunjangan($userId, $approval, $approvalStatus)
    {
        return
            Timesheet::getTotalMandays($userId, $approval, $approvalStatus) +
            Timesheet::getTotalInsentif($userId, $approval, $approvalStatus) +
            Timesheet::getTotalTransport($userId, $approval, $approvalStatus);
    }

    public static function getTotalMandaysTimesheet($userId, $timesheetId, $approval, $approvalStatus)
    {
        $insentif = 0;

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` 
        JOIN timesheets ON timesheets.id = timesheet_details.timesheet_id
        JOIN approval_histories ON approval_histories.transaction_id = timesheet_details.id
        where approval_histories.user_id = " . $userId . " 
        and timesheet_details.timesheet_id = ". $timesheetId ."
        and (approval_histories.approval_id = " . $approval['id'] . " or approval_histories.group_approval_id = " . $approval['role'] . ")
        and approval_histories.approval_status = " . $approvalStatus . " 
        and approval_histories.transaction_type = 2  
        and selected = 1 group by lokasi"));

        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional 
                      FROM tunjangan_positions,tunjangans,positions,users 
                      WHERE tunjangans.name != "Bantuan Perumahan"
                      and tunjangan_positions.tunjangan_id = tunjangans.id 
                      and tunjangan_positions.position_id = positions.id 
                      and users.position = positions.id and users.id = ' . $userId));

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        foreach ($mandays as $m)
        {
            if ($m->lokasi === "JABODETABEK") {
                if (!empty ($arr)) {
                    if ($arr['lokal'] != null) {
                        foreach ($arr['lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }

            } else if ($m->lokasi === "LUARJAWA") {
                if (!empty ($arr)) {
                    if ($arr['luar_jawa'] != null) {
                        foreach ($arr['luar_jawa'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "JAWA") {
                if (!empty ($arr)) {
                    if ($arr['non_lokal'] != null) {
                        foreach ($arr['non_lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "INTERNATIONAL") {
                if (!empty ($arr)) {
                    if ($arr['internasional'] != null) {
                        foreach ($arr['internasional'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            }
        }

        return $insentif;
    }

    public static function getTotalMandays($userId, $approval, $approvalStatus)
    {
        $insentif = 0;

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` 
        JOIN timesheets ON timesheets.id = timesheet_details.timesheet_id
        JOIN approval_histories ON approval_histories.transaction_id = timesheet_details.id
        where approval_histories.user_id = " . $userId . " 
        and (approval_histories.approval_id = " . $approval['id'] . " or approval_histories.group_approval_id = " . $approval['role'] . ")
        and approval_histories.approval_status = " . $approvalStatus . " 
        and approval_histories.transaction_type = 2  
        and selected = 1 group by lokasi"));

        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional 
                      FROM tunjangan_positions,tunjangans,positions,users 
                      WHERE tunjangans.name != "Bantuan Perumahan"
                      and tunjangan_positions.tunjangan_id = tunjangans.id 
                      and tunjangan_positions.position_id = positions.id 
                      and users.position = positions.id and users.id = ' . $userId));

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }

        foreach ($mandays as $m)
        {
            if ($m->lokasi === "JABODETABEK") {
                if (!empty ($arr)) {
                    if ($arr['lokal'] != null) {
                        foreach ($arr['lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }

            } else if ($m->lokasi === "LUARJAWA") {
                if (!empty ($arr)) {
                    if ($arr['luar_jawa'] != null) {
                        foreach ($arr['luar_jawa'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "JAWA") {
                if (!empty ($arr)) {
                    if ($arr['non_lokal'] != null) {
                        foreach ($arr['non_lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "INTERNATIONAL") {
                if (!empty ($arr)) {
                    if ($arr['internasional'] != null) {
                        foreach ($arr['internasional'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            }
        }

        return $insentif;
    }

    public static function getTotalInsentifTimesheet($userId, $timesheetId, $approval, $approvalStatus)
    {
        $insentif = DB::table('timesheet_insentif')
            ->join('approval_histories', 'approval_histories.guid', 'timesheet_insentif.guid')
            ->where('approval_histories.user_id', '=', $userId)
            ->where('timesheet_insentif.timesheet_id', '=', $timesheetId)
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->whereIn('transaction_type', [4])//bantuan perumahan
            ->pluck('timesheet_insentif.value')->sum();

        return $insentif;
    }

    public static function getTotalInsentif($userId, $approval, $approvalStatus)
    {
        $insentif = DB::table('timesheet_insentif')
            ->join('approval_histories', 'approval_histories.guid', 'timesheet_insentif.guid')
            ->where('approval_histories.user_id', '=', $userId)
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->whereIn('transaction_type', [4])//bantuan perumahan
            ->pluck('timesheet_insentif.value')->sum();

        return $insentif;
    }

    public static function getTotalTransportTimesheet($userId, $timesheetId, $approval, $approvalStatus)
    {
        $transport = DB::table('timesheet_transport')
            ->join('approval_histories', 'approval_histories.guid', 'timesheet_transport.guid')
            ->where('approval_histories.user_id', '=', $userId)
            ->where('timesheet_transport.timesheet_id', '=', $timesheetId)
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->where('transaction_type', '=', 3)//adcost
            ->pluck('timesheet_transport.value')->sum();

        return $transport;
    }

    public static function getTotalTransport($userId, $approval, $approvalStatus)
    {
        $transport = DB::table('timesheet_transport')
            ->join('approval_histories', 'approval_histories.guid', 'timesheet_transport.guid')
            ->where('approval_histories.user_id', '=', $userId)
            ->where(function ($query) use ($approval) {
                $query->where('approval_histories.approval_id', '=', $approval->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $approval->role);
            })
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->where('transaction_type', '=', 3)//adcost
            ->pluck('timesheet_transport.value')->sum();

        return $transport;
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function approvalHistory()
    {
        return $this->belongsTo('App\Models\ApprovalHistory');
    }

    public function getSubmittedAttribute()
    {
        return count(DB::select(DB::raw('SELECT * FROM `timesheet_details` WHERE timesheet_id=' . $this->id . ' and selected=1')));
    }

    public function getTotalAttribute()
    {
        return DB::table('timesheet_details')->where('timesheet_id', '=', $this->id)->count();
    }

    public function getMonthnameAttribute()
    {
        return date("F", mktime(0, 0, 0, $this->month, 10));
    }

    public function getStatusAttribute()
    {
        return $this->action;
    }

    public function getLinkAttribute()
    {
        return '<a href="timesheet/show/' . $this->id . '" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i>';
    }

    public function getApprovalAttribute()
    {
              $array = DB::select(DB::raw('select approval_histories.approval_status, count(approval_histories.approval_status)total from approval_histories,timesheet_details,timesheets WHERE transaction_type=2 and timesheet_details.timesheet_id = timesheets.id and approval_histories.transaction_id = timesheet_details.id and timesheets.id = '.$this->id.' group by approval_histories.approval_status'));
        //return response()->json($array);
        $appr = array();
        foreach($array as $a){
            //array_push($appr,array($a->approval_status=>$a->total));
            if($a->approval_status == 0){
                $status = 'pending';
            } else if($a->approval_status == 1){
                $status = 'approved';
            } else if($a->approval_status == 2){
                $status = 'rejected';
            } else if($a->approval_status == 3){
                $status = 'postponed';
            } else if($a->approval_status == 4){
                $status = 'paid';
            } else if($a->approval_status == 5){
                $status = 'on hold';
            } else if($a->approval_status == 6){
                $status = 'over budget';
            }
            $appr[$status]=$a->total;
        }
        $color = 'orange';
        if(isset($appr['rejected']) && $appr['rejected'] > 0){
            $color = '#dd4b39';
        };
        $statuses = '<i class="fa fa-fw fa-circle" title="" style="color:'.$color.'"></i>';
        return $statuses;
    }

    public function timesheetdetails()
    {
        return $this->hasMany('App\Models\TimesheetDetail');
    }

    public function timesheetinsentifs()
    {
        return $this->hasMany('App\Models\TimesheetInsentif');
    }

    public function timesheettransports()
    {
        return $this->hasMany('App\Models\TimesheetTransport');
    }

    public static function getreport($approvalStatus, $allProject, $project, $month, $year)
    {
        $result = TimesheetDetail::
            join('timesheets', 'timesheet_details.timesheet_id', 'timesheets.id')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->join('projects', 'projects.id', 'timesheet_details.project_id')
            ->join('constants as effort', 'effort.value', 'projects.effort_type')
            ->join('constants as moderation', 'moderation.value', 'approval_histories.approval_status')
            ->join('positions', 'positions.id', 'users.position')
            ->whereIn('approval_histories.approval_status', $approvalStatus)
            ->where('approval_histories.sequence_id', '=', 2)
            ->where('transaction_type', '=', 2)
            ->where('effort.category', '=', 'EffortType')
            ->where('moderation.category', '=', 'Moderation')
            ->where('timesheets.month', '=', $month)
            ->where('timesheets.year', '=', $year)
            ->where(function ($query) use ($allProject, $project) {
                $query->whereRaw('1 = '.$allProject)
                    ->orWhere('projects.id', '=', $project);
            })
            ->get(['*',
                DB::raw('users.name as user_name'),
                DB::raw('positions.name as position_name'),
                DB::raw('effort.name as effort_name'),
                DB::raw('case when (projects.claimable = 1) THEN \'Billable\' ELSE \'Non-Billable\' END as is_billable'),
                DB::raw('moderation.name as moderation_name')
            ]);

        return $result;
    }



    public static function getFinanceSummary($project_id,$periode)
    {
       $array = DB::select(DB::raw("select * from ( SELECT count(week)w,GROUP_CONCAT(timesheets.id) ts_id,periode,month, year,CONCAT(periode,'-',month,'-', year) as pr,week , user_id FROM timesheets where user_id in (select user_id from project_members WHERE project_id = ".$project_id.") and periode=".$periode." group by user_id,pr ) as tbl order by pr"));
       $data = array();
        foreach ($array as $a ){
            $project = DB::table('projects')->where('id', $project_id)->first();
            $user = User::where('id','=',$a->user_id)->first();
            $position = Position::where('id','=',$user->position)->first()->name;;
            $total =  Timesheet::getTotalTimesheetValueByProjectsAndPeriod($a->ts_id,$project_id,$a->user_id);
            if($total > 0){
                $data[] = array(
                                'period' => Timesheet::getPeriod($a->periode,$a->month,$a->year),
                                'project_name'=>$project->project_name,
                                'iwo_wbs_code'=>$project->code,
                                'nama_konsultan'=>$user->name,
                                'position'=>$position,
                                'nama_bank'=>$user->bank,
                                'cabang_bank'=>$user->cabang,
                                'nama_rekening'=>$user->nama_rekening,
                                'no_rekening'=>$user->rekening,
                                //'rate_consultant'=>'',
                                'jumlah_hari'=> Timesheet::getTotalTimesheetDays($a->ts_id,$project_id),
                                'total_claim_transportz' => Timesheet::getTotalTransportByProject($a->ts_id,$project_id),
                               // 'status'=> 'not validated',
                                'total' => $total
                
                );
           }
        }

        return $data;
    }

    public static function isPeriodValid($period)
    {
       $array = DB::select(DB::raw("select * from (SELECT count(week)w,GROUP_CONCAT(timesheets.id) ts_id,CONCAT(periode,'-',month,'-', year) as pr,week , user_id FROM timesheets group by user_id,pr) as tbl"));
       $data = array();
        foreach ($array as $a ){
            if($a->w == 2){
                $data[] = array('timesheet_id'=>$a->ts_id);
            }
        }

        return $data;
    }

    public static function getTotalTransportByProject($timesheet_list,$project_id)
    {
       // echo $timesheet_list;
        $list = explode(',', $timesheet_list);
        $transport = DB::table('timesheet_transport')
            ->join('timesheets', 'timesheet_transport.timesheet_id', 'timesheets.id')
            ->where('timesheet_transport.project_id', '=', $project_id)//adcost
            ->whereIn('timesheets.id', $list)
            ->pluck('timesheet_transport.value')->sum();

        return $transport;
    }

    public static function getTotalPerumahanByProject($timesheet_list,$project_id)
    {
       // echo $timesheet_list;
        $list = explode(',', $timesheet_list);
        $transport = DB::table('timesheet_insentif')
            ->join('timesheets', 'timesheet_insentif.timesheet_id', 'timesheets.id')
            ->where('timesheet_insentif.project_id', '=', $project_id)//adcost
            ->whereIn('timesheets.id', $list)
            ->pluck('timesheet_insentif.value')->sum();

        return $transport;
    }

     public static function getTotalTimesheetDays($timesheet_list,$project_id)
    {
        $insentif = 0;

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` 
        JOIN timesheets ON timesheets.id = timesheet_details.timesheet_id
        where timesheets.id in (".$timesheet_list.")
        and timesheet_details.project_id = ".$project_id."
        and selected = 1 group by lokasi"));
        return $mandays[0]->total;
    }

     public static function getTotalMandaysByProject($timesheet_list,$project_id,$userId)
    {
        $insentif = 0;

        $mandays = DB::select(DB::raw("SELECT lokasi , count(*)total FROM `timesheet_details` 
        JOIN timesheets ON timesheets.id = timesheet_details.timesheet_id
        where timesheets.id in (".$timesheet_list.")
        and timesheet_details.project_id = ".$project_id."
        and selected = 1 group by lokasi"));

     //   print_r ($mandays).'<br>';echo '<br>';

        $tunjangans = DB::select(DB::raw('SELECT positions.name,tunjangans.name,lokal,non_lokal,luar_jawa,internasional 
                      FROM tunjangan_positions,tunjangans,positions,users 
                      WHERE tunjangans.name != "Bantuan Perumahan"
                      and tunjangan_positions.tunjangan_id = tunjangans.id 
                      and tunjangan_positions.position_id = positions.id 
                      and users.position = positions.id and users.id = ' . $userId));
       // print_r ($tunjangans);echo '<br>';

        foreach ($tunjangans as $t) {
            $arr['lokal'][$t->name] = $t->lokal;
            $arr['non_lokal'][$t->name] = $t->non_lokal;
            $arr['luar_jawa'][$t->name] = $t->luar_jawa;
            $arr['internasional'][$t->name] = $t->internasional;
        }
      //  print_r ($arr);echo '<br>';

        foreach ($mandays as $m)
        {
            if ($m->lokasi === "JABODETABEK") {
                if (!empty ($arr)) {
                    if ($arr['lokal'] != null) {
                        foreach ($arr['lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                          //  echo $insentif.'JABODETABEK<br>';
                        }
                    }
                }

            } else if ($m->lokasi === "LUARJAWA") {
                if (!empty ($arr)) {
                    if ($arr['luar_jawa'] != null) {
                        foreach ($arr['luar_jawa'] as $key => $value) {
                            $insentif += $value * $m->total;
                           //  echo $insentif.'LUARJAWA<br>';
                        }
                    }
                }
            } else if ($m->lokasi === "JAWA") {
                if (!empty ($arr)) {
                    if ($arr['non_lokal'] != null) {
                        foreach ($arr['non_lokal'] as $key => $value) {
                            $insentif += $value * $m->total;
                  //           echo $insentif.'DOMESTIK P. JAWA<br>';
                        }
                    }
                }
            } else if ($m->lokasi === "INTERNATIONAL") {
                if (!empty ($arr)) {
                    if ($arr['internasional'] != null) {
                        foreach ($arr['internasional'] as $key => $value) {
                            $insentif += $value * $m->total;
                    //         echo $insentif.'INTERNATIONAL<br>';
                        }
                    }
                }
            }
        }

        return $insentif;
    }


public static function getTotalTimesheetValueByProjectsAndPeriod($timesheet_list,$project_id,$userId)
    {
        $insentif = 0;
        $insentif = Timesheet::getTotalTransportByProject($timesheet_list,$project_id)+Timesheet::getTotalPerumahanByProject($timesheet_list,$project_id)+Timesheet::getTotalMandaysByProject($timesheet_list,$project_id,$userId);
        return $insentif;
    }

public static function getPeriod($period,$month,$year)
    {
    //$datetocheck = "2012-03-01";
    $datetocheck = $year.'-'.$month.'-01';
    $lastday = date('t',strtotime($datetocheck));
    if($period==1){
        $range = "1/".$month.'/'.$year.' - '."15/".$month.'/'.$year;
    } else {
        $range = "16/".$month.'/'.$year.' - '.$lastday."/".$month.'/'.$year;
    }
        return $range;
    }




}
