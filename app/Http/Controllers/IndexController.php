<?php

namespace App\Http\Controllers;

use App;
use App\Notification;
use App\Seo;
use App\Job;
use App\Company;
use App\FunctionalArea;
use App\Country;
use App\Video;
use App\Testimonial;
use App\Slider;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;
use App\Traits\CompanyTrait;
use App\Traits\FunctionalAreaTrait;
use App\Traits\CityTrait;
use App\Traits\JobTrait;
use App\Traits\Active;
use App\Helpers\DataArrayHelper;

class IndexController extends Controller
{

    use CompanyTrait;
    use FunctionalAreaTrait;
    use CityTrait;
    use JobTrait;
    use Active;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        if(\Illuminate\Support\Facades\Session::has('login_user_data'))
//        {
//            dd('ha',$value = Session::get('login_user_data'));
//        }else{
//            dd('Na');
//        }

        // $data = [
        //     'company_name' => 'Abc',
        //     'user_name' => 'Abc',
        //     'job_title' => 'Test',
        //     'user_link' => route('user.profile', '1'),
        //     'job_link' => route('job.detail', ['PHP-Laravel'])
        // ];
        // $user_full_name = 'PHPanchal';
        // $to_email = 'ppa@logixbuilt.com';
        // \Mail::send('emails.job_applied_company_message', $data, function($message) use ($user_full_name, $to_email) {
        //     $message->to($to_email, $user_full_name)->subject('Laravel Test Mail');
        //     $message->from('support@jobsportal.com','Test Mail');
        // });
        // $success = mail("ppa.logixbuilt@gmail.com","My subject",'hi');
        // dd($success);
        // if (!$success) {
        //     $errorMessage = error_get_last()['message'];
        // }
//        dd('hi1');
        $topCompanyIds = $this->getCompanyIdsAndNumJobs(16);
        $topFunctionalAreaIds = $this->getFunctionalAreaIdsAndNumJobs(32);
        $topIndustryIds = $this->getIndustryIdsFromCompanies(32);
        $topCityIds = $this->getCityIdsAndNumJobs(32);
        $featuredJobs = Job::active()->featured()->notExpire()->limit(12)->get();
        $latestJobs = Job::active()->notExpire()->orderBy('id', 'desc')->limit(12)->get();
        $video = Video::getVideo();
        $testimonials = Testimonial::langTestimonials();

        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $countries = DataArrayHelper::langCountriesArray();
        $sliders = Slider::langSliders();

        $seo = SEO::where('seo.page_title', 'like', 'front_index_page')->first();

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
            return view('welcome')
                ->with('topCompanyIds', $topCompanyIds)
                ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
                ->with('topCityIds', $topCityIds)
                ->with('topIndustryIds', $topIndustryIds)
                ->with('featuredJobs', $featuredJobs)
                ->with('latestJobs', $latestJobs)
                ->with('functionalAreas', $functionalAreas)
                ->with('countries', $countries)
                ->with('sliders', $sliders)
                ->with('video', $video)
                ->with('testimonials', $testimonials)
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount)
                ->with('seo', $seo);
        } else {
            return view('welcome')
                ->with('topCompanyIds', $topCompanyIds)
                ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
                ->with('topCityIds', $topCityIds)
                ->with('topIndustryIds', $topIndustryIds)
                ->with('featuredJobs', $featuredJobs)
                ->with('latestJobs', $latestJobs)
                ->with('functionalAreas', $functionalAreas)
                ->with('countries', $countries)
                ->with('sliders', $sliders)
                ->with('video', $video)
                ->with('testimonials', $testimonials)
                ->with('seo', $seo);
        }
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        $return_url = $request->input('return_url');
        $is_rtl = $request->input('is_rtl');
        $localeDir = ((bool)$is_rtl) ? 'rtl' : 'ltr';

        session(['locale' => $locale]);
        session(['localeDir' => $localeDir]);

        return Redirect::to($return_url);
    }

}
