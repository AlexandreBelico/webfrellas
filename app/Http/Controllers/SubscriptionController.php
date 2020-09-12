<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use Input;
use Redirect;
use App\User;
use App\Subscription;
use Newsletter;
use App\Company;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getSubscription(Request $request)
    {
        $msgresponse = Array();
        $rules = array(
            'name' => 'required|max:100|between:4,70',
            'email' => 'required|email|max:100',
        );
        $rules_messages = array(
            'name.required' => __('Name is required'),
            'email.required' => __('E-mail address is required'),
        );
        $validation = Validator::make($request->all(), $rules, $rules_messages);
        if ($validation->fails()) {
            $msgresponse = $validation->messages()->toJson();
            echo $msgresponse;
            exit;
        } else {
            $user = User::where('email', 'like', $request->input('email'))->first();			
			if(null !== $user){
				$user->is_subscribed = 1;
				$user->update();
			}
			
			$company = Company::where('email', 'like', $request->input('email'))->first();
			if(null !== $company){
				$company->is_subscribed = 1;
				$company->update();
			}
			
			/*************************/
			Subscription::where('email', 'like', $request->input('email'))->delete();
			$subscription = new Subscription();
			$subscription->email = $request->input('email');
			$subscription->name = $request->input('name');
			$subscription->save();
			/*************************/
			Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
			/*************************/
			
			
			$msgresponse = ['success' => 'success', 'message' => __('Subscribed successfully')];
            echo json_encode($msgresponse);
            exit;
        }
    }
}
