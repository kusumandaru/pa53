<?php

namespace App\Services;

use App\Models\Leave;
use Auth;
use Carbon\Carbon;

class DateService
{
    public $week;
    public $month;
    public $year;

    public function __construct()
    {
        $today = Carbon::today();

        $this->week = $today->weekOfMonth;
        $this->month = $today->month;
        $this->year = $today->year;

    }
}