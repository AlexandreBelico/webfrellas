@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Create Milestones')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> @include('flash::message')
        <div class="row">
             @include('includes.company_dashboard_menu')
            <div class="col-md-8">
                <div class="userccount">
                    <div class="formpanel"> {!! Form::open(array('method' => 'post', 'route' => ['job.milestone.update', $job])) !!} 
                        <!-- Job Information -->
                        <h5>{{$job->title}}</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('freelancer') ? ' has-error' : '' }}">
                                <select class="form-control" name="freelancer" id="freelancer">
                                    <option value="">Select User</option>
                                    @if(isset($job_applications) && count($job_applications))
                                    @foreach($job_applications as $job_application)
                                    @php
                                        $user = $job_application->getUser();
                                    @endphp
                                    @if(null !== $user)
                                        @if($user->id==$editDetails[0]->candidate_id) 
                                            <option selected="selected" value="{{$user->id}}">{{$user->getName()}}</option>
                                        @else
                                            <option value="{{$user->id}}">{{$user->getName()}}</option>
                                        @endif
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('freelancer')) <span class="help-block"> <strong>{{ $errors->first('freelancer') }}</strong> </span> @endif
                            </div>
                            </div>
                            <input type="hidden" name="job_id" value="{{ $job->id }}">
                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('task_details') ? ' has-error' : '' }}">
                                    <textarea class="form-control" name="task_details" placeholder="Enter Details of task">{{ $editDetails[0]->description }}</textarea>
                                     @if ($errors->has('task_details')) <span class="help-block"> <strong>{{ $errors->first('task_details') }}</strong> </span> @endif
                                </div>
                            </div>
                            <input type="hidden" name="editdetailId" value="{{$editDetails[0]->id}}">
                            <div class="col-md-12">
                                <div class="formrow{{ $errors->has('price') ? ' has-error' : '' }}"> 
                                    <input type="number" class="form-control" name="price" value="<?php echo $editDetails[0]->price ?>" placeholder="Price">
                                    @if ($errors->has('price')) <span class="help-block"> <strong>{{ $errors->first('price') }}</strong> </span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="formrowformrow{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                <div class='input-group date' id='startdate'>
                                    <input type='text' value="{{date_format(date_create($editDetails[0]->start_date), 'm/d/Y')}}" class="form-control" name="start_date" placeholder="Start date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                @if ($errors->has('start_date')) <span class="help-block"> <strong>{{ $errors->first('start_date') }}</strong> </span> @endif
                              </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="formrow{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                <div class='input-group date' id='enddate'>
                                    <input type='text' class="form-control" name="end_date" value="{{date_format(date_create($editDetails[0]->end_date), 'm/d/Y')}}" placeholder="End date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                @if ($errors->has('end_date')) <span class="help-block"> <strong>{{ $errors->first('end_date') }}</strong> </span> @endif
                            </div>
                            </div>
                            
                        </div>
                        <br>
                        <input type="submit" class="btn" value="{{__('Update Milestone')}}">
                        {!! Form::close() !!} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
