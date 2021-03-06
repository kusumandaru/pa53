<?php

namespace App\Models;

use Eloquent as Model;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use \Carbon\Carbon;

/**
 * @SWG\Definition(
 *      definition="Leave",
 *      required={"start_date", "end_date", "approval_id", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="start_date",
 *          description="start_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="end_date",
 *          description="end_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *     @SWG\Property(
 *          property="note",
 *          description="note",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
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
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="moderated_at",
 *          description="moderated_at",
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
 *      )
 * )
 */
class Leave extends Model
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
        'start_date' => 'required',
        'end_date' => 'required',
        'note' => 'required'
        /**,
         * 'user_id' => 'required',
         * 'approval_id' => 'required',
         * 'status' => 'required'
         **/
    ];
    public $table = 'leaves';
    public $fillable = [
        'start_date',
        'end_date',
        'note',
        'user_id',
        'approval_id',
        'type',
        'status',
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
        'status' => 'integer',
        'approval_status' => 'integer',
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function approvals()
    {
        return $this->hasOne('App\Models\User', 'id', 'approval_id');
    }

    public function approvalstat()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'approval_status')->where('category', '=', 'Moderation');
    }

    public function statuses()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'status')->where('category', '=', 'Status');
    }

    public function types()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'type')->where('category', '=', 'Cuti');
    }

    public function getStartDateAttribute($date)
    {
        $cDate = Carbon::parse($date)->toDateString();
        return $cDate;
    }

    public function getEndDateAttribute($date)
    {
        $cDate = Carbon::parse($date)->toDateString();
        return $cDate;
    }
}
