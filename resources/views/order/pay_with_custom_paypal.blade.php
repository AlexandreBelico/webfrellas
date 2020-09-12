<div class="">
	<div class="formpanel">
		@include('flash::message')
		<h5>{{__('Paypal - Card Details')}}</h5>
		<p>Please fix your project amount for hire this person for Candidate security purpose.</p>
		@php
		$new_or_upgrade = 'new';
		$route = 'order.upgrade.package';
		if($new_or_upgrade == 'new'){
		    $route = 'pay.fee';
		} 
		@endphp
		{!! Form::open(array('method' => 'post', 'route' => $route, 'id' => 'paypal-form', 'class' => 'form')) !!}
		{{ Form::hidden('pay_type', 'credit_card') }}
		<div class="row">
			<div class="col-md-12" id="error_div"></div>
			<div class="col-md-12">
				<div class="formrow">
					<label>{{__('Name on Credit Card')}}</label>
					<input class="form-control" autocomplete="off" name="card_name" id="card_name" placeholder="{{__('Name on Credit Card')}}" type="text">
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<label>{{__('Credit card Number')}}</label>
					<input class="form-control" id="card_no" name="card_no" autocomplete="off" placeholder="{{__('Credit card Number')}}" type="text">
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
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<label>{{__('CVV Number')}}</label>
					<input class="form-control" id="cvvNumber" name="cvvNumber" autocomplete="off" placeholder="{{__('CVV number')}}" type="number" pattern="\d*" maxlength="4">
				</div>
			</div>
			<div class="col-md-12">
				<div class="formrow">
					<button type="submit" class="btn">{{__('Pay')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
		<hr>
	</div>
</div>
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