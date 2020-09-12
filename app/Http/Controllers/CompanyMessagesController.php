<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\Company;
use App\CompanyMessage;
use App\User;
use Image;
use Auth;
use Mail;
use App\Mail\MessageSendCompanyMail;
use App\FavouriteApplicant;
use App\JobApply;
use App\Job;
use App\Message;
use Session;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Pusher\Pusher;
use File;

class CompanyMessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $post_input = 'post_input';
    public function __construct()
    {
        $this->middleware('company');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function fileupload_action(Request $request) {
    {
         $to = $request->file_received_id;
         $job_id = $request->file_job_id;
         $from = Auth::guard('company')->user()->id;
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
        $from = Auth::guard('company')->user()->id;
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


    function submitnew_message_seeker(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        // $message = new CompanyMessage();
        // $message->company_id = Auth::guard('company')->user()->id;
        // $message->message = $request->message;
        // $message->seeker_id = $request->seeker_id;
        // $message->type = 'reply';
        // $message->save();

        $message = new Message();
        $message->from = Auth::guard('company')->user()->id;
        $message->to = $request->seeker_id;
        $message->job_id = $request->job_id;
        $message->message = $request->message;
        $message->is_read = 0;
        $message->save();


        // $company = Company::where('id', Auth::guard('company')->user()->id)->first();
        // $user = User::where('id', $request->seeker_id)->first();
        // $data['name'] = $user->name;
        // $data['email'] = $user->email;
        // $data['company_name'] = $company->name;

        //Mail::send(new MessageSendCompanyMail($data));
        if ($message->save() == true) {
            $arr = array('msg' => 'Your message have successfully been posted. ', 'status' => true);
        }
        return Response()->json($arr);
    }

    function submit_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        $message = new CompanyMessage();
        $message->company_id = Auth::guard('company')->user()->id;
        $message->message = $request->message;
        $message->seeker_id = $request->seeker_id;
        $message->type = 'reply';
        $message->save();
        $company = Company::where('id', Auth::guard('company')->user()->id)->first();
        $user = User::where('id', $request->seeker_id)->first();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['company_name'] = $company->name;

        //Mail::send(new MessageSendCompanyMail($data));
        if ($message->save() == true) {
            $seeker_id = $request->seeker_id;
            $company_id = Auth::guard('company')->user()->id;
            $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
            $seeker = User::where('id', $seeker_id)->first();
            $company = Company::where('id', $company_id)->first();
            $search = view("company.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
            return $search;
        }
    }
    public function all_messages()
    {

        $messages = CompanyMessage::where('company_id', Auth::guard('company')->user()->id)->get();
        $ids = array();
        foreach ($messages as $key => $value) {
            $ids[] = $value->seeker_id;
        }
        $data['seekers'] = User::whereIn('id', $ids)->get();
        return view('company.all-messages')->with($data);
    }
    public function append_messages(Request $request)
    {
        $seeker_id = $request->get('seeker_id');
        $company_id = Auth::guard('company')->user()->id;
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

        $search = view("company.append-messages", compact('messages', 'seeker', 'company'))->render();
        return $search;
    }
    public function appendonly_messages(Request $request)
    {
        $seeker_id = $request->get('seeker_id');
        $company_id = Auth::guard('company')->user()->id;
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::where('id', $seeker_id)->first();
        $company = Company::where('id', $company_id)->first();
        $search = view("company.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
        $data = array();
        $data['html_data'] = $search;
        $data['seeker_id'] = $seeker_id;
        return \Response::json($data);
    }

    public function change_message_status(Request $request)
    {
        $company_id = $request->get('company_id');
        $seeker_id = $request->get('seeker_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        if ($messages) {
            foreach ($messages as $key => $value) {
                $message = CompanyMessage::findOrFail($value->id);
                $message->status = 'viewed';
                $message->update();
            }
        }
        echo 'done';
    }

    public function company_chats() {
        $messages = CompanyMessage::where('company_id', Auth::guard('company')->user()->id)->get();
        $ids = array();
        foreach ($messages as $key => $value) {
            $ids[] = $value->seeker_id;
        }
        $data['seekers'] = User::whereIn('id', $ids)->get();

        $company_id = Auth::guard('company')->user()->id;
        $user_ids = FavouriteApplicant::where('company_id', '=', $company_id)->pluck('user_id')->toArray();

        $users = Message::select('to')->get();
        $allUserIds = [];

        foreach ($users as $user) {
            array_push($allUserIds, $user['to']);
        }

        $notIds = Message::select('to')
                    ->where('from',$company_id)
                    ->whereIn('to', $allUserIds)
                    ->whereNotIn('to', $user_ids)
                    ->groupBy('to')->get();

        if(count($notIds)>0){
            foreach ($notIds as $notId) {
             $id = $notId['to'];
             if(!in_array($id, $user_ids)){
                array_push($user_ids, $id);
             }
          }
        }

        $job_applications = JobApply::select('user_id')->whereIn('user_id', $user_ids)->groupBy('user_id')->get();

        $allUsers = [];
        foreach ($job_applications as $userIds)
        {
            $userId = $userIds['user_id'];
            $userDetails = User::where('id', $userId)->select('id', 'name', 'email', 'image')->get();

            $unread = DB::select('select count(is_read) as unread from messages where messages.to = '. $company_id . ' and messages.from = '. $userId .' and is_read = 0');

                $unread = $unread[0]->unread;

                if(isset($userDetails[0])) { $userDetails[0]->unread = $unread; }

                $jobIds = DB::select("select job_id from messages where messages.from = $company_id and messages.to = $userId group by job_id");

                if(!empty($jobIds)){
                  $allJobs = [];
                  foreach ($jobIds as $job) {
                    $job_id = $job->job_id;

                    $jobDetails = DB::select('select id, title from jobs where id = '.$job_id);
                    if(!empty($jobDetails)){
                        $unread = DB::select('select count(is_read) as unread from messages where messages.to = '. $company_id . ' and messages.from = '. $userId .' and job_id = ' . $job_id . ' and is_read = 0');
                        $jobDetails[0]->unread = $unread[0]->unread;
                        array_push($allJobs, $jobDetails[0]);
                    }
                  }
                  $userDetails[0]->jobDetails = $allJobs;
                  if(!empty($userDetails->toArray())){
                    array_push($allUsers, $userDetails);
                  }
                } else {

                }

        }

        /* foreach ($allUsers as $user) {
            echo "<pre>";
            print_r($user->toArray()); 
        }
        exit;  */
        $data['allUsers'] = $allUsers;
        $company_count = count($allUsers);

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
            return view('company.chats_page')->with($data)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('company.chats_page')->with($data);
        }
    }

    function get_last_message(Request $request) {
        $receiver_id = $request->receiver_id;
        $job_id = $request->job_id;
        $from = Auth::guard('company')->user()->id;

         Message::where(['from'=> $receiver_id, "to"=>$from, "job_id"=>$job_id])->update(['is_read'=>1]);

        $message_info = Message::where(function ($query) use ($from, $receiver_id, $job_id) {
            $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
        })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
            $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
        })->orderByDesc('id')->first();


         if($message_info->from==Auth::guard('company')->user()->id)
         {
            $message = Message::select('users.name','messages.*','users.image')->join('users','users.id','messages.to')->where(function ($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
            })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
            })->orderByDesc('id')->first();
         }
         else
         {
            $message = Message::select('users.name','messages.*','users.image')->join('users','users.id','messages.from')->where(function ($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $from)->where('to', $receiver_id)->where('job_id', $job_id);
            })->orWhere(function($query) use ($from, $receiver_id, $job_id) {
                $query->where('from', $receiver_id)->where('to', $from)->where('job_id', $job_id);
            })->orderByDesc('id')->first();
         }


        echo json_encode($message);
    }

    function get_user_messages(Request $request) {
        $user_id = $request->receiver_id;
        $job_id = $request->job_id;
        $my_id =  Auth::guard('company')->user()->id;

        Message::where(['from'=> $user_id, "to"=>$my_id, "job_id"=>$job_id])->update(['is_read'=>1]);

        $messages = Message::where(function ($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $my_id)->where('to', $user_id)->where('job_id', $job_id);
        })->orWhere(function($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $user_id)->where('to', $my_id)->where('job_id', $job_id);
        })->get();

        $merge_data_employee =Message::select('users.name','messages.*','users.image')->join('users','users.id','messages.from')->where(function ($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $my_id)->where('to', $user_id)->where('job_id', $job_id);
        })->orWhere(function($query) use ($user_id, $my_id, $job_id) {
            $query->where('from', $user_id)->where('to', $my_id)->where('job_id', $job_id);
        })->get();

        $userData = User::where('id', $user_id)->get();

        return view('company.chat_messages', ['messages'=>$messages],['merge_data_employee'=>$merge_data_employee, 'userData'=>$userData]);
    }

    public function post_user_messages(Request $request) {
        $to = $request->receiver_id;
        $job_id = $request->job_id;
        $from =  Auth::guard('company')->user()->id;
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

}
