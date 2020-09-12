<div class="paypackages"> 
    <!---four-paln-->
    <div class="four-plan">
        <h3>{{__('Our Packages')}}</h3>
        <div class="row"> @foreach($packages as $package)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="boxes">
                    <li class="icon"><i class="fa fa-paper-plane" aria-hidden="true"></i></li>
                    <li class="plan-name">{{$package->package_title}}</li>
                    <li>
                        <div class="main-plan">
                            <div class="plan-price1-1">{{ $siteSetting->default_currency_code }}</div>
                            <div class="plan-price1-2">{{$package->package_price}}</div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="plan-pages">{{__('Can post jobs')}} : {{$package->package_num_listings}}</li>
                    <li class="plan-pages">{{__('Package Duration')}} : {{$package->package_num_days}} {{__('Days')}}</li>                    
                    @if($package->package_price > 0)                        
                        @if((bool)$siteSetting->is_paypal_active)
                        <li class="order paypal"><a href="{{route('order.form', [$package->id, 'new'])}}"><i class="fa fa-cc-paypal" aria-hidden="true"></i> {{__('pay with paypal')}}</a></li>
                        @endif
                        @if((bool)$siteSetting->is_stripe_active)
                        <li class="order"><a href="{{route('stripe.order.form', [$package->id, 'new'])}}"><i class="fa fa-cc-stripe" aria-hidden="true"></i> {{__('pay with stripe')}}</a></li>
                        @endif
                        <li class="order"><img alt="Visa Checkout" class="v-button" role="button"src="//sandbox.secure.checkout.visa.com/wallet-services-web/xo/button.png"/></li>
                        
                    @else
                    <li class="order paypal"><a href="{{route('order.free.package', $package->id)}}"> {{__('Subscribe Free Package')}}</a></li>
                    @endif
                </ul>
            </div>
            @endforeach </div>
            <div id="result"></div>
    </div>
    <!---end four-paln--> 
</div>
<script type="text/javascript"> 
	function onVisaCheckoutReady(){
		V.init( {
			apikey: "4RR2GU82R17TSPGZW2SG21KCOc9urTz33N9IQz5sbiFO_ImA0",
            encryptionKey: "bhi#dri/AYwSsr/JsLJR1pf2B4xxyTS6Vg@hpc4/",
			paymentRequest:{
				currencyCode: "USD",
				subtotal: "11.00"
			}
		});
		V.on("payment.success", function(payment)  {
			document.getElementById("result").innerHTML = JSON.stringify(payment);
		});
		V.on("payment.cancel", function(payment)  {
			document.getElementById("result").innerHTML = JSON.stringify(payment);
		});
		V.on("payment.error", function(payment, error)  {
			document.getElementById("result").innerHTML = JSON.stringify(payment);
		});
	}
</script>
<script type="text/javascript"src="//sandbox-assets.secure.checkout.visa.com/checkout-widget/resources/js/integration/v1/sdk.js"></script>
