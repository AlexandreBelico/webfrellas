<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'payment_details';

    protected $primaryKey = 'id';

    protected $fillable = ['employee_id','candidate_id','job_apply_id','job_id','sale_id','payment_status','transaction_details','invoice_number'];
}
