<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Project;
use Mail;
use App\Mail\ProjectMemberAssign;

class SendProjectMemberAssignEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var  Repository */
    private $project;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $project, int $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user);
        $project = Project::find($this->project);

        $mail = Mail::to($user['email'])
            ->queue(new ProjectMemberAssign($user, $project));

    }
}
