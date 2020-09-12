<?php

namespace App\Http\Controllers;

use App\JobFeedback;
use App\Notification;
use Auth;
use DB;
use Input;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use ImgUploader;
use Carbon\Carbon;
use Redirect;
use Newsletter;
use App\User;
use App\Subscription;
use App\ApplicantMessage;
use App\Company;
use App\FavouriteCompany;
use App\Gender;
use App\MaritalStatus;
use App\Country;
use App\State;
use App\City;
use App\JobExperience;
use App\JobApply;
use App\CareerLevel;
use App\Industry;
use App\FunctionalArea;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileSummaryTrait;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\Skills;
use App\Http\Requests\Front\UserFrontFormRequest;
use App\Helpers\DataArrayHelper;
use App\userPaypalPaymentDetail;


class UserController extends Controller
{

    use CommonUserFunctions;
    use ProfileSummaryTrait;
    use ProfileCvsTrait;
    use ProfileProjectsTrait;
    use ProfileExperienceTrait;
    use ProfileEducationTrait;
    use ProfileSkillTrait;
    use ProfileLanguageTrait;
    use Skills;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['only' => ['myProfile', 'updateMyProfile', 'viewPublicProfile']]);
        $this->middleware('auth', ['except' => ['showApplicantProfileEducation', 'showApplicantProfileProjects', 'showApplicantProfileExperience', 'showApplicantProfileSkills', 'showApplicantProfileLanguages']]);
    }

    public function viewPublicProfile($id)
    {
        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();

        $projectFeedback = JobFeedback::with('jobDetails','jobApply')/*->whereHas('jobApply',function ($q){
            $q->where('isCandidateContractStatus','=','close');
            $q->where('isEmployeerContractStatus','=','close');
        })*/ ->where('user_id', $user->id)->get();

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
            $notificationCount = Notification::where('to_user_id', $companyId->id)->where('isRead', 'false')->count();
        }

        if (isset($notification)) {
            return view('user.applicant_profile')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->with('page_title', $user->getName())
                ->with('projectFeedback', $projectFeedback)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('form_title', 'Contact ' . $user->getName());
        } else {
            return view('user.applicant_profile')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->with('page_title', $user->getName())
                ->with('projectFeedback', $projectFeedback)
                ->with('form_title', 'Contact ' . $user->getName());
        }

//        dd($projectFeedback);
    }

    public function myProfile()
    {
        $genders = DataArrayHelper::langGendersArray();
        $maritalStatuses = DataArrayHelper::langMaritalStatusesArray();
        $nationalities = DataArrayHelper::langNationalitiesArray();
        $countries = DataArrayHelper::langCountriesArray();
        $jobExperiences = DataArrayHelper::langJobExperiencesArray();
        $careerLevels = DataArrayHelper::langCareerLevelsArray();
        $industries = DataArrayHelper::langIndustriesArray();
        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();

        $upload_max_filesize = UploadedFile::getMaxFilesize() / (1048576);
        $user = User::findOrFail(Auth::user()->id);
        //echo "<pre>";print_r($user);echo "</pre>";//die();

        $userId = \Illuminate\Support\Facades\Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)->where('isRead', 'false')->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)->where('isRead', 'false')->count();
        }

        if (isset($notification)) {
            return view('user.edit_profile')
                ->with('genders', $genders)
                ->with('maritalStatuses', $maritalStatuses)
                ->with('nationalities', $nationalities)
                ->with('countries', $countries)
                ->with('jobExperiences', $jobExperiences)
                ->with('careerLevels', $careerLevels)
                ->with('industries', $industries)
                ->with('functionalAreas', $functionalAreas)
                ->with('user', $user)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('upload_max_filesize', $upload_max_filesize);
        } else {
            return view('user.edit_profile')
                ->with('genders', $genders)
                ->with('maritalStatuses', $maritalStatuses)
                ->with('nationalities', $nationalities)
                ->with('countries', $countries)
                ->with('jobExperiences', $jobExperiences)
                ->with('careerLevels', $careerLevels)
                ->with('industries', $industries)
                ->with('functionalAreas', $functionalAreas)
                ->with('user', $user)
                ->with('upload_max_filesize', $upload_max_filesize);
        }
    }

    public function updateMyProfile(UserFrontFormRequest $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        /*         * **************************************** */
        if ($request->hasFile('image')) {
            $is_deleted = $this->deleteUserImage($user->id);
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImage('public/user_images', $image, $request->input('name'), 300, 300, false);
            $user->image = $fileName;
        }

        /*         * ************************************** */
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        /*         * *********************** */
        $user->name = $user->getName();
        /*         * *********************** */
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->father_name = $request->input('father_name');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->gender_id = $request->input('gender_id');
        $user->marital_status_id = $request->input('marital_status_id');
        $user->nationality_id = $request->input('nationality_id');
        $user->national_id_card_number = $request->input('national_id_card_number');
        $user->country_id = $request->input('country_id');
        $user->state_id = $request->input('state_id');
        $user->city_id = $request->input('city_id');
        $user->phone = $request->input('phone');
        $user->mobile_num = $request->input('mobile_num');
        $user->job_experience_id = $request->input('job_experience_id');
        $user->career_level_id = $request->input('career_level_id');
        $user->industry_id = $request->input('industry_id');
        $user->functional_area_id = $request->input('functional_area_id');
        $user->current_salary = $request->input('current_salary');
        $user->expected_salary = $request->input('expected_salary');
        $user->salary_currency = $request->input('salary_currency');
        $user->street_address = $request->input('street_address');
		$user->is_subscribed = $request->input('is_subscribed', 0);

        $kk = $user->update();
        /*
        echo "<pre>";print_r($user);
        print_r($kk);
        die();*/
        $this->updateUserFullTextSearch($user);
		/*************************/
		Subscription::where('email', 'like', $user->email)->delete();
		if((bool)$user->is_subscribed)
		{
			$subscription = new Subscription();
			$subscription->email = $user->email;
			$subscription->name = $user->name;
			$subscription->save();

			/*************************/
			Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
			/*************************/
		}
		else
		{
			/*************************/
			Newsletter::unsubscribe($user->email);
			/*************************/
		}

        flash(__('You have updated your profile successfully'))->success();
        return \Redirect::route('my.profile');
    }

    public function addToFavouriteCompany(Request $request, $company_slug)
    {
        $data['company_slug'] = $company_slug;
        $data['user_id'] = Auth::user()->id;
        $data_save = FavouriteCompany::create($data);
        flash(__('Company has been added in favorites list'))->success();
        return \Redirect::route('company.detail', $company_slug);
    }

    public function removeFromFavouriteCompany(Request $request, $company_slug)
    {
        $user_id = Auth::user()->id;
        FavouriteCompany::where('company_slug', 'like', $company_slug)->where('user_id', $user_id)->delete();

        flash(__('Company has been removed from favorites list'))->success();
        return \Redirect::route('company.detail', $company_slug);
    }

    public function myFollowings()
    {
        $user = User::findOrFail(Auth::user()->id);
        $companiesSlugArray = $user->getFollowingCompaniesSlugArray();
        $companies = Company::whereIn('slug', $companiesSlugArray)->get();

        return view('user.following_companies')
                        ->with('user', $user)
                        ->with('companies', $companies);
    }

    public function myMessages()
    {
        $user = User::findOrFail(Auth::user()->id);
        $messages = ApplicantMessage::where('user_id', '=', $user->id)
                ->orderBy('is_read', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('user.applicant_messages')
                        ->with('user', $user)
                        ->with('messages', $messages);
    }

    public function applicantMessageDetail($message_id)
    {
        $user = User::findOrFail(Auth::user()->id);
        $message = ApplicantMessage::findOrFail($message_id);
        $message->update(['is_read' => 1]);

        return view('user.applicant_message_detail')
                        ->with('user', $user)
                        ->with('message', $message);
    }

    public function getUserPaymentDetails()
    {
        $user = User::findOrFail(Auth::user()->id);

        $payment_detail=[];

        if($user)
        {
            $user_payment_details = userPaypalPaymentDetail::where('user_id',$user->id)->first();

            if($user_payment_details)
            {
                $payment_detail['email']=$user_payment_details->paypal_email_id;
                $payment_detail['mobile']=$user_payment_details->paypal_mobile_number;
            }

            return view('user.user_payment_management')->with('payment_detail',$payment_detail); 
        }
        else
        {
            return \Redirect::route('my.profile');
        }
    }

    public function saveUserPaymentDetails(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);


        $email=$request->input('email');
        $mobile=$request->input('mobile_number');

        $data=array();
        $data['user_id']=$user->id;
        $data['paypal_email_id']=$email;
        $data['paypal_mobile_number']=$mobile;

        if($user)
        {
            $userPaypalPaymentDetail = userPaypalPaymentDetail::where('user_id',$user->id)->first();
            if($userPaypalPaymentDetail)
            {
                userPaypalPaymentDetail::where('id',$userPaypalPaymentDetail->id)->update($data);
                flash(__('You have successfully changed payment details'))->success();

            }
            else
            {
                userPaypalPaymentDetail::create($data);
                flash(__('You have successfully added payment details'))->success();
            }

            

            return Redirect::back(); 
        }
        else
        {
            flash(__('Logged in details not found. Please try again later.'))->success();

            return Redirect::back(); 
        }

    }

    public function deleteUserPaymentDetails()
    {
        $user = User::findOrFail(Auth::user()->id);
        if($user)
        {
            userPaypalPaymentDetail::where('user_id',$user->id)->delete();
            flash(__('Successfully Details deleted'))->success();
            return Redirect::back();
        }
        else
        {
            flash(__('Logged in details not found. Please try again later.'))->error();

            return Redirect::back(); 
        }

    }

}
