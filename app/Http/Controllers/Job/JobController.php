<?php

namespace App\Http\Controllers\Job;

use App\Notification;
use DB;
use Illuminate\Support\Facades\Auth;
use Input;
use App\Job;
use App\City;
use Pusher\Pusher;
use Redirect;
use App\State;
use App\Gender;
use DataTables;
use App\Company;
use App\Country;
use App\JobType;
use App\JobApply;
use App\JobShift;
use App\JobSkill;
use App\ProfileCv;
use Carbon\Carbon;
use App\Milestones;
use App\Percentage;
use App\CareerLevel;
use App\DegreeLevel;
use App\FavouriteJob;
use App\CountryDetail;
use App\Http\Requests;
use App\JobExperience;
use App\FunctionalArea;
use App\JobSkillManager;
use App\TimesheetDetails;
use App\Traits\FetchJobs;
use App\Events\JobApplied;
use App\Mail\FeedbackMail;
use App\FavouriteApplicant;
use App\Helpers\MiscHelper;
use App\NotificationsModel;
use Illuminate\Http\Request;
use App\Events\JobApplyEvent;
use App\Helpers\DataArrayHelper;
use App\Mail\JobApplyByCandidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\JobFormRequest;
use App\Http\Requests\Front\ApplyJobFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobController extends Controller
{

    //use Skills;
    use FetchJobs;

    private $functionalAreas = '';
    private $countries = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['jobsBySearch', 'jobDetail']]);

        $this->functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $this->countries = DataArrayHelper::langCountriesArray();
    }

    public function singleJobpaymentDetail($slug) {
        $job = Job::where('slug', 'like', $slug)->firstOrFail();
        $milestoneDetails = [];
        if($job){
          $jobId = $job->id;
          $jobDetails = Job::where('id', $jobId)->get();
          $milestoneDetails = Milestones::where('job_id', $jobId)->get();
        }

        return view('job.singlejobpayment')
                ->with('singlejobpayment', $milestoneDetails)
                ->with('jobDetails', $jobDetails);
    }

    // Below function is added by hetal for get details of weekly timehseet client and candiate wise(69-120)
    public function timesheetWeeklyReport() {
         $date = \Carbon\Carbon::today()->subDays(7);
        $timesheetJobIdList = TimesheetDetails::where('created_at','>=',$date)
                                ->select('client_id', 'job_id')
                                ->groupBy('client_id', 'job_id')
                                ->get();

         if(count($timesheetJobIdList)>0){
            foreach ($timesheetJobIdList as $timesheet) {
               $job_id = $timesheet['job_id'];
               $singleUserDetails = TimesheetDetails::where('job_id', $job_id)
                                      ->select('user_id')
                                      ->groupBy('user_id')
                                      ->get();

               if(count($singleUserDetails)>0) {
                    $user_id = $singleUserDetails[0]['user_id'];
                    $singleUserTimesheet = TimesheetDetails::where('job_id', $job_id)
                                      ->join('users', 'users.id', '=', 'timesheet_details.user_id')
                                      ->join('jobs', 'jobs.id', '=', 'timesheet_details.job_id')
                                      ->select('milestone_number', 'whichdate', 'timesheet_details.description', 'time_spent', 'status', 'users.email', 'jobs.title', 'users.name', 'jobs.company_id')
                                      ->where('timesheet_details.user_id', $user_id)
                                      ->get();
                    $email = $singleUserTimesheet[0]['email'];
                    $title = $singleUserTimesheet[0]['title'];
                    $body = "Timesheets";
                    $data = array('singleUserTimesheet'=>$singleUserTimesheet);

                    // Send Email to the Candidate 
                    Mail::send('emails.weeklytimesheet',$data, function($message) use ($email, $body, $title)
                     {
                       $message->to($email, 'Webfreelas')->subject('Weekly timesheet report for '. $title)->setBody($body);
                       $message->from('support@jobsportal.com','JobsPortal');
                     });

                    // Send email to the Employer
                    $company_id = $singleUserTimesheet[0]['company_id'];
                    $companyDetails = Company::where('id', $company_id)->get();
                    $email = $companyDetails[0]['email'];
                    $data = array('singleUserTimesheet'=>$singleUserTimesheet, 'employer'=>true);

                    // return view('emails.weeklytimesheet', ['singleUserTimesheet'=> $singleUserTimesheet, 'employer'=>true]);
                     Mail::send('emails.weeklytimesheet',$data, function($message) use ($email, $body, $title)
                     {
                       $message->to($email, 'Webfreelas')->subject('Weekly timesheet report for '. $title)->setBody($body);
                       $message->from('support@jobsportal.com','JobsPortal');
                     });
               }

            }
         }
    }

    public function paymentdetails(Request $request) {
          $user =  Auth::user();
          $candidate_id = $user->id;
          $jobDetails = Milestones::where('candidate_id', $candidate_id)
                              ->groupBy('job_id')
                              ->get();

          $allJobDetails = [];
          foreach ($jobDetails as $job) {
            $jobId = $job->job_id;
            $jobDetails = Job::where('id', $jobId)->get();
            if(!empty($jobDetails)){
              $jobDetails = $jobDetails->toArray();
              $jobId = $jobDetails[0]['id'];

              $totalMilestones = Milestones::where('job_id', $jobId)
                                        ->get();

              $jobCompletedMilestones = Milestones::where('job_id', $jobId)
                                        ->where('status', 3)
                                        ->get();
              $totalMilestonePrice = 0;
              if(!empty($totalMilestones)){
                foreach ($totalMilestones as $milestone) {
                  $totalMilestonePrice = $totalMilestonePrice + $milestone->price;
                }
              }
              
              $jobDetails[0]['totalMilestones'] = count($totalMilestones);
              $jobDetails[0]['completedMilestones'] = count($jobCompletedMilestones);
              $jobDetails[0]['totalMilestonePrice'] = $totalMilestonePrice;                              
              array_push($allJobDetails, $jobDetails[0]);
            }
          }
          

          return view('job.paymentdetails')->with('jobs', $allJobDetails);
    }

    // Below function is added by hetal for change milestone status as submited(123-135)
    public function submitMilestone(Request $request) {
        $message = $request->messagewhilesugmitmilestone;
        $milestoneId = $request->submitmilestoneId;
        $job_slug = $request->job_slug;

        if(Milestones::where('id', $milestoneId)->update(array('submit_message' => $message, "status"=>2))) {
            flash(__('Milestone submitted successfully'))->success();
        } else {
           flash(__('Something wrong... Please try again later'))->error();
        }

        return \Redirect::route('job.detail', $job_slug);
    }

    // Below function is added by hetal for development status page and logic(138-187)
    public function developmentStatus() {
        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $jobs = Job::whereIn('id', $myAppliedJobIds)->paginate(10);
        $userId = Auth::user()->id;

        foreach ($jobs as $job) {
            $count = FavouriteApplicant::where('user_id', $userId)
                ->where('job_id', $job['id'])
                ->where('company_id', $job['company_id'])
                ->count();
            $job['hiredStatus'] = $count;

            $milestoneDetails = Milestones::where('job_id', $job['id'])->get();
            $open = 0;
            $inprogress = 0;
            $submitted = 0;
            $completed = 0;
            $paused = 0;

            if(count($milestoneDetails)>0){
                foreach ($milestoneDetails as $milestone) {
                    if($milestone->status==0){ $open++; }
                    else if($milestone->status==1){ $inprogress++; }
                    else if($milestone->status==2){ $submitted++; }
                    else if($milestone->status==3){ $completed++; }
                    else if($milestone->status==4){ $paused++; }
                 }

              if($open>0 && $inprogress==0 && $paused==0 && $submitted==0 && $completed==0){
                    $job['developmentstatus'] = 'Open';
              }
              if($inprogress>0){
                $job['developmentstatus'] = 'In Progress';
              }
              if($inprogress==0 && $paused>0){
                $job['developmentstatus'] = 'Paused';
              }
              if($inprogress==0 && $paused==0 && $submitted>0){
                $job['developmentstatus'] = 'submitted';
              }
              if($inprogress==0 && $paused==0 && $submitted==0 && $completed>0){
                $job['developmentstatus'] = "Completed";
              }
            }

                else {
                    $job['developmentstatus'] = "Open";
                }

            }

        return view('job.developmentstatus')
                        ->with('jobs', $jobs)
                        ->with('userId', $userId);
    }

    // Below function is added by hetal for change Milestone Status(195-220)
    public function changestatus($milestoneId, $status) {
        $milestones = Milestones::where('id', $milestoneId)->get();

        $userName = Auth::user()->name;
        $companyDetails = Company::where('id', $milestones[0]['company_id'])->get();
        $jobDetail = Job::where('id', $milestones[0]['job_id'])->get();

        $email = $companyDetails[0]->email;
        $name = $companyDetails[0]->name;
        $body = "Milestone";
        $data = array('user'=>$userName, "name"=>$name ,"jobname"=>$jobDetail[0]['title']);


        if(Milestones::where('id', $milestoneId)->update(array("status"=>$status))) {
            $status=  Mail::send('emails.milestone_change_status',$data, function($message) use ($name, $email,$body) {
            $message->to('development0261@gmail.com', 'Webfreelas')->subject('Milestone status changed')->setBody($body);
            $message->from('support@jobsportal.com','JobsPortal');
            });

            flash(__('Milestone Status changed successfully'))->success();
        } else {
           flash(__('Something wrong... Please try again later'))->error();
        }

        return \Redirect::route('job.detail', $jobDetail[0]['slug']);
    }

    public function jobsBySearch(Request $request)
    {

        $percentage = Percentage::first();
        $search = $request->query('search', '');
        $job_titles = $request->query('job_title', array());
        $company_ids = $request->query('company_id', array());
        $industry_ids = $request->query('industry_id', array());
        $job_skill_ids = $request->query('job_skill_id', array());
        $functional_area_ids = $request->query('functional_area_id', array());
        $country_ids = $request->query('country_id', array());
        $state_ids = $request->query('state_id', array());
        $city_ids = $request->query('city_id', array());
        $is_freelance = $request->query('is_freelance', array());
        $career_level_ids = $request->query('career_level_id', array());
        $job_type_ids = $request->query('job_type_id', array());
        $job_shift_ids = $request->query('job_shift_id', array());
        $gender_ids = $request->query('gender_id', array());
        $degree_level_ids = $request->query('degree_level_id', array());
        $job_experience_ids = $request->query('job_experience_id', array());
        $salary_from = $request->query('salary_from', '');
        $salary_to = $request->query('salary_to', '');
        $salary_currency = $request->query('salary_currency', '');
        $is_featured = $request->query('is_featured', 2);
        $order_by = $request->query('order_by', 'id');
        $limit = 10;

        $jobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);

        /*         * ************************************************** */

        $jobTitlesArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.title');

        /*         * ************************************************* */

        $jobIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.id');

        /*         * ************************************************** */

        $skillIdsArray = $this->fetchSkillIdsArray($jobIdsArray);

        /*         * ************************************************** */

        $countryIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.country_id');

        /*         * ************************************************** */

        $stateIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.state_id');

        /*         * ************************************************** */

        $cityIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.city_id');

        /*         * ************************************************** */

        $companyIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.company_id');

        /*         * ************************************************** */

        $industryIdsArray = $this->fetchIndustryIdsArray($companyIdsArray);

        /*         * ************************************************** */


        /*         * ************************************************** */

        $functionalAreaIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.functional_area_id');

        /*         * ************************************************** */

        $careerLevelIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.career_level_id');

        /*         * ************************************************** */

        $jobTypeIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.job_type_id');

        /*         * ************************************************** */

        $jobShiftIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.job_shift_id');

        /*         * ************************************************** */

        $genderIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.gender_id');

        /*         * ************************************************** */

        $degreeLevelIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.degree_level_id');

        /*         * ************************************************** */

        $jobExperienceIdsArray = $this->fetchIdsArray($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 'jobs.job_experience_id');

        /*         * ************************************************** */

        $seoArray = $this->getSEO($functional_area_ids, $country_ids, $state_ids, $city_ids, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids);

        /*         * ************************************************** */

        $currencies = DataArrayHelper::currenciesArray();

        /*         * ************************************************** */

        $seo = (object) array(
                    'seo_title' => $seoArray['description'],
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
        );


        $userId = Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')->where('to_user_id', $userId->id)->orderby('id', 'asc')->limit(5)->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)->where('isRead', 'false')->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')->where('to_user_id', $companyId->id)->orderby('id', 'asc')->limit(5)->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)->where('isRead', 'false')->count();
        }

        if (isset($notification)) {
            return view('job.list')
                ->with('functionalAreas', $this->functionalAreas)
                ->with('countries', $this->countries)
                ->with('currencies', array_unique($currencies))
                ->with('jobs', $jobs)
                ->with('jobTitlesArray', $jobTitlesArray)
                ->with('skillIdsArray', $skillIdsArray)
                ->with('countryIdsArray', $countryIdsArray)
                ->with('stateIdsArray', $stateIdsArray)
                ->with('cityIdsArray', $cityIdsArray)
                ->with('companyIdsArray', $companyIdsArray)
                ->with('industryIdsArray', $industryIdsArray)
                ->with('functionalAreaIdsArray', $functionalAreaIdsArray)
                ->with('careerLevelIdsArray', $careerLevelIdsArray)
                ->with('jobTypeIdsArray', $jobTypeIdsArray)
                ->with('jobShiftIdsArray', $jobShiftIdsArray)
                ->with('genderIdsArray', $genderIdsArray)
                ->with('degreeLevelIdsArray', $degreeLevelIdsArray)
                ->with('jobExperienceIdsArray', $jobExperienceIdsArray)
                ->with('percentage', $percentage)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('seo', $seo);
        } else {
            return view('job.list')
                ->with('functionalAreas', $this->functionalAreas)
                ->with('countries', $this->countries)
                ->with('currencies', array_unique($currencies))
                ->with('jobs', $jobs)
                ->with('jobTitlesArray', $jobTitlesArray)
                ->with('skillIdsArray', $skillIdsArray)
                ->with('countryIdsArray', $countryIdsArray)
                ->with('stateIdsArray', $stateIdsArray)
                ->with('cityIdsArray', $cityIdsArray)
                ->with('companyIdsArray', $companyIdsArray)
                ->with('industryIdsArray', $industryIdsArray)
                ->with('functionalAreaIdsArray', $functionalAreaIdsArray)
                ->with('careerLevelIdsArray', $careerLevelIdsArray)
                ->with('jobTypeIdsArray', $jobTypeIdsArray)
                ->with('jobShiftIdsArray', $jobShiftIdsArray)
                ->with('genderIdsArray', $genderIdsArray)
                ->with('degreeLevelIdsArray', $degreeLevelIdsArray)
                ->with('jobExperienceIdsArray', $jobExperienceIdsArray)
                ->with('percentage', $percentage)
                ->with('seo', $seo);
        }
    }

    public function jobDetail(Request $request, $job_slug)
    {
        $percentage = Percentage::first();
        $job = Job::where('slug', 'like', $job_slug)->firstOrFail();
        /*         * ************************************************** */
        $search = '';
        $job_titles = array();
        $company_ids = array();
        $industry_ids = array();
        $job_skill_ids = (array) $job->getJobSkillsArray();
        $functional_area_ids = (array) $job->getFunctionalArea('functional_area_id');
        $country_ids = (array) $job->getCountry('country_id');
        $state_ids = (array) $job->getState('state_id');
        $city_ids = (array) $job->getCity('city_id');
        $is_freelance = $job->is_freelance;
        $career_level_ids = (array) $job->getCareerLevel('career_level_id');
        $job_type_ids = (array) $job->getJobType('job_type_id');
        $job_shift_ids = (array) $job->getJobShift('job_shift_id');
        $gender_ids = (array) $job->getGender('gender_id');
        $degree_level_ids = (array) $job->getDegreeLevel('degree_level_id');
        $job_experience_ids = (array) $job->getJobExperience('job_experience_id');
        $salary_from = 0;
        $salary_to = 0;
        $salary_currency = '';
        $is_featured = 2;
        $order_by = 'id';
        $limit = 5;

        $jobs_apply = array();
        if(Auth::user()){
            $jobs_apply = JobApply::where('user_id', '=', Auth::user()->id)->where('job_id', $job->id)->first();
        }
        $relatedJobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);
        /*         * ***************************************** */

        $seoArray = $this->getSEO((array) $job->functional_area_id, (array) $job->country_id, (array) $job->state_id, (array) $job->city_id, (array) $job->career_level_id, (array) $job->job_type_id, (array) $job->job_shift_id, (array) $job->gender_id, (array) $job->degree_level_id, (array) $job->job_experience_id);
        /*         * ************************************************** */
        $seo = (object) array(
                    'seo_title' => $seoArray['description'],
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
        );

        if(!Auth::guard('company')->user()){
            $user =  Auth::user();
            $candidate_id = isset($user) ? $user->id : '';
            Notification::where('to_user_id',$candidate_id)->where('job_id',$job->id)->update(['isRead' => 'true']);
        } else {
            $user = Auth::guard('company')->user();
            $candidate_id = $user['id'];
        }

        $job_id = $job->id;

        $milestones = Milestones::where('job_id', $job_id)
                                 ->where('candidate_id', $candidate_id)
                                 ->get();


        $userId = Auth::user();
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
            Notification::where('job_id', $job_id)->update(['isRead' => 'true']);
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
            return view('job.detail')
                ->with('job', $job)
                ->with('jobs_apply', $jobs_apply)
                ->with('relatedJobs', $relatedJobs)
                ->with('percentage',$percentage)
                ->with('seo', $seo)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('milestones', $milestones);
        } else {
            return view('job.detail')
                ->with('job', $job)
                ->with('jobs_apply', $jobs_apply)
                ->with('relatedJobs', $relatedJobs)
                ->with('percentage',$percentage)
                ->with('seo', $seo)
                ->with('milestones', $milestones);
        }
    }

    /*     * ************************************************** */

    public function addToFavouriteJob(Request $request, $job_slug)
    {
        $data['job_slug'] = $job_slug;
        $data['user_id'] = Auth::user()->id;
        $data_save = FavouriteJob::create($data);
        flash(__('Job has been added in favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function removeFromFavouriteJob(Request $request, $job_slug)
    {
        $user_id = Auth::user()->id;
        FavouriteJob::where('job_slug', 'like', $job_slug)->where('user_id', $user_id)->delete();

        flash(__('Job has been removed from favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function applyJob(Request $request, $job_slug)
    {
        $percentage = Percentage::first();
        $user = Auth::user();
        $job = Job::where('slug', 'like', $job_slug)->first();
        $salaryPeriods = DataArrayHelper::defaultSalaryPeriodsArray();

        if ((bool)$user->is_active === false) {
            flash(__('Your account is inactive contact site admin to activate it'))->error();
            return \Redirect::route('job.detail', $job_slug);
            exit;
        }

        if ((bool) config('jobseeker.is_jobseeker_package_active')) {
            if (
                    ($user->jobs_quota <= $user->availed_jobs_quota) ||
                    ($user->package_end_date->lt(Carbon::now()))
            ) {
                flash(__('Please subscribe to package first'))->error();
                return \Redirect::route('home');
                exit;
            }
        }
        if ($user->isAppliedOnJob($job->id)) {
            flash(__('You have already applied for this job'))->success();
            return \Redirect::route('job.detail', $job_slug);
            exit;
        }

        $myCvs = ProfileCv::where('user_id', '=', $user->id)->pluck('title', 'id')->toArray();

        return view('job.apply_job_form')
                        ->with('job_slug', $job_slug)
                        ->with('job', $job)
                        ->with('salaryPeriods', $salaryPeriods)
                        ->with('percentage', $percentage)
                        ->with('myCvs', $myCvs);
    }

    public function postApplyJob(ApplyJobFormRequest $request, $job_slug)
    {
        try{
//            DB::beginTransaction();
                $user = Auth::user();
                $user_id = $user->id;

                $job = Job::with('company')->where('slug', 'like', $job_slug)->first();

                $jobApply = new JobApply();
                $jobApply->user_id = $user_id;
                $jobApply->job_id = $job->id;
                $jobApply->cover_letter = $request->post('cover_letter');
                $jobApply->expected_salary = $request->post('expected_salary');
                $jobApply->salary_period_id = $request->post('salary_period_id');
                $jobApply->save();

                /*         * ******************************* */
                if ((bool) config('jobseeker.is_jobseeker_package_active')) {
                    $user->availed_jobs_quota = $user->availed_jobs_quota + 1;
                    $user->update();
                }
                /*         * ******************************* */
                //event(new JobApplied($job, $jobApply));

                flash(__('You have successfully applied for this job'))->success();
                //Begin:For web based notification
                $user_full_name = ucfirst($user->first_name)." ".ucfirst($user->last_name);
                $send_user_profile_pic = isset($user->image) && !empty($user->image) && $user->image != NULL ? $user->image : 0;
                $full_msg = __($user_full_name." ed for the job.");

//                $content = " The user " . $user_full_name . "has applied for this job <a href='".url('job/' . $job->slug)."'>".ucfirst($job->title)."</a>";
                $content = $user_full_name . " has applied for this job ".ucfirst($job->title);

                $applyNotification = [
                    'to_user_id' => $job->company_id,
                    'job_id' => $job->id,
                    'content' => $content,
                    'isRead' => 'false'
                ];

                $notification = Notification::create($applyNotification);

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

                $notification = [
                    'jobSlug' => $job_slug,
                    'content' => $content,
                    'notificationId' => $notification->id
                ];

                //Send a message to notify channel with an event name of notify-event
                $pusher->trigger('employer', 'employer-notification', $notification);
                $user_full_name = ucfirst($user->first_name)." ".ucfirst($user->last_name);
                $send_user_profile_pic = isset($user->image) && !empty($user->image) && $user->image != NULL ? $user->image : 0;
                $full_msg = __($user_full_name." has applied for the job.");
                $data = [
                    'company_name' => $job->company->name,
                    'user_name' => $user_full_name,
                    'job_title' => $job->title,
                    'user_link' => route('user.profile', $user_id),
                    'job_link' => route('job.detail', [$job->slug])
                ];

                $user_full_name = ucfirst($user->first_name)." ".ucfirst($user->last_name);
                $to_email = $job->company->email;

                Mail::send('emails.job_applied_company_message', $data, function ($message) use ($user_full_name, $to_email) {
                    $message->to($to_email, $user_full_name)->subject('Applied for job');
                    $message->from('support@jobsportal.com', 'WebFreeLas');
                });
                return \Redirect::route('job.detail', $job_slug);
//            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            flash('Something went wrong!')->error();
            return back();
        }
    }


    public function submitNotificationsDetails(Request $request){
      if($request->ajax()){
        $notifications_save = new NotificationsModel();
        $notifications_save->user_id = $request->post('user_id');
        $notifications_save->message = $request->post('message');
        $query = $notifications_save->save();
        if($query){
          return $query;
        }else{
          return 0;
        }
      }
    }

    public function myJobApplications(Request $request)
    {
        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $jobs = Job::whereIn('id', $myAppliedJobIds)->paginate(10);
        $userId = Auth::user()->id;

        foreach ($jobs as $job) {
            $count = FavouriteApplicant::where('user_id', $userId)
                ->where('job_id', $job['id'])
                ->where('company_id', $job['company_id'])
                ->count();
            $applyJob = JobApply::where('user_id',$userId)->where('job_id',$job->id)->firstorfail();
            $job['hiredStatus'] = $count;
            $job['appliedUser'] = $applyJob;
        }

        $userId = Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'asc')
                ->limit(5)->get();
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
            return view('job.my_applied_jobs')
                ->with('jobs', $jobs)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('userId', $userId);
        } else {
            return view('job.my_applied_jobs')
                ->with('jobs', $jobs)
                ->with('userId', $userId);
        }
    }

    // Below function is added by hetal for dislay jobs list with timesheet details button(524-541)
    public function getTimesheetDetails(Request $request)
    {
        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $jobs = Job::whereIn('id', $myAppliedJobIds)->paginate(10);
        $userId = Auth::user()->id;

        foreach ($jobs as $job) {
            $count = FavouriteApplicant::where('user_id', $userId)
                ->where('job_id', $job['id'])
                ->where('company_id', $job['company_id'])
                ->count();
            $job['hiredStatus'] = $count;
        }

        return view('job.my_timesheet')
                        ->with('jobs', $jobs)
                        ->with('userId', $userId);
    }

    // Below function is added by hetal to add timesheet for a job and milestone(544-577)
    public function addTimesheet() {

        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();

        $jobs = Job::whereIn('jobs.id', $myAppliedJobIds)
                ->join('companies', 'jobs.company_id', '=', 'companies.id')
                ->select('jobs.id', 'companies.id as company_id', 'name')
                ->get();
        $userId = Auth::user()->id;

        foreach ($jobs as $job) {
            $count = FavouriteApplicant::where('user_id', $userId)
                ->where('job_id', $job['id'])
                ->where('company_id', $job['company_id'])
                ->count();
            $job['hiredStatus'] = $count;
        }

        $allClients = [];
        $existsClient = [];
        foreach ($jobs as $job) {
            if($job['hiredStatus']==1 && !in_array($job['company_id'], $existsClient)){
                $client['name'] = $job['name'];
                $client['company_id'] = $job['company_id'];

                array_push($allClients, $client);
                array_push($existsClient, $job['company_id']);
          }
        }

        return view('job.add_timesheet')
                ->with('job_details', $jobs)
                ->with('all_clients', $allClients);
    }

    // Below function is added by hetal for get hiredjobs list in drodown(580-594)
    public function gethiredjobslist(Request $request) {
        $clientId = $request->clientId;
        $userId = Auth::user()->id;
        $jobIdList = FavouriteApplicant::where('company_id', '=', $clientId)
                        ->where('user_id', $userId)
                        ->select('job_id')
                        ->get();

        $jobs = Job::whereIn('id', $jobIdList)->get();
        if(!empty($jobs)){
            return json_encode($jobs);
        } else {
            return 0;
        }
    }

    // Below function is added by hetal for display milestonelist in dropdown of a particular job(597-606)
    public function milestonesList(Request $request) {
        $jobId = $request->jobId;
        $milestones = Milestones::where('job_id', '=', $jobId)
                    ->get();
        if(count($milestones)>0){
            echo json_encode($milestones);
        } else {
            echo 0;
        }
    }

    // Below function is added by hetal for Submit new timesheet in database(609-644)
    public function submitTimeline(Request $request) {
        $request->validate([
            'client' => 'required',
            'jobsofclient' => 'required',
            'milestonesofclient' => 'required',
            'hours' => 'required',
            'minutes' => 'required',
            'description' => 'required',
            'whichdate' => 'required'
        ]);

        $client = $request['client'];
        $jobsofclient = $request['jobsofclient'];
        $milestonesofclient = $request['milestonesofclient'];
        $hours = $request['hours'];
        $minutes = $request['minutes'];
        $description = $request['description'];
        $whichdate = $request['whichdate'];

        $timesheet = new TimesheetDetails();
        $timesheet->client_id = $client;
        $timesheet->user_id = Auth::user()->id;
        $timesheet->job_id = $jobsofclient;
        $timesheet->whichdate = date_format(date_create($whichdate),"Y-m-d");
        $timesheet->milestone_number = $milestonesofclient;
        $timesheet->time_spent = $hours.":".$minutes." H";
        $timesheet->description = $description;

        if($timesheet->save()) {
            flash(__('You have successfully added timesheet'))->success();
        } else {
            flash(__('Something wrong!!! Please try again later'))->error();
        }

        return \Redirect::route('job.timesheets');
    }

    // Below function is added by hetal for get Single job timesheetDetail(647-657)
    public function singleJobTimesheetDetails($jobId) {
        $timesheetDetails = TimesheetDetails::where('timesheet_details.job_id', $jobId)
                            ->Join('jobs', 'jobs.id', '=', 'timesheet_details.job_id')
                            ->join('companies', 'companies.id', '=', 'timesheet_details.client_id')
                            ->join('milestones', 'milestones.id', '=', 'timesheet_details.milestone_number')
                            ->select('timesheet_details.milestone_number', 'timesheet_details.description', 'jobs.title', 'companies.name', 'timesheet_details.time_spent', 'timesheet_details.status', 'timesheet_details.whichdate', 'milestones.milestone_title')
                            ->paginate(10);

        return view('job.singlejobtimesheet')
                ->with('timesheetDetails', $timesheetDetails);
    }

    public function myFavouriteJobs(Request $request)
    {

        $jobs = JobApply::where('user_id', '=', Auth::user()->id)->where('status', 'Approved')->paginate(10);

        //$myFavouriteJobSlugs = Auth::user()->getFavouriteJobSlugsArray();


        //$jobs = Job::whereIn('slug', $myFavouriteJobSlugs)->paginate(10);

        return view('job.my_favourite_jobs')
                        ->with('project', $jobs);
    }

   
 

}
