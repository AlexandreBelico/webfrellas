<?php

namespace App\Http\Controllers\Job;

use Auth;
use DB;
use Input;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\JobTrait;
use App\Job;
use App\Helpers\DataArrayHelper;
use App\JobApply;
use App\Milestones;
use App\User;
use Illuminate\Support\Facades\Mail;


class JobPublishController extends Controller
{

    use JobTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('company');
    }

    // Below function is added by hetal (38-42)
    public function verifywork(Request $request) {
        $milestoneId = $request->milestoneId;
        $milestone_details = Milestones::where('id', $milestoneId)->get();
        echo json_encode($milestone_details);
    }

    // Below function is added by hetal for display create milestones function(45-54)
    public function create_milestones($id) {
        $job = Job::findOrFail($id);
        $currencies = DataArrayHelper::currenciesArray();
        $job_applications = JobApply::where('job_id', $id)->get();
    
        return view('job.create_milestones')
                ->with('job', $job)
                ->with('currencies', array_unique($currencies))
                ->with('job_applications', $job_applications);
    }

    // Below function is added by hetal to mark as completed milestone(57-83)
    public function completemilestone(Request $request) {
        $milestoneId = $request['completemilestoneId'];
        if(Milestones::where('id', $milestoneId)->update(array("status"=>3))) {

            $milestoneDetails = Milestones::where('id', $milestoneId)->get();
            $candidateDetails = User::where('id', $milestoneDetails[0]['candidate_id'])->get();
            $jobDetail = Job::where('id', $milestoneDetails[0]['job_id'])->get();
            
            $userName = Auth::guard('company')->user()->name; 
            $email = $candidateDetails[0]->email;
            $name = $candidateDetails[0]->name;
            $body = "Milestone";
            $data = array('user'=>$userName, "name"=>$name ,"jobname"=>$jobDetail[0]['title']);

            $status=  Mail::send('emails.milestone_change_status',$data, function($message) use ($name, $email,$body) {
            $message->to($email, 'Webfreelas')->subject('Milestone status changed')->setBody($body);
            $message->from('support@jobsportal.com','JobsPortal');
            });

            flash(__('Milestone marked as completed successfully'))->success();
        } else {
           flash(__('Something wrong... Please try again later'))->error();
        }

        $milestoneDetails = Milestones::where('id', $milestoneId)->get();    
        return \Redirect::route('milestones.list', $milestoneDetails[0]['job_id']);
    }

    // Below function is added by hetal to list out the milestone of a job(86-96)
    public function list_milestones($job_id) {
        $job = Job::findOrFail($job_id);
        $company_id = Auth::guard('company')->user()->id;
        $milestones =  Milestones::where('company_id', $company_id)
                                ->where('job_id', $job_id)
                                ->get();

        return view('job.milestones_list')
                ->with('milestones', $milestones)
                ->with('job', $job);
    }
    
    // Below function is added by hetal to add a new Milestone (99-136)
    public function postMilestone(Request $request) {

        $request->validate([
            'task_details' => 'required',
            'price' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'freelancer' => 'required',
            'milestone_title' => 'required',
        ]);

        $data=$request->all(); 

        return redirect('milestone-payment-add')->with('post_data',$data);

       // return view('job.inc.milestone_payment_add')->with('post_data',$data);


       // return \Redirect::route('milestone.payment.add')->with('request_data',$request->all());


        // $title = $request['milestone_title'];
        // $taskDetails = $request['task_details'];
        // $price = $request['price'];
        // $startDate =  date("Y-m-d", strtotime($request['start_date']));
        // $endDate = date("Y-m-d", strtotime($request['end_date']));
        // $freelancer = $request['freelancer'];
        // $job_id = $request['job_id'];
        // $company_id = Auth::guard('company')->user()->id;

        // $milestones = new Milestones();

        // $milestones->candidate_id = $freelancer;
        // $milestones->milestone_title = $title;
        // $milestones->description = $taskDetails; 
        // $milestones->price = $price;
        // $milestones->start_date = $startDate;
        // $milestones->end_date = $endDate;
        // $milestones->job_id = $job_id;
        // $milestones->company_id = $company_id;

        //Start Add Payment Details :-




        //End Add Payment Details :-

        // if($milestones->save()){
        //    flash(__('You have successfully added milestone'))->success();
        // } else {
        //    flash(__('Something wrong!!! Please try again later'))->error();
        // }

        // return \Redirect::route('milestones.list', $job_id);
    }



}
