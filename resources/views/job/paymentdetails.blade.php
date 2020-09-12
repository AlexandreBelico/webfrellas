@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Payment Details')])
<!-- Inner Page Title end -->
<div class="listpgWraper"> 
    <div class="container"> @include('flash::message')
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <div class="row">
                        <div class="col-md-12">
                                <h3>Payment Details</h3>
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if($jobs && count($jobs) > 0)
                        @foreach($jobs as $job)
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">{{$job['title']}}</a></h3>
                                    </div>
                                   
                                     <div class="jobdescription">{!! $job['description'] !!}</div>
                                     
                                         <div class="row mt-5">
                                             <div class="col-lg-6">
                                                 <p><span style="font-weight: 600">Total Milestones : </span> <span>{{$job['totalMilestones']}}</span></p>
                                                 <p><span style="font-weight: 600">Completed Milestones : </span> {{$job['completedMilestones']}}</p>
                                             </div>
                                             <div class="col-lg-6">
                                                 <p><span style="font-weight: 600">Total Amount : </span> {{$job['totalMilestonePrice']}}$</p>
                                                 <p><span style="font-weight: 600">Paid : </span> 00$</p>
                                             </div>
                                         </div>
                                     
                                </div>
                                <div class="col-md-4 col-sm-4 text-right">
                                
                                <div class="listbtn"></div>
                                <div class="listbtn">
                                    <a class="btn btn-info" href="{{route('job.singlejobpaymentdetail', [$job['slug']])}}">{{__('Payment details')}}</a>
                                </div>

                                <div class="clearfix"></div>
                                </div>
                            </div>
                           
                        </li>
                        @endforeach
                        @endif
                    </ul>
                        
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