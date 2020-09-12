<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userPaypalPaymentDetail extends Model
{
    protected $table = 'user_paypal_payment_details';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id','paypal_email_id','paypal_mobile_number'];
} 
