@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Add Payment for Milestones')]) 
<div class="listpgWraper">
    <div class="container"> @include('flash::message')
        <div class="row">
             @include('includes.company_dashboard_menu')
            <div class="col-md-8">
                <div class="userccount">
                    <div class="formpanel">
                    	{!! Form::open(array('method' => 'post', 'route' => 'milestone.payment.save', 'class' => 'form')) !!}
						{{ Form::hidden('pay_type', 'credit_card') }}
                    		 <h5>Add Payment for Create new Milestone : </h5>
                    		 <div class="row">
								<div class="col-md-12" id="error_div"></div>
								<div class="col-md-12">
									<div class="formrow">
										<input type="hidden" name="freelancer" id='freelancer' value="{{ $post_data['freelancer'] or old('freelancer') }}">
										<input type="hidden" name="job_id" id='job_id' value="{{ $post_data['job_id'] or old('job_id') }}">
										<input type="hidden" name="milestone_title" id='milestone_title' value="{{ $post_data['milestone_title'] or old('milestone_title') }}">
										<input type="hidden" name="task_details" id='task_details' value="{{ $post_data['task_details'] or old('task_details') }}">
										<input type="number" style="display: none;"  name="price" id='price' value="{{ $post_data['price'] or old('price') }}">
										<input type="hidden" name="start_date" id='start_date' value="{{ $post_data['start_date'] or old('start_date') }}">
										<input type="hidden" name="end_date" id='end_date' value="{{ $post_data['end_date'] or old('end_date') }}">

										<label>{{__('Name on Credit Card')}}</label>
										<input class="form-control" autocomplete="off" name="card_name" id="card_name" placeholder="{{__('Name on Credit Card')}}" type="text">
										 @if ($errors->has('card_name')) <span class="help-block"> <strong>{{ $errors->first('card_name') }}</strong> </span> @endif
									</div>
								</div>
								<div class="col-md-12">
									<div class="formrow">
										<label>{{__('Credit card Number')}}</label>
										<input class="form-control" id="card_no" name="card_no" autocomplete="off" placeholder="{{__('Credit card Number')}}" type="text">
										 @if ($errors->has('card_no')) <span class="help-block"> <strong>{{ $errors->first('card_no') }}</strong> </span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="formrow">
										<label>{{__('Credit card Expiry Month')}}</label>
										<select class="form-control" name="ccExpiryMonth" id="ccExpiryMonth">
											@for ($counter = 1; $counter <= 12; $counter++)
											@php
											$val = str_pad($counter, 2, '0', STR_PAD_LEFT);
											@endphp
											<option value="{{$val}}">{{$val}}</option>
											@endfor
										</select>
										@if ($errors->has('ccExpiryMonth')) <span class="help-block"> <strong>{{ $errors->first('ccExpiryMonth') }}</strong> </span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="formrow">
										<label>{{__('Credit card Expiry Year')}}</label>
										<select class="form-control" name="ccExpiryYear" id="ccExpiryYear">
											@php
											$ccYears = MiscHelper::getCcExpiryYears();
											@endphp
											@foreach($ccYears as $year)
											<option value="{{$year}}">{{$year}}</option>
											@endforeach
										</select>
										@if ($errors->has('ccExpiryYear')) <span class="help-block"> <strong>{{ $errors->first('ccExpiryYear') }}</strong> </span> @endif
									</div>
								</div>
								<div class="col-md-12">
									<div class="formrow">
										<label>{{__('CVV Number')}}</label>
										<input class="form-control" id="cvvNumber" name="cvvNumber" autocomplete="off" placeholder="{{__('CVV number')}}" type="number" pattern="\d*" maxlength="4">
										@if ($errors->has('cvvNumber')) <span class="help-block"> <strong>{{ $errors->first('cvvNumber') }}</strong> </span> @endif
									</div>
								</div>
								<div class="col-md-12">
									<div class="formrow">
										<button type="submit" class="btn">{{__('Pay')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
                    	{!! Form::close() !!}
                   
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')

<style type="text/css">
	.help-block
	{
		color: red;
	}
</style>

@push('scripts') 
<script type="text/javascript">
var d = new Date();
var n = d.getMonth();
n = n+1;
if(n<10){
    n = '0'+n;
}
$("#card_no").attr({
    maxlength: 16
});
$("#ccExpiryMonth").val(n);
</script> 
@endpush
@endsection

