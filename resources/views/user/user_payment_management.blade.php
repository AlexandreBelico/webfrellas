@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Payment Detail Management')])
<div class="listpgWraper">
	<div class="container">@include('flash::message')
		<div class="row"> @include('includes.user_dashboard_menu')
			<div class="col-md-9 col-sm-8 ">
				<div class="card bg-light">
				  <div class="card-body">
				    <h3 class="card-title">Payment Management</h3>
				    <div class="row">
				    	<div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
				    		<h6 class="card-subtitle mb-2 text-muted">Paypal Configuration Details</h6>
				    	</div>
				    	<div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
				    		<div class="flex-wrap float-right">
				    		@if(!empty($payment_detail))
				    			<button data-toggle="modal" data-target="#btnAddPaypalDetails" type="button" class="update-details btn btn-primary btn-sm">  Update </button>
				    			<button type="button" data-toggle="modal" data-target="#deletePayapalDetail" class="delete-details btn btn-danger btn-sm">  Delete </button>
				    		@else
				    			<button type="button" data-toggle="modal" data-target="#btnAddPaypalDetails" class="btn btn-primary btn-sm float-right">  Add </button>
				    		@endif	
				    		</div>
				    	</div>
				    </div>
				    
				    <p class="card-text">Please add your Email and mobile number which is connected to your paypal.</p>
				    <br>
				    <p><b> Email Id : </b>@if(isset($payment_detail['email']) && $payment_detail['email'] != '') {{ $payment_detail['email'] }} @else Not added @endif </p>
				    <p><b> Mobile Numner : </b>@if(isset($payment_detail['mobile']) && $payment_detail['mobile'] != '') {{ $payment_detail['mobile'] }} @else Not added @endif</p>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div> 

<div id="btnAddPaypalDetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Paypal Detail</h4>
      </div>
      <div class="modal-body">
        <form class="form" method="post" action="{{ route('paypal.save.detail') }}">
        	@csrf
        	<div class="form-group">
        		<label>Email ID</label>
        		<input type="text" value="{{ (isset($payment_detail['email']))?$payment_detail['email']:'' }}" class="form-control" name='email' placeholder="Please enter your email which is connected to your Paypal Account">
        		<span id='email_error'></span>
        	</div>
        	<div class="form-group">
        		<label>Mobile Number</label>
        		<input type="text" value="{{ (isset($payment_detail['mobile']))?$payment_detail['mobile']:'' }}" class="form-control" name='mobile_number' placeholder="Please enter your mobile number which is connected to your Paypal Account">
        		<span id='mobile_number_error'></span>
        	</div>
        	<input class="btn btn-primary add_paypal_detail" onclick="JavaScript:void(0);" type="submit" name="add_paypal_details" value="Submit">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="deletePayapalDetail" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Are you sure ?</h4>
      </div>
      <div class="modal-body">
       <p>Are you sure want to remove this PayPal details from your accoutn ?</p>
      </div>
      <div class="modal-footer">
        <a href="{{ route('my.delete.paypal.details')}}" class="btn btn-danger">Confirm</a>

        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>
<style type="text/css">
	.bg-light{
		background-color: #f8f9fa!important;
		padding: 1rem
	}
	.float-right{
		float: right !important; 
	}
	b{
		font-weight: 700 !important;
	}
</style>

@include('includes.footer')
@endsection
@push('scripts')
@include('includes.immediate_available_btn')

<script>
	$(document).ready(function(){
			// $(document).on('click','#btnAddPaypalDetails',function(){
			// 	$('input[name="email"]').val('');
			// 	$('input[name="mobile_number"]').val('');
			// });
			$('.add_paypal_detail').click(function(){
				$('#email_error').html('');
				$('#mobile_number_error').html('');
				var errror_status = true;
					var email_id = $('#btnAddPaypalDetails input[name="email"]').val();
					var number = $('#btnAddPaypalDetails input[name="mobile_number"]').val();

					if(email_id == '')
					{
						errror_status=false;
						var email_error_txt='<p style="color:red"> Please enter Email</p>';
						$('#email_error').html(email_error_txt);
					}
					else if(email_id !='')
					{
						var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
						if(!regex.test(email_id)){
							errror_status=false;
							var email_error_txt='<p style="color:red"> Please enter valid email</p>';
							$('#email_error').html(email_error_txt);

						}
					}
					
					if(number == '')
					{
						var mobile_number_error_txt='<p style="color:red"> Please enter Mobile number</p>';
						errror_status=false;
						$('#mobile_number_error').html(mobile_number_error_txt);
					}
					else if(number != '')
					{
						if(!$.isNumeric(number))
						{
							var mobile_number_error_txt='<p style="color:red"> Please enter valid Mobile number</p>';
							errror_status=false;
							$('#mobile_number_error').html(mobile_number_error_txt);	
						}
					}


					return errror_status;
			});
			
	});
	
</script>
@endpush