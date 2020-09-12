<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class NotificationsModel extends Model
{

    protected $table = 'notifications_master';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at'];
}