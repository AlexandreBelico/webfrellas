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
                            @if(count($timesheetDetails))
                                <h3>Timesheet details for {{$timesheetDetails[0]->title}} - {{$timesheetDetails[0]->name}}</h3>
                            @else 
                                <h3>No timesheet details found</h3>
                            @endif
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        @if(isset($timesheetDetails) && count($timesheetDetails))
                        @foreach($timesheetDetails as $timesheet)
                        @php
                            $whichdate = $timesheet->whichdate;
                            $whichdate = date_format(date_create($whichdate), 'd M, Y')
                        @endphp
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-sm-10">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">Milestone : {{$timesheet->milestone_title}}</a></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-2 col-sm-2 text-right">
                                  @if($timesheet->status==0)
                                     <span class="btn btn-warning btn-sm">Pending</span>
                                  @elseif($timesheet->status==1)
                                     <span class="btn btn-success btn-sm">Approved</span>
                                  @elseif($timesheet->status==2)
                                     <span class="btn btn-danger btn-sm">Rejected</span>
                                  @endif  
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <p>Date : {{$whichdate}}</p>
                            <p>Time spent : {{$timesheet->time_spent}}</p>
                            <p>{{$timesheet->description}}</p>
                        </li>
                        <!-- job end --> 
    
                        @endforeach
                        @endif
                    </ul>
                    {{ $timesheetDetails->links() }}
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