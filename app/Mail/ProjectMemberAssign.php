<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Project;

class ProjectMemberAssign extends Mailable
{
    /**
     * The request instance.
     *
     * @var User
     */
    public $user;
    public $project;
    public $url;
    public $trouble_email;
    public $logo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Project $project)
    {
        $this->user = $user;
        $this->project = $project;
        $this->url = url()->to('/');
        $this->trouble_email = "project.control@sigma.co.id";
        $this->logo = public_path()."/image/metrasys.png";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('email.project.assignmember');
    }
}
