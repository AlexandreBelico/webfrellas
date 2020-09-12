@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Job Detail')]) 
<!-- Inner Page Title end -->
@php
$company = $job->getCompany();
@endphp

<!-- =========== Submit Milestone Popup ==========  -->
<div class="modal fade" id="Submitmilestonemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    {!! Form::open(array('method' => 'post', 'route' => ['post.submitmilestone'])) !!} 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Submit milestone</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <textarea class="form-control" name="messagewhilesugmitmilestone" placeholder="Enter final message for employer here..."></textarea>
            <input type="hidden" name="submitmilestoneId" class="submitmilestoneId">
            <input type="hidden" name="job_slug" value="{{$job->slug}}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-info" value="Submit">
      </div>
    </div>
    {!! Form::close() !!} 
  </div>
</div>
<!-- =========== Submit Milestone Popup ==========  -->

<div class="listpgWraper">
    <div class="container"> 
        @include('flash::message')
       

        <!-- Job Detail start -->
        <div class="row">
            <div class="col-md-8"> 
				
				 <!-- Job Header start -->
        <div class="job-header">
            <div class="jobinfo">
                <h2>{{$job->title}} - {{$company->name}}</h2>
                <div class="ptext">{{__('Date Posted')}}: {{date('d-m-Y', strtotime($job->created_at))}}</div>
                @if(!(bool)$job->hide_salary)
                <div class="salary">{{__('Project Cost')}}: <strong>{{$job->salary_from.' '.$job->salary_currency}}</strong></div>
                @endif
                @if(Auth::check() && Auth::user() && !empty($jobs_apply))
                    @php
                    $percentVal = '5';
                    if(isset($percentage->id)){
                        $percentVal = $percentage->percent;
                    }
                    
                    $newprice = $jobs_apply->expected_salary - ($jobs_apply->expected_salary * (6/100));
                    $newprice = $newprice - ($newprice * ($percentVal/100))
                    @endphp
                    <div class="salary">{{__('Project Cost You Bid')}}: <strong>{{$jobs_apply->expected_salary.' '.$job->salary_currency}}</strong></div>
                    <div class="salary">{{__('You will receive')}}: <strong>{{number_format((float)$newprice, 2, '.', '').' '.$job->salary_currency}}</strong></div>
                @endif
            </div>
            @if(Auth::user())
            <div class="jobButtons">
                
                @if(Auth::check() && Auth::user()->isAppliedOnJob($job->id))
                    <a href="javascript:;" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Already Applied')}}</a>
                    <a href="" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Submit Your Work')}}</a>
                @else
                <a href="{{route('apply.job', $job->slug)}}" class="btn apply"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{__('Apply Now')}}</a>
                @endif
                
                @if(Auth::check() && Auth::user()->isFavouriteJob($job->slug)) <a href="{{route('remove.from.favourite', $job->slug)}}" class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Favourite Job')}} </a> @else <a href="{{route('add.to.favourite', $job->slug)}}" class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{__('Add to Favourite')}}</a> @endif
                <a href="{{route('report.abuse', $job->slug)}}" class="btn report"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{__('Report Abuse')}}</a>
            </div>
            @endif
        </div>
				
				
				
    <!-- Job Description start -->
    <div class="job-header">
        <div class="contentbox">
            <h3>{{__('Job Description')}}</h3>
            <p>{!! $job->description !!}</p>

            <hr>
            <h3>{{__('Skills Required')}}</h3>
            <ul class="skillslist">
                {!!$job->getJobSkillsList()!!}
            </ul>

            <hr>
            @if(count($milestones)>0)
            <h3>{{__('Milestones')}}</h3>
            <ul class="skillslist">
        <div class="userccount">
            @php
                $i = 1;
            @endphp
            @foreach($milestones as $milestone)
            @php
                $status = '';
                $startdate = date("d M, Y", strtotime($milestone->start_date));
                $enddate = date("d M, Y", strtotime($milestone->end_date));
            @endphp

        <div class="row">
            <div class="col-md-4">
                <h5>Milestone : {{ $i++ }}</h5>
            </div>
            <div class="col-md-3">
                 @if($milestone->status==0)
                        <span class="milestoneobjects">Status: Open</span>
                    @elseif($milestone->status==1)
                        <span class="milestoneobjects">Status: In progress</span>
                    @elseif($milestone->status==2)
                        <span class="milestoneobjects">Status: Submitted</span>
                    @elseif($milestone->status==3)
                        <span class="milestoneobjects">Status: Completed</span>
                    @elseif($milestone->status==4)
                        <span class="milestoneobjects">Status: Paused</span>
                    @endif
            </div>
            <div class="col-md-5 text-right">
                @if($milestone->status==0)
                    <a href="{{route('milestone.changestatus', [$milestone->id, 1])}}" class="btn btn-info btn-sm">Start work</a>
                @elseif($milestone->status==1)
                    <a href="{{route('milestone.changestatus', [$milestone->id, 4])}}" class="btn btn-warning btn-sm">Stop work</a>
                @elseif($milestone->status==2)
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm">Submitted</a>
                @elseif($milestone->status==3)
                    <span class="btn btn-success btn-sm">Completed</span>
                @elseif($milestone->status==4)
                    <a href="{{route('milestone.changestatus', [$milestone->id, 1])}}" class="btn btn-warning btn-sm">Start work</a>
                @endif
                @if($milestone->status>0 && $milestone->status!=3 && $milestone->status!=2)
                    <a href="javascript: void(0)" onclick="Submitmilestone({{$milestone->id}})" class="btn btn-danger btn-sm">Mark completed</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <ul >
                    <li class="milestoneobjects">Start date : {{ $startdate }} </li>
                    <li class="milestoneobjects">End date : {{ $enddate }} </li>
                    <li class="milestoneobjects">Price : {{ $milestone->price }} </li>
                </ul>
            </div>

            <div class="col-md-8 no-gutters">
                <p class="milestonedescription"> {{ str_limit($milestone->description, 400, '') }} 
                    @if (strlen($milestone->description) > 400)
                        <span id="dots_{{$milestone->id}}">...</span>
                        <span id="moredescription_{{$milestone->id}}" class="moredescription">{{ substr($milestone->description, 400) }}</span>
                        <button class="btn-link btn-anchor" href="javascript:void(0)" onclick="readmore({{$milestone->id}})" id="readmorebtn_{{$milestone->id}}">Read more</button>
                    @endif
                </p>
            </div>
        </div>
        <hr class="rowseparator">
         @endforeach
        </div>
            </ul>
            @endif
        </div>
    </div>
    <!-- Job Description end --> 

                <!-- related jobs start -->
                <div class="relatedJobs">
                    @if(isset($relatedJobs) && count($relatedJobs))
                    <h3>{{__('Related Jobs')}}</h3>
                    <ul class="searchList">
                        
                        @foreach($relatedJobs as $relatedJob)
                        <?php $relatedJobCompany = $relatedJob->getCompany(); ?>
                        @if(null !== $relatedJobCompany)
                        <!--Job start-->
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobimg"><a href="{{route('job.detail', [$relatedJob->slug])}}" title="{{$relatedJob->title}}">
                                            {{$relatedJobCompany->printCompanyImage()}}
                                        </a></div>
                                    <div class="jobinfo">
                                        <h3><a href="{{route('job.detail', [$relatedJob->slug])}}" title="{{$relatedJob->title}}">{{$relatedJob->title}}</a></h3>
                                        <div class="companyName"><a href="{{route('company.detail', $relatedJobCompany->slug)}}" title="{{$relatedJobCompany->name}}">{{$relatedJobCompany->name}}</a></div>
                                        <div class="location">
                                            <label class="fulltime">{{$relatedJob->getJobType('job_type')}}</label>
                                            <label class="partTime">{{$relatedJob->getJobShift('job_shift')}}</label>   - <span>{{$relatedJob->getCity('city')}}</span></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="listbtn"><a href="{{route('job.detail', [$relatedJob->slug])}}">{{__('View Detail')}}</a></div>
                                </div>
                            </div>
                            <p>{{str_limit(strip_tags($relatedJob->description), 150, '...')}}</p>
                        </li>
                        <!--Job end--> 
                        @endif
                        @endforeach
                        <!-- Job end -->
                    </ul>
                @else
                <ul class="searchList">
                    </ul>
                 @endif
                 </div>
            </div>
            <!-- related jobs end -->

            <div class="col-md-4"> 
				
				<div class="companyinfo">
                            <div class="companylogo"><a href="{{route('company.detail',$company->slug)}}">{{$company->printCompanyImage()}}</a></div>
                            <div class="title"><a href="{{route('company.detail',$company->slug)}}">{{$company->name}}</a></div>
                            <div class="ptext">{{$company->getLocation()}}</div>
                            <div class="opening">
                                <a href="{{route('company.detail',$company->slug)}}">
                                    {{App\Company::countNumJobs('company_id', $company->id)}} {{__('Current Jobs Openings')}}
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
				
				
                <!-- Job Detail start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Job Detail')}}</h3>
                        <ul class="jbdetail">
                            <li class="row">
                                <div class="col-md-4 col-xs-5">{{__('Location')}}</div>
                                <div class="col-md-8 col-xs-7">
                                    
                                    <span>{{str_replace( ', ', '', $job->getLocation() )}}</span>
                                    
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5">{{__('Company')}}</div>
                                <div class="col-md-8 col-xs-7"><a href="{{route('company.detail', $company->id)}}">{{$company->name}}</a></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Type')}}</div>
                                <div class="col-md-8 col-xs-7"><span class="permanent">{{$job->getJobType('job_type')}}</span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Shift')}}</div>
                                <div class="col-md-8 col-xs-7"><span class="freelance">{{$job->getJobShift('job_shift')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5">{{__('Career Level')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{$job->getCareerLevel('career_level')}}</span></div>
                            </li>
                            <li class="row">
                                <div class="col-md-4 col-xs-5">{{__('Positions')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{$job->num_of_positions}}</span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Experience')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{$job->getJobExperience('job_experience')}}</span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Gender')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{$job->getGender('gender')}}</span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Degree')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{$job->getDegreeLevel('degree_level')}}</span></div>
                            </li>
                            <li style="display:none;" class="row">
                                <div class="col-md-4 col-xs-5">{{__('Apply Before')}}</div>
                                <div class="col-md-8 col-xs-7"><span>{{date('d-m-Y', strtotime($job->expiry_date))}}</span></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Google Map start -->
                <div class="job-header">
                    <div class="jobdetail">
                        <h3>{{__('Google Map')}}</h3>
                        <div class="gmap">
                            {!!$company->map!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .view_more{display:none !important;}
</style>
@endpush
@push('scripts') 
<script>
    $(document).ready(function ($) {
        $("form").submit(function () {
            $(this).find(":input").filter(function () {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("form").find(":input").prop("disabled", false);

        $(".view_more_ul").each(function () {
            if ($(this).height() > 100)
            {
                $(this).css('height', 100);
                $(this).css('overflow', 'hidden');
                //alert($( this ).next());
                $(this).next().removeClass('view_more');
            }
        });



    });
</script> 
@endpush