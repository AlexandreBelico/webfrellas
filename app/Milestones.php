<?php

namespace App;

use DB;
use App;
use Illuminate\Database\Eloquent\Model;

class Milestones extends Model
{
    protected $table = 'milestones';
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];   
}
