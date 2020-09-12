<?php

namespace App\Http\Controllers\Job;

use App\Notification;
use Auth;
use DB;
use Input;
use Redirect;
use Mail;
use App\Job;
use App\FavouriteApplicant;
use Carbon\Carbon;
use App\User;
use App\Company;
use App\Message;
use App\JobApply;
use App\CompanyMessage;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Mail\MessageSendMail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Pusher\Pusher;
use App\Events\MessageSent;
use Validator;
use File;

class SeekerSendController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fileupload_action(Request $request) {
    {
         $to = $request->file_received_id;
         $job_id = $request->file_job_id;
         $from = Auth::id(); 
         $validation = Validator::make($request->all(), [
          'inputfile' => 'required|mimes:jpeg,png,jpg,gif,pdf,zip,doc,docx,mpeg,mpga,mp3,wav,aac,mp4,flv,avi,3gp,txt'
         ]);
         if($validation->passes())
         {
              $image = $request->file('inputfile');
              $extension = $image->getClientOriginalExtension();
              $new_name = $image->getClientOriginalName().'-'.rand() . '.' . $extension;
              
              $images = ["jpeg","png","jpg","gif"];
              if(in_array($extension, $images)){
                    $message_type = 2;
              } else {
                    $message_type = 1;
              }

              $image->move(public_path('images/chats'), $new_name);

                $data = new Message();
                $data->from = $from;
                $data->to = $to;
                $data->job_id = $job_id;
                $data->message = $new_name;
                $data->is_read = 0;
                $data->message_type = $message_type;
                $data->original_name = $image->getClientOriginalName();
                $data->save();

                // Pusher
                $options = array(
                    'cluster'=>'ap2',
                    'encrypted'=> false,
                );

                $pusher = new Pusher(
                   env('PUSHER_APP_KEY'),
                   env('PUSHER_APP_SECRET'),
                   env('PUSHER_APP_ID'),
                   $options
                );

                $data = ["from"=>$from, "to"=>$to, "job_id"=>$job_id];

                $pusherevent = $pusher->trigger('my-channel', 'my-event', $data);
                // return json_encode($pusherevent);

              return response()->json([
               'message'   => 'File Uploaded Successfully',
               'uploaded_image' => '<img src="/images/'.$new_name.'" class="img-thumbnail" width="300" />',
               'class_name'  => 'alert-success',
               'pusher_event'=>$pusherevent
              ]);
         }
         else
         {
          return response()->json([
           'message'   => $validation->errors()->all(),
           'uploaded_image' => '',
           'class_name'  => 'alert-danger'
          ]);
         }
        }
    }

    public function deletefile_action(Request $request) {
        $messegeId = $request->deletemessageId;
        $selectFileDetails = Message::where('id', $messegeId)->get();
        $image_path = public_path("images/chats/".$selectFileDetails[0]->message);
    
        $to = $request->deletereceiver_id;
        $from = Auth::id();
        $deletedRow = Message::where('id', $messegeId)->delete();

        if($deletedRow){
            File::delete($image_path);
        }

        $options = array(
            'cluster'=>'ap2',
            'encrypted'=> false,
        );

        $pusher = new Pusher(
           env('PUSHER_APP_KEY'),
           env('PUSHER_APP_SECRET'),
           env('PUSHER_APP_ID'),
           $options
        );

        $data = ["from"=>$from, "to"=>$to];

        $pusherevent = $pusher->trigger('my-channel', 'my-event', $data);

    }

    public function all_messages()
    {
        $messages = CompanyMessage::where('seeker_id', Auth::user()->id)->get();
        $ids = array();
        foreach ($messages as $key => $value) {
            $ids[] = $value->company_id;
        }
        $data['companies'] = Company::whereIn('id', $ids)->get();

        return view('seeker.all-messages')->with($data);
    }
    public function append_messages(Request $request)
    {
        $seeker_id = Auth::user()->id;
        $company_id = $request->get('company_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::where('id', $seeker_id)->first();
        $company = Company::where('id', $company_id)->first();
		$messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        if ($messages) {
            foreach ($messages as $key => $value) {
                $message = CompanyMessage::findOrFail($value->id);
                $message->status = 'viewed';
                $message->update();
            }
        }

        $search = view("seeker.append-messages", compact('messages', 'seeker', 'company'))->render();
        return $search;
    }

    public function chats_page() {
        $messages = CompanyMessage::where('seeker_id', Auth::user()->id)->get();
        $ids = array();
        foreach ($messages as $key => $value) {
            $ids[] = $value->company_id;
        }
        $data['companies'] = Company::whereIn('id', $ids)->get();

        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $jobs = Job::whereIn('id', $myAppliedJobIds)->get();
        $userId = Auth::user()->id;

        $allcompanies = [];
        $hiredJobs = [];
        foreach ($jobs as $job) {
            $count = FavouriteApplicant::where('user_id', $userId)
                ->where('job_id', $job['id'])
                ->where('company_id', $job['company_id'])
                ->count();   
            $job['hiredStatus'] = $count;

            if($count > 0){
                array_push($hiredJobs, $job['id']);
                array_push($allcompanies, $job['company_id']);
            }
        }


        // $loggedIn = Message::select('from', 'to')->where('to', $userId)->groupBy('from', 'to')->get();
        // if(count($loggedIn)>0){
        //     foreach ($loggedIn as $fromId) {
        //         $from = $fromId['from'];
        //         if(!in_array($from, $allcompanies)){
        //             array_push($allcompanies, $from);
        //         }
        //     }
        // }

        $chatJobIds = DB::select("select job_id from messages where messages.to = $userId or messages.from = $userId group by job_id");
        $associatveChatJobs = [];
        foreach ($chatJobIds as $chatJob) {
            $jobId = $chatJob->job_id;
            array_push($associatveChatJobs, $jobId);
        }

        $count = count($associatveChatJobs);
        foreach ($hiredJobs as $hireJobs) {
            $value = $hireJobs;
            $exists = array_search($value, array_values($associatveChatJobs));
            if($exists=='' || $exists<=0){
                $count++;
                $associatveChatJobs[$count] = $value;
            }
        }

        $companyDetails = [];
        $jobCount= 0;
        
        foreach ($associatveChatJobs as $chatJob) {
            $jobId = $chatJob;
            $jobDetails = Job::where('id', $jobId)->get();
            if(!empty($jobDetails->toArray())){
                $hiredprojectData = [];
                $companyId = $jobDetails[0]->company_id;
                $singleCompany = Company::where('id', $companyId)->get();
                $count = Message::where('to', $userId)
                        ->where('job_id', $jobId)
                        ->where('from', $companyId)
                        ->count();   

                if($count > 0){
                        $unread = DB::select('select count(is_read) as unread from messages where messages.to =  '. Auth::id() . ' and messages.from = '. $companyId .' and job_id = ' . $jobId . ' and is_read = 0');
                        $unread = $unread[0]->unread;

                        $hiredprojectData[$jobCount]["job_id"] = $jobDetails[0]->id;
                        $hiredprojectData[$jobCount]['job_title'] = $jobDetails[0]->title;
                        $hiredprojectData[$jobCount]['unread'] = $unread; 
                        $jobCount++;
                    }
                    
                    if(count($singleCompany)>0){
                $unread = DB::select('select count(is_read) as unread from messages where messages.to =  '. Auth::id() . ' and messages.from = '. $companyId .' and is_read = 0');
                $unread = $unread[0]->unread; 

                $singleCompany[0]->unread = $unread;
                $singleCompany[0]->hiredprojectData = $hiredprojectData;
                array_push($companyDetails, $singleCompany);
                }
              }
            }
        

        // $companyDetails = [];
        // if(!empty($allcompanies)){
            
        //     $existingCompanyId = [];
        //     foreach ($allcompanies as $companyId) {
        //         if(!in_array($companyId, $existingCompanyId)){
        //         array_push($existingCompanyId, $companyId);
        //         $hiredprojectData = [];
        //         $singleCompany = Company::where('id', $companyId)->get();
                
        //         $jobCount = 0;
        //         foreach ($jobs as $job) {
        //         $count = Message::where('to', $userId)
        //                 ->where('job_id', $job['id'])
        //                 ->where('from', $companyId)
        //                 ->count();   

        //         if($count > 0){
        //                 $unread = DB::select('select count(is_read) as unread from messages where messages.to =  '. Auth::id() . ' and messages.from = '. $companyId .' and job_id = ' . $job['id'] . ' and is_read = 0');
        //                 $unread = $unread[0]->unread;

        //                 $hiredprojectData[$jobCount]["job_id"] = $job['id'];
        //                 $hiredprojectData[$jobCount]['job_title'] = $job['title'];
        //                 $hiredprojectData[$jobCount]['unread'] = $unread; 
        //                 $jobCount++;
        //             }
        //         }

        //         if(count($singleCompany)>0){
        //         $unread = DB::select('select count(is_read) as unread from messages where messages.to =  '. Auth::id() . ' and messages.from = '. $companyId .' and is_read = 0');
        //         $unread = $unread[0]->unread; 

        //         $singleCompany[0]->unread = $unread;
        //         $singleCompany[0]->hiredprojectData = $hiredprojectData;
        //         array_push($companyDetails, $singleCompany);
        //       }
        //     }
        //   }
        // }
        
        /*foreach ($companyDetails as $comp) {
            echo "<pre>";
            print_r($comp->toArray());
        }
        exit; */

        $data['companyDetails'] = $companyDetails;
        $answer = count($companyDetails);

        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('chat')->with($data,$answer)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('chat')->with($data,$answer);
        }
    }

    function get_last_message(Request $request) {
        $receiver_id = $request->receiver_id;
        $job_id = $request->job_id;
        $from = Auth::id();

        Message::where(['from'=> $receiver_id, "to"=>$from, "job_id"=>$job_id])->update(['is_read'=>1]);

        $msg_info = Message::where(function ($query) use ($from, $receiver_id, $job_id) {
            $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
        })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
            $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
        })->orderByDesc('id')->first();

        
       if($msg_info->from==Auth::id()){
            $message = Message::select('companies.name','messages.*','companies.logo')
            ->join('companies','companies.id','messages.to')
            ->where(function ($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
            })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
            })->orderByDesc('id')->first();
       }else{
            $message = Message::select('companies.name','messages.*','companies.logo')
            ->join('companies','companies.id','messages.from')
            ->where(function ($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
            })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
            })->orderByDesc('id')->first();
       }
        
        echo json_encode($message);
    }

    function get_company_messages(Request $request) {
        $user_id = $request->receiver_id;
        $job_id = $request->job_id;
        $my_id = Auth::id();

        Message::where(['from'=> $user_id, "to"=>$my_id, "job_id"=>$job_id])->update(['is_read'=>1]);

        $messages = Message::where(function ($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $my_id)->where('to', $user_id)->where('job_id', $job_id);
        })->orWhere(function($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $user_id)->where('to', $my_id)->where('job_id', $job_id);
        })->get();

        $merge_data = Message::select('companies.name','messages.*','companies.logo')->join('companies','companies.id','messages.from')
        ->where(function ($query) use ($user_id, $my_id) {
            $query->where('from', $my_id)->where('to', $user_id);
        })->orWhere(function($query) use ($user_id, $my_id) {
            $query->where('from', $user_id)->where('to', $my_id);
        })->get();

        $company_data = Company::where('id', $user_id)->get();

        return view('chat_messages', ['messages'=>$messages], ['merge_data'=>$merge_data ,'company_data'=>$company_data]);
    }

    public function post_company_messages(Request $request) {
        $to = $request->receiver_id;
        $job_id = $request->job_id;
        $from = Auth::id();
        $message = $request->message;

        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->job_id = $job_id;
        $data->message = $message;
        $data->is_read = 0;
        $data->save();

        // Pusher
        $options = array(
            'cluster'=>'ap2',
            'encrypted'=> false,
        );

        $pusher = new Pusher(
           env('PUSHER_APP_KEY'),
           env('PUSHER_APP_SECRET'),
           env('PUSHER_APP_ID'),
           $options
        );

        $data = ["from"=>$from, "to"=>$to, "job_id"=>$job_id];

        $pusherevent = $pusher->trigger('my-channel', 'my-event', $data);
        return json_encode($pusherevent);
    }

    function submit_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        $message = new CompanyMessage();
        $message->company_id = $request->company_id;
        $message->message = $request->message;
        $message->seeker_id = Auth::user()->id;
        $message->save();
        $company = Company::where('id', $request->company_id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        $data['name'] = $company->name;
        $data['email'] = $company->email;
        $data['seeker_name'] = $user->name;

        //Mail::send(new MessageSendMail($data));

        if ($message->save() == true) {
            $arr = array('msg' => 'Your message have successfully been posted. ', 'status' => true);
        }
        return Response()->json($arr);
    }
    function submitnew_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        $message = new CompanyMessage();
        $message->company_id = $request->company_id;
        $message->message = $request->message;
        $message->seeker_id = Auth::user()->id;
        $message->save();
        $company = Company::where('id', $request->company_id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        $data['name'] = $company->name;
        $data['email'] = $company->email;
        $data['seeker_name'] = $user->name;

        //Mail::send(new MessageSendMail($data));
        if ($message->save() == true) {
            $messages = CompanyMessage::where('company_id', $request->company_id)->where('seeker_id', Auth::user()->id)->get();
            $seeker = User::where('id', Auth::user()->id)->first();
            $company = Company::where('id', $request->company_id)->first();
            $search = view("seeker.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
            return $search;
        }
    }

    public function appendonly_messages(Request $request)
    {
        $seeker_id = Auth::user()->id;
        $company_id = $request->get('company_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::where('id', $seeker_id)->first();
        $company = Company::where('id', $company_id)->first();
        $search = view("seeker.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
        $data = array();
        $data['html_data'] = $search;
        $data['company_id'] = $company_id;
        return \Response::json($data);
    }
}
