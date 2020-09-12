@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Timesheet Details')])
<!-- Inner Page Title end -->
<div class="listpgWraper"> 
    <div class="container"> @include('flash::message')
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <div class="row">
                        <div class="col-md-12">
                                <h3>Development Status Details</h3>
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                        @if($job->hiredStatus==1)
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">{{$job->title}}</a></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                     <div class="jobdescription">{!! $job->description !!}</div>
                                </div>
                                <div class="col-md-4 col-sm-4 text-right">
                                
                                <div class="listbtn"><a href="{{route('job.detail', [$job->slug])}}">{{__('View Details')}}</a></div>
                                <div class="listbtn">
                                    <button type="button" class="btn btn-info btn-block">
                                        {{ $job->developmentstatus }}
                                    </button>
                                </div>

                                <div class="clearfix"></div>
                                </div>
                            </div>
                           
                        </li>
                        <!-- job end --> 
                        @endif
                        @endforeach
                        @endif
                    </ul>
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('scripts')
@include('includes.immediate_available_btn')
@endpush