<?php

namespace App\Http\Controllers\Company;

use App\JobFeedback;
use App\Notification;
use Mail;
use Hash;
use File;
use ImgUploader;
use Auth;
use Validator;
use DB;
use Input;
use Redirect;
use App\Subscription;
use Newsletter;
use App\User;
use App\Company;
use App\Milestones;
use App\CompanyMessage;
use App\ApplicantMessage;
use App\Country;
use App\CountryDetail;
use App\State;
use App\City;
use App\Job;
use App\Industry;
use App\FavouriteCompany;
use App\TimesheetDetails;
use App\FavouriteApplicant;
use App\OwnershipType;
use App\JobApply;
use Carbon\Carbon;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use App\Mail\CompanyContactMail;
use App\Mail\ApplicantContactMail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Front\CompanyFrontFormRequest;
use App\Http\Controllers\Controller;
use App\Traits\CompanyTrait;
use App\Traits\Cron;
use Session;
use App\Message;
use Pusher\Pusher;

class CompanyController extends Controller
{

    use CompanyTrait;
    use Cron;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('company', ['except' => ['companyDetail', 'sendContactForm']]);
        $this->runCheckPackageValidity();
    }

    public function fileupload_action()
    {
        echo 1;
        die;
    }

    public function index()
    {
        // $countCandidates = DataArrayHelper::TotalMessagesCountEmp();
        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('company_home')
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('company_home');
        }
    }

    // Below function is added by hetal for get details of editing milestone(69-93)
    public function editMilestone($milestoneId)
    {
        $milestoneDetails = Milestones::where('id', $milestoneId)->get();
        $job = Job::findOrFail($milestoneDetails[0]['job_id']);
        $currencies = DataArrayHelper::currenciesArray();
        $job_applications = JobApply::where('job_id', $milestoneDetails[0]['job_id'])->get();

        return view('job.editMilestone')
            ->with('job', $job)
            ->with('currencies', array_unique($currencies))
            ->with('editDetails', $milestoneDetails)
            ->with('job_applications', $job_applications);
    }

    // Below function is added by hetal for delete milestone logic(83-93)
    public function deleteMilestone(Request $request)
    {
        $milestoneId = $request->deleteMilestoneId;
        $milestoneDetails = Milestones::where('id', $milestoneId)->get();
        if (Milestones::where('id', '=', $milestoneId)->delete()) {
            flash(__('Milestone deleted successfully'))->success();
        } else {
            flash(__('Something wrong!!! Please try again later'))->error();
        }

        return \Redirect::route('milestones.list', [$milestoneDetails[0]['job_id']]);
    }

    // Below function is added by hetal to submit the updated milestone details(96-125)
    public function updateMilestone(Request $request)
    {
        $request->validate([
            'task_details' => 'required',
            'price' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'freelancer' => 'required',
        ]);

        $taskDetails = $request['task_details'];
        $price = $request['price'];
        $startDate = date("Y-m-d", strtotime($request['start_date']));
        $endDate = date("Y-m-d", strtotime($request['end_date']));
        $freelancer = $request['freelancer'];
        $milestoneId = $request['editdetailId'];
        $job_id = $request['job_id'];
        $company_id = Auth::guard('company')->user()->id;

        $data = array('candidate_id' => $freelancer, 'description' => $taskDetails, 'price' => $price,
            'start_date' => $startDate, 'end_date' => $endDate, 'updated_at' => date('Y-m-d H:i:s')
        );

        if (Milestones::where('id', $milestoneId)->update($data)) {
            flash(__('Milestone details updated successfully'))->success();
        } else {
            flash(__('Something wrong!!! Please try again later'))->error();
        }

        return \Redirect::route('milestones.list', [$job_id]);
    }

    // Below function is added by hetal to get the timesheets of a single Job(128-138)
    public function singleJobTimesheetDetails($jobId)
    {
        $timesheetDetails = TimesheetDetails::where('timesheet_details.job_id', $jobId)
            ->Join('jobs', 'jobs.id', '=', 'timesheet_details.job_id')
            ->join('companies', 'companies.id', '=', 'timesheet_details.client_id')
            ->join('milestones', 'milestones.id', '=', 'timesheet_details.milestone_number')
            ->select('timesheet_details.milestone_number', 'timesheet_details.description', 'jobs.title', 'companies.name', 'timesheet_details.time_spent', 'timesheet_details.id', 'timesheet_details.status', 'milestones.milestone_title')
            ->paginate(10);

        return view('company.singlejobtimesheet')
            ->with('timesheetDetails', $timesheetDetails);
    }

    // Below function is added by hetal to change status of timesheet(141-159)
    public function changeTimesheetStatus(Request $request)
    {
        if ($request['approve']) {
            $status = 1;
        }
        if ($request['reject']) {
            $status = 2;
        }
        $timesheetid = $request['timesheetid'];

        $timesheetDetails = TimesheetDetails::where('id', $timesheetid)->get();

        if (TimesheetDetails::where('id', $timesheetid)->update(array('status' => $status))) {
            flash(__('Timesheet status successfully'))->success();
        } else {
            flash(__('Something wrong!!! Please try again later'))->error();
        }

        return \Redirect::route('timesheet.details', [$timesheetDetails[0]->job_id]);
    }

    // Below function is added by hetal for get page of development status (162-211)
    public function developmentStatus()
    {
        $comp_id = Auth::guard('company')->user()->id;

        $job_applications = Job::where('company_id', $comp_id)->paginate(10);
        if (count($job_applications) > 0) {
            foreach ($job_applications as $job) {

                $milestoneDetails = Milestones::where('job_id', $job['id'])->get();
                $open = 0;
                $inprogress = 0;
                $submitted = 0;
                $completed = 0;
                $paused = 0;

                if (count($milestoneDetails) > 0) {
                    foreach ($milestoneDetails as $milestone) {
                        if ($milestone->status == 0) {
                            $open++;
                        } else if ($milestone->status == 1) {
                            $inprogress++;
                        } else if ($milestone->status == 2) {
                            $submitted++;
                        } else if ($milestone->status == 3) {
                            $completed++;
                        } else if ($milestone->status == 4) {
                            $paused++;
                        }
                    }

                    if ($open > 0 && $inprogress == 0 && $paused == 0 && $submitted == 0 && $completed == 0) {
                        $job['developmentstatus'] = 'Open';
                    }
                    if ($inprogress > 0) {
                        $job['developmentstatus'] = 'In Progress';
                    }
                    if ($inprogress == 0 && $paused > 0) {
                        $job['developmentstatus'] = 'Paused';
                    }
                    if ($inprogress == 0 && $paused == 0 && $submitted > 0) {
                        $job['developmentstatus'] = 'submitted';
                    }
                    if ($inprogress == 0 && $paused == 0 && $submitted == 0 && $completed > 0) {
                        $job['developmentstatus'] = "Completed";
                    }
                } else {
                    $job['developmentstatus'] = "Open";
                }
            }
        }

        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('company.developmentstatus')
                ->with('job_applications', $job_applications)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('company.developmentstatus')
                ->with('job_applications', $job_applications);
        }
    }

    public function company_listing()
    {
        $data['companies'] = Company::paginate(20);
        return view('company.listing')->with($data);
    }

    public function companyProfile()
    {
        $countries = DataArrayHelper::defaultCountriesArray();
        $industries = DataArrayHelper::defaultIndustriesArray();
        $ownershipTypes = DataArrayHelper::defaultOwnershipTypesArray();
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('company.edit_profile')
                ->with('company', $company)
                ->with('countries', $countries)
                ->with('industries', $industries)
                ->with('ownershipTypes', $ownershipTypes)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('company.edit_profile')
                ->with('company', $company)
                ->with('countries', $countries)
                ->with('industries', $industries)
                ->with('ownershipTypes', $ownershipTypes);
        }
    }

    public function updateCompanyProfile(CompanyFrontFormRequest $request)
    {
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        /*         * **************************************** */
        if ($request->hasFile('logo')) {
            $is_deleted = $this->deleteCompanyLogo($company->id);
            $image = $request->file('logo');
            $fileName = ImgUploader::UploadImage('public/company_logos', $image, $request->input('name'), 300, 300, false);
            $company->logo = $fileName;
        }
        /*         * ************************************** */
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $company->password = Hash::make($request->input('password'));
        }
        $company->ceo = $request->input('ceo');
        $company->industry_id = $request->input('industry_id');
        $company->ownership_type_id = $request->input('ownership_type_id');
        $company->description = $request->input('description');
        $company->location = $request->input('location');
        $company->map = $request->input('map');
        $company->no_of_offices = $request->input('no_of_offices');
        $website = $request->input('website');
        $company->website = (false === strpos($website, 'http')) ? 'http://' . $website : $website;
        $company->no_of_employees = $request->input('no_of_employees');
        $company->established_in = $request->input('established_in');
        $company->fax = $request->input('fax');
        $company->phone = $request->input('phone');
        $company->facebook = $request->input('facebook');
        $company->twitter = $request->input('twitter');
        $company->linkedin = $request->input('linkedin');
        $company->google_plus = $request->input('google_plus');
        $company->pinterest = $request->input('pinterest');
        $company->country_id = $request->input('country_id');
        $company->state_id = $request->input('state_id');
        $company->city_id = $request->input('city_id');
        $company->is_subscribed = $request->input('is_subscribed', 0);

        $company->slug = str_slug($company->name, '-') . '-' . $company->id;
        $company->update();
        /*************************/
        Subscription::where('email', 'like', $company->email)->delete();
        if ((bool)$company->is_subscribed) {
            $subscription = new Subscription();
            $subscription->email = $company->email;
            $subscription->name = $company->name;
            $subscription->save();
            /*************************/
            Newsletter::subscribeOrUpdate($subscription->email, ['FNAME' => $subscription->name]);
            /*************************/
        } else {
            /*************************/
            Newsletter::unsubscribe($company->email);
            /*************************/
        }


        flash(__('Company has been updated'))->success();
        return \Redirect::route('company.profile');
    }

    public function addToFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)
    {
        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;

        DB::table('job_apply')
            ->where('job_id', $job_id)
            ->where('user_id', $user_id)
            ->update(['status' => 'Approved']);

        $data_save = FavouriteApplicant::create($data);
        flash(__('You have successfully hired this Freelancer'))->success();
        return \Redirect::route('applicant.profile', $application_id);
    }

    public function removeFromFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)
    {
        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;
        FavouriteApplicant::where('user_id', $user_id)
            ->where('job_id', '=', $job_id)
            ->where('company_id', '=', $company_id)
            ->delete();

        DB::table('job_apply')
            ->where('job_id', $job_id)
            ->where('user_id', $user_id)
            ->update(['status' => 'Rejected']);
        flash(__('You have successfully removed this Freelancer'))->success();

        return \Redirect::route('applicant.profile', $application_id);
    }

    public function companyDetail(Request $request, $company_slug)
    {
        $company = Company::where('slug', 'like', $company_slug)->firstOrFail();
        /*         * ************************************************** */
        $seo = $this->getCompanySEO($company);
        /*         * ************************************************** */

        $projectFeedback = JobFeedback::with('jobDetails', 'jobApply')/*->whereHas('jobApply',function ($q){
            $q->where('isCandidateContractStatus','=','close');
            $q->where('isEmployeerContractStatus','=','close');
        })*/ ->where('user_id', $company->id)->get();

        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('company.detail')
                ->with('company', $company)
                ->with('projectFeedback', $projectFeedback)
                ->with('seo', $seo)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('company.detail')
                ->with('company', $company)
                ->with('projectFeedback', $projectFeedback)
                ->with('seo', $seo);
        }
    }

    public function sendContactForm(Request $request)
    {
        $msgresponse = array();
        $rules = array(
            'from_name' => 'required|max:100|between:4,70',
            'from_email' => 'required|email|max:100',
            'subject' => 'required|max:200',
            'message' => 'required',
            'to_id' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        );
        $rules_messages = array(
            'from_name.required' => __('Name is required'),
            'from_email.required' => __('E-mail address is required'),
            'from_email.email' => __('Valid e-mail address is required'),
            'subject.required' => __('Subject is required'),
            'message.required' => __('Message is required'),
            'to_id.required' => __('Recieving Company details missing'),
            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again'),
        );
        $validation = Validator::make($request->all(), $rules, $rules_messages);
        if ($validation->fails()) {
            $msgresponse = $validation->messages()->toJson();
            echo $msgresponse;
            exit;
        } else {
            $receiver_company = Company::findOrFail($request->input('to_id'));
            $data['company_id'] = $request->input('company_id');
            $data['company_name'] = $request->input('company_name');
            $data['from_id'] = $request->input('from_id');
            $data['to_id'] = $request->input('to_id');
            $data['from_name'] = $request->input('from_name');
            $data['from_email'] = $request->input('from_email');
            $data['from_phone'] = $request->input('from_phone');
            $data['subject'] = $request->input('subject');
            $data['message_txt'] = $request->input('message');
            $data['to_email'] = $receiver_company->email;
            $data['to_name'] = $receiver_company->name;
            $msg_save = CompanyMessage::create($data);
            $when = Carbon::now()->addMinutes(5);
            Mail::send(new CompanyContactMail($data));
            $msgresponse = ['success' => 'success', 'message' => __('Message sent successfully')];
            echo json_encode($msgresponse);
            exit;
        }
    }

    public function sendApplicantContactForm(Request $request)
    {
        $msgresponse = array();
        $rules = array(
            'from_name' => 'required|max:100|between:4,70',
            'from_email' => 'required|email|max:100',
            'subject' => 'required|max:200',
            'message' => 'required',
            'to_id' => 'required',
        );
        $rules_messages = array(
            'from_name.required' => __('Name is required'),
            'from_email.required' => __('E-mail address is required'),
            'from_email.email' => __('Valid e-mail address is required'),
            'subject.required' => __('Subject is required'),
            'message.required' => __('Message is required'),
            'to_id.required' => __('Recieving applicant details missing'),
            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again'),
        );
        $validation = Validator::make($request->all(), $rules, $rules_messages);
        if ($validation->fails()) {
            $msgresponse = $validation->messages()->toJson();
            echo $msgresponse;
            exit;
        } else {
            $receiver_user = User::findOrFail($request->input('to_id'));
            $data['user_id'] = $request->input('user_id');
            $data['user_name'] = $request->input('user_name');
            $data['from_id'] = $request->input('from_id');
            $data['to_id'] = $request->input('to_id');
            $data['from_name'] = $request->input('from_name');
            $data['from_email'] = $request->input('from_email');
            $data['from_phone'] = $request->input('from_phone');
            $data['subject'] = $request->input('subject');
            $data['message_txt'] = $request->input('message');
            $data['to_email'] = $receiver_user->email;
            $data['to_name'] = $receiver_user->getName();
            $msg_save = ApplicantMessage::create($data);
            $when = Carbon::now()->addMinutes(5);
            Mail::send(new ApplicantContactMail($data));
            $msgresponse = ['success' => 'success', 'message' => __('Message sent successfully')];
            echo json_encode($msgresponse);
            exit;
        }
    }

    public function postedJobs(Request $request)
    {
        $jobs = Auth::guard('company')->user()->jobs()->paginate(5);
        $comp_id = Auth::guard('company')->user()->id;

        $job_applications = Job::where('company_id', $comp_id)->get();
        //dd($job_applications);
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
            return view('job.company_posted_jobs')
                ->with('jobs', $jobs)
                ->with('job_applications', $job_applications)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('job.company_posted_jobs')
                ->with('jobs', $jobs)
                ->with('job_applications', $job_applications);
        }
    }

    public function listAppliedUsers(Request $request, $job_id)
    {
        $job_applications = JobApply::where('job_id', $job_id)->get();
        $request->session()->put('listed_job', $job_id);
        return view('job.job_applications')
            ->with('job_applications', $job_applications);
    }

    public function listFavouriteAppliedUsers(Request $request, $job_id)
    {
        $company_id = Auth::guard('company')->user()->id;
        $user_ids = FavouriteApplicant::where('job_id', '=', $job_id)->where('company_id', '=', $company_id)->pluck('user_id')->toArray();
//        $job_applications = JobApply::where('job_id', '=', $job_id)->whereIn('user_id', $user_ids)->get();

        $job_applications = JobApply::where('job_id', '=', $job_id)->whereIn('user_id', $user_ids)->where('status', 'Approved')->get();
        return view('job.job_applications')
            ->with('job_applications', $job_applications);
    }

    public function applicantProfile($application_id)
    {
        $job_application = JobApply::findOrFail($application_id);
        $user = $job_application->getUser();
        $job = $job_application->getJob();
        $company = $job->getCompany();
        $profileCv = $job_application->getProfileCv();

        /*         * ********************************************** */
        $num_profile_views = $user->num_profile_views + 1;
        $user->num_profile_views = $num_profile_views;
        $user->update();
        Session::put('hired_user', $user->id);
        Session::put('application_id', $application_id);
        // echo "<pre>";print_r($user);echo"</pre>";
        /*         * ********************************************** */
        $projectFeedback = JobFeedback::with('jobDetails')->whereHas('jobApply', function ($q) {
            $q->where('isCandidateContractStatus', '=', 'close');
            $q->where('isEmployeerContractStatus', '=', 'close');
        })->where('user_id', $user->id)->get(); 


        return view('user.applicant_profile')
            ->with('job_application', $job_application)
            ->with('user', $user)
            ->with('job', $job)
            ->with('company', $company)
            ->with('projectFeedback', $projectFeedback)
            ->with('profileCv', $profileCv)
            ->with('page_title', 'Applicant Profile')
            ->with('form_title', 'Contact Applicant');
    }

    public function userProfile($id)
    {

        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();

        /*         * ********************************************** */
        $num_profile_views = $user->num_profile_views + 1;
        $user->num_profile_views = $num_profile_views;
        $user->update();
        $projectFeedback = JobFeedback::with('jobDetails', 'jobApply')/*->whereHas('jobApply',function ($q){
            $q->where('isCandidateContractStatus','=','close');
            $q->where('isEmployeerContractStatus','=','close');
        })*/ ->where('user_id', $user->id)->get();
        /*         * ********************************************** */
        return view('user.applicant_profile')
            ->with('user', $user)
            ->with('profileCv', $profileCv)
            ->with('page_title', 'Job Seeker Profile')
            ->with('projectFeedback', $projectFeedback)
            ->with('form_title', 'Contact Job Seeker');
    }

    public function companyFollowers()
    {
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        $userIdsArray = $company->getFollowerIdsArray();
        $users = User::whereIn('id', $userIdsArray)->get();
        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'desc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('company.follower_users')
                ->with('users', $users)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('company', $company);
        } else {
            return view('company.follower_users')
                ->with('users', $users)
                ->with('company', $company);
        }
    }

    public function companyMessages()
    {
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        $messages = CompanyMessage::where('company_id', '=', $company->id)
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('company.company_messages')
            ->with('company', $company)
            ->with('messages', $messages);
    }

    public function companyMessageDetail($message_id)
    {
        $company = Company::findOrFail(Auth::guard('company')->user()->id);
        $message = CompanyMessage::findOrFail($message_id);
        $message->update(['is_read' => 1]);

        return view('company.company_message_detail')
            ->with('company', $company)
            ->with('message', $message);
    }

     //Start Added By Hetal_
    public function hireCandidateJob(Request $request,$jobApplicationId)
    {

        $job_application_id=$request->input('job_application_id');
        $job_candidate_id=$request->input('job_candidate_id');
        $job_employee_id=$request->input('job_employee_id');

        $job_application_details=JobApply::find($job_application_id);

        if($job_application_details)
        {
            $data['user_id'] = $job_candidate_id;
            $data['job_id'] = $job_application_details->job_id;

            $job_details=Job::find($data['job_id']);

            if($job_details)
            {
                $data['company_id'] = $job_details->company_id;
            }
            else
            {
                $data['company_id']=0;
            }

            DB::table('job_apply')
                ->where('job_id', $data['job_id'])
                ->where('user_id', $data['user_id'])
                ->update(['status' => 'Approved','isCandidateContractStatus'=>'open','isEmployeerContractStatus'=>'open']);

            $data_save = FavouriteApplicant::create($data);

            $options = array(
                'cluster' => 'ap2',
                'encrypted' => true
            );

            //Remember to set your credentials below.
            $pusher = new Pusher(
                'a28b149bfa9cd891c76e',
                'f9bd83f202cd75455a0a',
                '962744', $options
            );

            $job = Job::where('jobs.id',$data['job_id'])->join('companies','companies.id','=','jobs.company_id')->first();


            $content = $job->name. " has hired you for this job " . ucfirst($job->title);

             $hiredUserNotification = [
                'to_user_id' => $data['user_id'],
                'job_id' => $data['job_id'],
                'company_id' => $data['company_id'],
                'content' => $content,
                'isRead' => 'false',
            ];
            $notification = Notification::create($hiredUserNotification);
            $hiredNotification = [
                'jobSlug' => $job->slug,
                'content' => $content,
                'notificationId' => $notification->id
            ];

            $pusher->trigger('hire-candidate', 'hire-candidate-event', $hiredNotification);

            echo true;
            
        }
        else
        {
            echo false;
        }
        
        
    }

    public function getPayenteDetails(){
        echo 1;
        die();
    }
    //End Added By Hetal_



}
