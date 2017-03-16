<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use App\Models\User;
use Mail;
use App\Mail\TimesheetSubmission;
use Excel;
use DB;
use Carbon\Carbon;
use App\Models\TimesheetDetail;

class SendTimesheetHRReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-timesheet-to-hr';

    /** @var  Repository */
    private $timesheetRepository;
    private $userRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send timesheet report to HRD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TimesheetRepository $timesheetRepo, UserRepository $userRepo)
    {
        parent::__construct();
        $this->timesheetRepository = $timesheetRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$arguments = $this->arguments();
        //$user = $this->argument('user');

        $id = 1;
        $approvalStatus = array(1,4); //approve and paid

        $timesheet = TimesheetDetail::
        join('timesheets', 'timesheet_details.timesheet_id', 'timesheets.id')->
        join('users', 'users.id', 'timesheets.user_id')->
        join('projects', 'projects.id', 'timesheet_details.project_id')->
        join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')->
        whereIn('approval_histories.approval_status', $approvalStatus)->
        where('approval_histories.sequence_id', '=', 2)->
        whereBetween('timesheet_details.created_at', [Carbon::today()->subDays(7)->toDateString(),Carbon::today()->toDateString()])->
        get(['users.nik','users.email', 'projects.project_id', 'projects.code', 'timesheet_details.activity',
             'timesheet_details.activity_detail','timesheet_details.start_time', 'timesheet_details.end_time', 
             'timesheet_details.date', 'projects.claimable'
            DB::raw('') 
            DB::raw('IF(projects.claimable == 1, \'Claimable\',\'Non Claimable\')') ,
            DB::raw('timesheet_details.created_at as created_timesheet')
        ]);

        $data = array();
        $count = 0;
        foreach ($timesheet as $result) {
            //$data[] = (array)$result;
            //$res = array();
            $res = array(
                'nik'=>$result->nik,
                'email'=>$result->email,
                'iwo'=>$result->project_id,
                'task_name'=>$result->activity_detail,
                'total_work'=>$result->hour,
                'ts_date'=>$result->date,
                'submit_date'=>$result->created_timesheet,
                'effort_type'=>$result->activity_detail,
                'task_type'=>$result->task_type
                
        );
            $data[$count] = $res;
            $count++;
        }


        $user = User::where('id', $id)->first();

        $path = Excel::create('Timesheet', function($excel) use ($timesheet, $data) {

            // Set the title
            $excel->setTitle('Timesheet');
            // Chain the setters
            $excel->setCreator('PA-Online')
                ->setCompany('PT. Sigma Metrasys Solution');
            // Call them separately
            $excel->setDescription('Weekly Timesheet');

            //get data
            $excel->sheet('timesheet', function($sheet) use ($timesheet, $data) {
                $sheet->fromModel($data, null, 'A1', true);
            });

        })->store('xls', false, true);

        // send mail
        $mail = Mail::to($user['email'])
            ->cc('oky.gustiawan@metrasys.co.id')
            ->send(new TimesheetSubmission($user, $path['full']));

        $this->info('Executed');
        
    }

    private function createFromUser(User $user)
    {
        return array(
            'name' => $user->name
        );
    }
}
