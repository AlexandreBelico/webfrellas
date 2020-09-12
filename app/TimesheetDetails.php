<?php

namespace App;

use DB;
use App;
use Illuminate\Database\Eloquent\Model;

class TimesheetDetails extends Model
{
    protected $table = 'timesheet_details';
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];   
}
