@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Add timesheet')])
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> @include('flash::message')
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <h3>{{__('Add timesheet')}}</h3>
                <div class="userccount">
                    <div class="formpanel"> {!! Form::open(array('method' => 'post', 'route' => 'post.milestone.timeline')) !!} 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('client') ? ' has-error' : '' }}">
                                <select class="form-control" name="client" id="client" onchange="getClientJobslist(this.value)">
                                    <option value="">Select client</option>
                                    @if(isset($all_clients) && count($all_clients))
                                    @foreach($all_clients as $client)
                                        <option value="{{$client['company_id']}}">{{$client['name']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('client')) <span class="help-block"> <strong>{{ $errors->first('client') }}</strong> </span> @endif
                            </div>
                            </div>

                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('jobsofclient') ? ' has-error' : '' }}">
                                <select class="form-control" name="jobsofclient" id="jobsofclient" onchange="getMilestonesList(this.value)">
                                    <option value="">Select job</option>
                                </select>
                                @if ($errors->has('jobsofclient')) <span class="help-block"> <strong>{{ $errors->first('jobsofclient') }}</strong> </span> @endif
                            </div>
                            </div>

                            <div class="col-md-12">
                               <div class="formrow{{ $errors->has('milestonesofclient') ? ' has-error' : '' }}">
                               <select class="form-control" name="milestonesofclient" id="milestonesofclient">
                                    <option value="">Select Milestone</option>
                                   
                                </select>
                                @if ($errors->has('milestonesofclient')) <span class="help-block"> <strong>{{ $errors->first('milestonesofclient') }}</strong> </span> @endif
                            </div>
                            </div>

                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('whichdate') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" name="whichdate" id="whichdate" class="whichdate" placeholder="Select Date">
                                    @if ($errors->has('whichdate')) <span class="help-block"> <strong>{{ $errors->first('whichdate') }}</strong> </span> @endif
                                </div>
                                 
                            </div>

                            <div class="col-md-6">
                                <div class="formrow{{ $errors->has('hours') ? ' has-error' : '' }}">
                                    <select class="form-control" name="hours" id="hours">
                                        <option value="">Select hours for task</option>
                                        @for ($i = 1; $i <= 16; $i++)
                                             <option value="{{ $i }}">{{ $i }} H</option>
                                        @endfor  
                                    </select>
                                    @if ($errors->has('hours')) <span class="help-block"> <strong>{{ $errors->first('hours') }}</strong> </span> @endif
                                </div>
                                 
                            </div>

                            <div class="col-md-6">
                                <div class="formrow{{ $errors->has('minutes') ? ' has-error' : '' }}">
                                    <select class="form-control" name="minutes" id="minutes">
                                        <option value="">Select minutes for task</option>
                                        @for ($i = 0; $i <= 60; $i=$i+5)
                                             <option value="{{ $i }}">{{ $i }} M</option>
                                        @endfor  
                                    </select>
                                    @if ($errors->has('minutes')) <span class="help-block"> <strong>{{ $errors->first('minutes') }}</strong> </span> @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <textarea class="form-control" name="description" placeholder="Enter Details of task"></textarea>
                                @if ($errors->has('description')) <span class="help-block"> <strong>{{ $errors->first('description') }}</strong> </span> @endif   
                                </div>
                            </div>
                            
                        </div>
                        <br>
                        <input type="submit" class="btn" value="{{__('Submit Timesheet')}}">
                        {!! Form::close() !!} </div>
                </div>
                    
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