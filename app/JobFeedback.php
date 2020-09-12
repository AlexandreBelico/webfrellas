<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;


class JobFeedback extends Model
{

    protected $table = 'jobfeedback';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'user_id', 'job_id', 'company_id','feedback','rating'
    ];

    public function jobDetails(){
        return $this->hasOne(Job::class,'id','job_id');
    }

    public function jobApply(){
        return $this->hasOne(JobApply::class,'job_id','job_id');
    }
}
