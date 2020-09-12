@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Pay with PayPal')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container">
        <div class="row"> 
            @if(Auth::guard('company')->check())
            @include('includes.company_dashboard_menu')
            @else
            @include('includes.user_dashboard_menu')
            @endif

            <div class="col-md-9 col-sm-8">
                <div class="userccount">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="{{asset('/')}}images/paypal-logo.png" alt="" />
                            <div class="strippckinfo">
                                <h5>{{__('Invoice Details')}}</h5>
                                <div class="pkginfo">{{__('Package')}}: <strong>{{ $package->package_title }}</strong></div>
                                <div class="pkginfo">{{__('Price')}}: <strong>${{ $package->package_price }}</strong></div>

                                @if(Auth::guard('company')->check())
                                <div class="pkginfo">{{__('Can post jobs')}}: <strong>{{ $package->package_num_listings }}</strong></div>
                                @else
                                <div class="pkginfo">{{__('Can apply on jobs')}}: <strong>{{ $package->package_num_listings }}</strong></div>
                                @endif
                                <div class="pkginfo">{{__('Package Duration')}}: <strong>{{ $package->package_num_days }} {{__('Days')}}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="formpanel"> @include('flash::message')
                                <h5>{{__('Paypal - Credit Card Details')}}</h5>
                                @php                
                                $route = 'order.upgrade.package';                
                                if($new_or_upgrade == 'new'){                
                                $route = 'order.package';                
                                }                
                                @endphp                            
                                {!! Form::open(array('method' => 'post', 'route' => $route, 'id' => 'paypal-form', 'class' => 'form')) !!}                
                                {{ Form::hidden('package_id', $package_id) }}
                                {{ Form::hidden('pay_type', 'credit_card') }}
                                <div class="row">
                                    <div class="col-md-12" id="error_div"></div>
                                    <div class="col-md-12">
                                        <div class="formrow">
                                            <label>{{__('Name on Credit Card')}}</label>
                                            <input class="form-control" name="card_name" id="card_name" placeholder="{{__('Name on Credit Card')}}" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="formrow">
                                            <label>{{__('Credit card Number')}}</label>
                                            <input class="form-control" id="card_no" name="card_no" placeholder="{{__('Credit card Number')}}" type="text">
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
                                            <input class="form-control" id="cvvNumber" name="cvvNumber" placeholder="{{__('CVV number')}}" type="number" pattern="\d*" maxlength="4">
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
                        {!! Form::open(array('method' => 'post', 'route' => $route, 'id' => 'paypal-form', 'class' => 'form')) !!}                
                                {{ Form::hidden('package_id', $package_id) }}
                                {{ Form::hidden('pay_type', 'paypal') }}
                                <div id="paypal-button">
                                    <button type="submit" class="btn">{{__('Pay with Paypal')}} <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                                </div>
                                {!! Form::close() !!}
                    </div>
                    <!--div id="paypal-button"></div-->
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .userccount p{ text-align:left !important;}
</style>
@endpush
