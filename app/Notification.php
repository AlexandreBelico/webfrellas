<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{

    protected $table = 'notification';

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'to_user_id', 'job_id', 'company_id', 'content', 'isRead'
    ];

    public function getJobDetails()
    {
        return $this->hasOne(Job::class,'id','job_id');
    }
}
