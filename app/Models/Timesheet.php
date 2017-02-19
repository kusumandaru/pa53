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

    public static function getapprovalmoderation($approval, $approvalStatus)
    {
        /**
        $result = Timesheet::getwaitingname($approval, $approvalStatus);
        foreach ($result as $r) {
            $r->count = Timesheet::getapprovalcount($r->user_id, $approval, $approvalStatus);
            $total = Timesheet::gettotaltunjangan($r->user_id, $approval, $approvalStatus);
            $r->insentif = "Rp ". number_format($total, 0 , ',' , '.' );
        }
         * **/

        $result = Timesheet::getwaitingtimesheet($approval, $approvalStatus);
        foreach ($result as $r) {
            $r->count = Timesheet::getapprovalcounttimesheet($r->user_id, $r->id, $approval, $approvalStatus);
            $total = Timesheet::gettotaltunjangantimesheet($r->user_id, $r->id, $approval, $approvalStatus);
            $r->insentif = "Rp ". number_format($total, 0 , ',' , '.' );
        }

        return $result;
    }

    public static function getwaitingtimesheet($approval, $approvalStatus)
    {
        $result = DB::table('timesheets')
            ->select('timesheets.id','timesheets.week', 'timesheets.month', 'timesheets.year', 'approval_histories.user_id', 'users.name', 'approval_histories.approval_id', 'approval_histories.approval_status')
            ->join('timesheet_details', 'timesheet_details.timesheet_id', 'timesheets.id')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', $approvalStatus)
            ->where('transaction_type', '=', 2)
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

            } else if ($m->lokasi === "DOMESTIK L. JAWA") {
                if (!empty ($arr)) {
                    if ($arr['luar_jawa'] != null) {
                        foreach ($arr['luar_jawa'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "DOMESTIK P. JAWA") {
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

            } else if ($m->lokasi === "DOMESTIK L. JAWA") {
                if (!empty ($arr)) {
                    if ($arr['luar_jawa'] != null) {
                        foreach ($arr['luar_jawa'] as $key => $value) {
                            $insentif += $value * $m->total;
                        }
                    }
                }
            } else if ($m->lokasi === "DOMESTIK P. JAWA") {
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

    public static function getreport($approvalStatus)
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
            ->where('transaction_type', '=', 2)
            ->where('effort.category', '=', 'EffortType')
            ->where('moderation.category', '=', 'Moderation')
            ->groupBy('timesheets.user_id', 'timesheets.id')
            ->get(['*',
                DB::raw('users.name as user_name'),
                DB::raw('positions.name as position_name'),
                DB::raw('effort.name as effort_name'),
                DB::raw('case when (projects.claimable = 1) THEN \'Billable\' ELSE \'Non-Billable\' END as is_billable'),
                DB::raw('moderation.name as moderation_name')
            ]);

        return $result;
    }
}
