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

class SendTimesheetFinanceReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-timesheet-to-finance';

    /** @var  Repository */
    private $timesheetRepository;
    private $userRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send timesheet report to Finance';

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

        /** @var query here $timesheet */
        $timesheet = TimesheetDetail::
        all();

        $data = array();
        $count = 0;
        foreach ($timesheet as $result) {

            $res = array(
                'id'=>$result->id,
                'week'=>$result->activity
        );
            $data[$count] = $res;
            $count++;
        }


        $user = User::where('id', $id)->first();

        $path = Excel::create('Timesheet-Finance', function($excel) use ($timesheet, $data) {

            // Set the title
            $excel->setTitle('Timesheet-Finance');
            // Chain the setters
            $excel->setCreator('PA-Online')
                ->setCompany('PT. Sigma Metrasys Solution');
            // Call them separately
            $excel->setDescription('Periodically Timesheet');

            //get data
            $excel->sheet('timesheet', function($sheet) use ($timesheet, $data) {
                $sheet->fromModel($data, null, 'A1', true);
                $sheet->appendRow(array(
                    'total', count($data)
                ));
            });

            // Append row as very last


        })->store('xls', false, true);

        // send mail
        //$mail = Mail::to($user['email'])
        //    ->cc('rudy.febrianto@metrasys.co.id')
        //    ->send(new TimesheetSubmission($user, $path['full']));

        $this->info('Executed');
        
    }

    private function createFromUser(User $user)
    {
        return array(
            'name' => $user->name
        );
    }
}
