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
                                <h3>{{$jobDetails[0]->title}} - <small>Milestones payment details</small    ></h3> 
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if($singlejobpayment && count($singlejobpayment) > 0)
                        @foreach($singlejobpayment as $job)
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">{{$job->milestone_title}}</a></h3>
                                    </div>
                                    <div class="jobdescription">{!! $job->description !!}</div>
                                     <div class="row mt-5">
                                        <div class="col-lg-4">
                                            <p><span style="font-weight: 600">Amount : </span> {{$job->price}}$</p>
                                        </div>

                                        <div class="col-lg-5">
                                                <p style="position: absolute;"><span style="font-weight: 600">Payment status : </span>       
                                                    @if($job->payment_status == 0) 
                                                        <span class="badge badge-pill badge-success">pending</span>
                                                    @endif
                                                    @if($job->payment_status == 1) 
                                                        <span class="badge badge-success" style="background: green;">Paid</span>
                                                    @endif
                                            </p>
                                        </div>
                                     </div>
                                </div>

                                <div class="col-md-4 col-sm-4 text-center">
                                <div class="listbtn">
                                    <b>Milestone status</b>
                                </div>
                                <div class="listbtn">
                                   @if($job->status==0)
                                    <span class="btn btn-info btn-sm">Open</span>
                                    @endif
                                    @if($job->status==1)
                                        <span class="btn btn-warning btn-sm">In progress</span>
                                    @endif
                                    @if($job->status==2)
                                        <span class="btn btn-primary btn-sm">Submitted</span>
                                    @endif
                                     @if($job->status==3)
                                        <span class="btn btn-success btn-sm">Completed</span>
                                    @endif
                                    @if($job->status==4)
                                        <span class="btn btn-warning btn-sm">Paused</span>
                                    @endif
                                   <span class="btn btn-"></span>
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