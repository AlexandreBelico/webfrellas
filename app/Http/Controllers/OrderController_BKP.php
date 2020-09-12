<?php

namespace App\Http\Controllers;

use App\Company;
use App\Notification;
use DB;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use Config;
use App\Package;
use App\User;
use App\Job;
use App\FavouriteApplicant;
use Carbon\Carbon;
use Cake\Chronos\Chronos;
use App\Traits\JobTrait;
use App\Traits\CompanyPackageTrait;
use App\Traits\JobSeekerPackageTrait;
use App\PaymentDetails;
/** All Paypal Details class * */
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Links;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PaymentHistory;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\RelatedResources;
use PayPal\Api\Sale;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Api\Transactions;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;


class OrderController extends Controller
{
    use JobTrait;
    use CompanyPackageTrait;
    use JobSeekerPackageTrait;

    private $_api_context;
    private $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** setup PayPal api context * */
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);

        /*         * ****************************************** */
        $this->middleware(function ($request, $next) {
            if (Auth::guard('company')->check()) {
                $this->redirectTo = 'company.home';
            }
            return $next($request);
        });
        /*         * ****************************************** */
    }

    public function orderForm($package_id, $new_or_upgrade)
    {
        $package = Package::findOrFail($package_id);
        $buyer_id = '';
        $buyer_name = '';
        if (Auth::guard('company')->check()) {
            $buyer_id = Auth::guard('company')->user()->id;
            $buyer_name = Auth::guard('company')->user()->name . '(' . Auth::guard('company')->user()->email . ')';
        }
        if (Auth::check()) {
            $buyer_id = Auth::user()->id;
            $buyer_name = Auth::user()->getName() . '(' . Auth::user()->email . ')';
        }
        $package_for = ($package->package_for == 'employer') ? __('Employer') : __('Job Seeker');
        $description = $package_for . ' ' . $buyer_name . ' - ' . $buyer_id . ' ' . __('Package') . ':' . $package->package_title;
        $item_name = $package_for . ' ' . __('Package') . ' : ' . $package->package_title;
        return view('order.pay_with_paypal')
                        ->with('package', $package)
                        ->with('item_name', $item_name)
                        ->with('description', $description)
                        ->with('package_id', $package_id)
                        ->with('new_or_upgrade', $new_or_upgrade);
    }

    public function orderPayment(Request $request)
    { 
        $package_id = '';
        $hired_user = '';
        $request->buyer_id = '';
        $request->buyer_name = '';
        $request->cost = '';
        $application_id = '';

        //$request->session()->forget('listed_job');
        if($request->session()->has('listed_job')){
            $package_id = $request->session()->get('listed_job');
        }else{
            flash(__('Invalid Access'));
            return back();
        }

        if($request->session()->has('application_id')){
            $application_id = $request->session()->get('application_id');
        }else{
            flash(__('Invalid Access'));
            return back();
        }

        if($request->session()->has('hired_user')){
            $hired_user = $request->session()->get('hired_user');
        }else{
            flash(__('Invalid Access'));
            return back();
        }

        if($request->pay_type == 'credit_card'){
            $pay_type = 'credit_card';
        }else if($request->pay_type == 'paypal'){
            $pay_type = 'paypal';
        }else{
            flash(__('Invalid Method'));
            return back();
        }
        if (Auth::guard('company')->check()) {
            $request->buyer_id = Auth::guard('company')->user()->id;
            $request->buyer_name = Auth::guard('company')->user()->name . '(' . Auth::guard('company')->user()->email . ')';
        }else{
            flash(__('Invalid User'));
            return back();
        }


        $request->package_id = $package_id;
        $request->hireduser = $hired_user;

        $vals = DB::table('job_apply')
            ->where('job_id', $package_id)
            ->where('user_id', $hired_user)
            ->get();
        $user = User::findOrFail($hired_user);

        $package = Job::findOrFail($package_id);

        if($vals[0]->expected_salary){
           $request->cost = $vals[0]->expected_salary;
        }
        $order_amount = $request->cost;
        $description = $request->buyer_name . '('. $request->buyer_id .') hired candidate' . $hired_user .'('.$user->email.')('.$user->id.') for project ' . __('Project') . ': ' . $package->title.'('.$package->id.')';

       

        if($pay_type == 'credit_card'){

            if(!isset($request->card_name) || $request->card_name=='' ){
                flash(__('Card Name is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryMonth) || $request->ccExpiryMonth=='' ){
                flash(__('Card Month is Required'));
                return back();
            }
            if(!is_numeric($request->card_no) || $request->card_no=='' ){
                flash(__('Card Number is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryYear) || $request->ccExpiryYear=='' ){
                flash(__('Card Expire Year is Required'));
                return back();
            }
            if(!is_numeric($request->cvvNumber) || $request->cvvNumber==''  ){
                flash(__('CVV is Required'));
                return back();
            }

            if(strlen((string)$request->cvvNumber) > 3 || strlen((string)$request->cvvNumber) < 3){
                flash(__('CVV is Required'));
                return back();
            }

            if($this->check_cc($request->card_no) !== false){
                $card_type = $this->check_cc($request->card_no);
            }else{
                $card_type = 'Error';
                flash(__('Card Type is Invalid'));
                return back();
            }

            // ### CreditCard
            $card = new creditCard();
            $card->setType($card_type)
                ->setNumber($request->card_no)
                ->setExpireMonth($request->ccExpiryMonth)
                ->setExpireYear($request->ccExpiryYear)
                ->setCvv2($request->cvvNumber)
                ->setFirstName($request->card_name)
                ->setLastName($request->card_name);

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // Use a Payer ID (A unique identifier of the payer generated
            // and provided by the facilitator. This is required when
            // creating or using a tokenized funding instrument)
            // and the `CreditCardDetails`
            $fi = new fundingInstrument();
            $fi->setCreditCard($card);

            // ### Payer
            // A resource representing a Payer that funds a payment
            // Use the List of `FundingInstrument` and the Payment Method
            // as 'credit_card'
            $payer = new payer();
            $payer->setPaymentMethod("credit_card")
                 ->setFundingInstruments([$fi]);
        }

        if($pay_type == 'paypal'){
            $payer = new payer();
            $payer->setPaymentMethod("paypal");
        }
       // echo "<pre>";print_r($payer);echo "</pre>";
        $item1 = new item();
        $item1->setName($description)
                ->setDescription($description)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($order_amount);

        $itemList = new itemList();
        $itemList->setItems([$item1]);

        //Payment Amount
        $amount = new amount();
        $amount->setCurrency("USD")
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal($order_amount);


        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = new transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description)
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        $payment = new payment();

        if($pay_type=='paypal'){
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('payment.status', $package_id)) /** Specify return URL * */
                ->setCancelUrl(URL::route('payment.status', $package_id));
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions([$transaction]);
        }else{
            $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]);
        }



       try {

        echo '<pre>';
        print_r($payment);
        die();
            $payment->create($this->_api_context);


        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message 
            die($ex);
        
           flash($ex->getMessage());
           return Redirect::route($this->redirectTo);

           // return response()->json(["error" => $ex->getMessage()], 400);

        }

        

        // $amt = new Amount();
        // $amt->setTotal($order_amount)
        //   ->setCurrency('USD');
        
        // $refund = new Refund();
        // $refund->setAmount($amt);
        
        
        // // ###Sale
        // // A sale transaction.
        // // Create a Sale object with the
        // // given sale transaction id.
        // $sale = new Sale();
        // $sale->setId('3CV57606M1389554U');
        // try {
        //     // Create a new apiContext object so we send a new
        //     // PayPal-Request-Id (idempotency) header for this resource

        //     // Refund the sale
        //     // (See bootstrap.php for more on `ApiContext`)
        //     $refundedSale = $sale->refund($refund, $this->_api_context);
        // } catch (Exception $ex) {
        //     ResultPrinter::printError("Refund Sale", "Sale", $refundedSale->getId(), $refund, $ex);
        //     exit(1);
        // }
        // print_r($refundedSale);
        
        // die();
        
        // print_r($payment);

        // die();

        if($payment->state ==='approved'){

            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();
            $invoice_number=$transactions[0]->getInvoiceNumber();

            /**
             * Write Here Your Database insert logic.
             */
            $data['user_id'] = $request->hireduser;
            $data['job_id'] = $package_id;
            $data['company_id'] = $request->buyer_id;
               DB::table('job_apply')
                ->where('job_id', $package_id)
                ->where('user_id', $request->hireduser)
                ->update(['status' => 'Approved','isCandidateContractStatus'=>'open','isEmployeerContractStatus'=>'open']);

            $data_save = FavouriteApplicant::create($data);
            //Remember to change this with your cluster name.
            $options = array(
                'cluster' => 'ap2',
                'encrypted' => true
            );

            //Remember to set your credentials below.
            $pusher = new Pusher(
                'a28b149bfa9cd891c76e',
                'f9bd83f202cd75455a0a',
                '962744', $options
            );

            $job = Job::where('id',$package_id)->first();

            $payment_data = [];

            $payment_data['employee_id']=Auth::guard('company')->user()->id;
            $payment_data['candidate_id']=$request->hireduser;
            $payment_data['job_apply_id']=$vals[0]->id;
            $payment_data['job_id']=$job->id;
            $payment_data['sale_id']=$saleId;
            $payment_data['payment_status']=$payment->state;
            $payment_data['transaction_details']=json_encode($relatedResources);
            $payment_data['invoice_number']=$invoice_number;

            $payment_details = PaymentDetails::create($payment_data);

            $content = $job->company->name . " has hired you for this job " . ucfirst($job->title);
            $hiredUserNotification = [
                'to_user_id' => $request->hireduser,
                'job_id' => $package_id,
                'company_id' => $request->buyer_id,
                'content' => $content,
                'isRead' => 'false',
            ];
            $notification = Notification::create($hiredUserNotification);
            $hiredNotification = [
                'jobSlug' => $job->slug,
                'content' => $content,
                'notificationId' => $notification->id
            ];

            //Send a message to notify channel with an event name of notify-event
            $pusher->trigger('hire-candidate', 'hire-candidate-event', $hiredNotification);

            flash(__('You have successfully hired this Freelancer'))->success();
            return \Redirect::route('applicant.profile', $application_id);
        } else {
            flash(__('Payment not approved Please do payment for hire Freelancer for Fixed price project.'));
            return \Redirect::route('applicant.profile', $application_id);
        }

    }
    /**
     * Store a details of payment with paypal.
     *
     * @param IlluminateHttpRequest $request
     * @return IlluminateHttpResponse
     */
    public function orderPackage(Request $request)
    {

        if($request->pay_type == 'credit_card'){
            $pay_type = 'credit_card';
        }else if($request->pay_type == 'paypal'){
            $pay_type = 'paypal';
        }else{
            flash(__('Invalid Method'));
            return back();
        }

        $package_id = $request->package_id;
        $package = Package::findOrFail($request->package_id);

        $order_amount = $package->package_price;

        /*         * ************************ */
        $buyer_id = '';
        $buyer_name = '';
        if (Auth::guard('company')->check()) {
            $buyer_id = Auth::guard('company')->user()->id;
            $buyer_name = Auth::guard('company')->user()->name . '(' . Auth::guard('company')->user()->email . ')';
        }
        if (Auth::check()) {
            $buyer_id = Auth::user()->id;
            $buyer_name = Auth::user()->getName() . '(' . Auth::user()->email . ')';
        }
        $package_for = ($package->package_for == 'employer') ? __('Employer') : __('Job Seeker');
        $description = $package_for . ' ' . $buyer_name . ' - ' . $buyer_id . ' ' . __('Package') . ':' . $package->package_title;
        /*         * ************************ */

       if($pay_type == 'credit_card'){

            if(!isset($request->card_name) || $request->card_name=='' ){
                flash(__('Card Name is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryMonth) || $request->ccExpiryMonth=='' ){
                flash(__('Card Month is Required'));
                return back();
            }
            if(!is_numeric($request->card_no) || $request->card_no=='' ){
                flash(__('Card Number is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryYear) || $request->ccExpiryYear=='' ){
                flash(__('Card Expire Year is Required'));
                return back();
            }
            if(!is_numeric($request->cvvNumber) || $request->cvvNumber==''  ){
                flash(__('CVV is Required'));
                return back();
            }

            if(strlen((string)$request->cvvNumber) > 3 || strlen((string)$request->cvvNumber) < 3){
                flash(__('CVV is Required'));
                return back();
            }

            if($this->check_cc($request->card_no) !== false){
                $card_type = $this->check_cc($request->card_no);
            }else{
                $card_type = 'Error';
                flash(__('Card Type is Invalid'));
                return back();
            }

            // ### CreditCard
            $card = new creditCard();
            $card->setType($card_type)
                ->setNumber($request->card_no)
                ->setExpireMonth($request->ccExpiryMonth)
                ->setExpireYear($request->ccExpiryYear)
                ->setCvv2($request->cvvNumber)
                ->setFirstName($request->card_name)
                ->setLastName($request->card_name);

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // Use a Payer ID (A unique identifier of the payer generated
            // and provided by the facilitator. This is required when
            // creating or using a tokenized funding instrument)
            // and the `CreditCardDetails`
            $fi = new fundingInstrument();
            $fi->setCreditCard($card);

            // ### Payer
            // A resource representing a Payer that funds a payment
            // Use the List of `FundingInstrument` and the Payment Method
            // as 'credit_card'
            $payer = new payer();
            $payer->setPaymentMethod("credit_card")
                 ->setFundingInstruments([$fi]);
        }

        if($pay_type == 'paypal'){
            $payer = new payer();
            $payer->setPaymentMethod("paypal");
        }

        $item1 = new item();
        $item1->setName($package_for . ' ' . __('Package') . ' : ' . $package->package_title)
                ->setDescription($package_for . ' ' . __('Package') . ' : ' . $package->package_title)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($order_amount);

        $itemList = new itemList();
        $itemList->setItems([$item1]);

        //Payment Amount
        $amount = new amount();
        $amount->setCurrency("USD")
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal($order_amount);


        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = new transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description)
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        $payment = new payment();

        if($pay_type=='paypal'){
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('payment.status', $package_id)) /** Specify return URL * */
                ->setCancelUrl(URL::route('payment.status', $package_id));
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions([$transaction]);
        }else{
            $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]);
        }


        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $payment->create($this->_api_context);
        } catch (\PPConnectionException $ex) {

            flash($ex->getMessage());
            return Redirect::route($this->redirectTo);

           // return response()->json(["error" => $ex->getMessage()], 400);

        }
        if($payment->state ==='approved'){
            /**
             * Write Here Your Database insert logic.
             */
            if (Auth::guard('company')->check()) {
                $company = Auth::guard('company')->user();
                $this->addCompanyPackage($company, $package);
            }
            if (Auth::check()) {
                $user = Auth::user();
                $this->addJobSeekerPackage($user, $package);
            }

            flash(__('You have successfully subscribed to selected package'))->success();
        } else {
            flash(__('Package subscription failed'));
        }

        if($pay_type !='paypal'){
            return Redirect::route($this->redirectTo);
        }else{
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session * */
            Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal * */
                return Redirect::away($redirect_url);
            }
            flash(__('Unknown error occurred'));
            return Redirect::route($this->redirectTo);
        }

       // return response()->json([$payment->toArray()], 200);
    }


    public function orderUpgradePackage(Request $request)
    {

        if($request->pay_type == 'credit_card'){
            $pay_type = 'credit_card';
        }else if($request->pay_type == 'paypal'){
            $pay_type = 'paypal';
        }else{
            flash(__('Invalid Method'));
            return back();
        }

        $package_id = $request->package_id;
        $package = Package::findOrFail($request->package_id);

        $order_amount = $package->package_price;

        /*         * ************************ */
        $buyer_id = '';
        $buyer_name = '';
        if (Auth::guard('company')->check()) {
            $buyer_id = Auth::guard('company')->user()->id;
            $buyer_name = Auth::guard('company')->user()->name . '(' . Auth::guard('company')->user()->email . ')';
        }
        if (Auth::check()) {
            $buyer_id = Auth::user()->id;
            $buyer_name = Auth::user()->getName() . '(' . Auth::user()->email . ')';
        }
        $package_for = ($package->package_for == 'employer') ? __('Employer') : __('Job Seeker');
        $description = $package_for . ' ' . $buyer_name . ' - ' . $buyer_id . ' ' . __('Package') . ':' . $package->package_title;
        /*         * ************************ */

       if($pay_type == 'credit_card'){

            if(!isset($request->card_name) || $request->card_name=='' ){
                flash(__('Card Name is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryMonth) || $request->ccExpiryMonth=='' ){
                flash(__('Card Month is Required'));
                return back();
            }
            if(!is_numeric($request->card_no) || $request->card_no=='' ){
                flash(__('Card Number is Required'));
                return back();
            }
            if(!is_numeric($request->ccExpiryYear) || $request->ccExpiryYear=='' ){
                flash(__('Card Expire Year is Required'));
                return back();
            }
            if(!is_numeric($request->cvvNumber) || $request->cvvNumber==''  ){
                flash(__('CVV is Required'));
                return back();
            }

            if(strlen((string)$request->cvvNumber) > 3 || strlen((string)$request->cvvNumber) < 3){
                flash(__('CVV is Required'));
                return back();
            }

            if($this->check_cc($request->card_no) !== false){
                $card_type = $this->check_cc($request->card_no);
            }else{
                $card_type = 'Error';
                flash(__('Card Type is Invalid'));
                return back();
            }

            // ### CreditCard
            $card = new creditCard();
            $card->setType($card_type)
                ->setNumber($request->card_no)
                ->setExpireMonth($request->ccExpiryMonth)
                ->setExpireYear($request->ccExpiryYear)
                ->setCvv2($request->cvvNumber)
                ->setFirstName($request->card_name)
                ->setLastName($request->card_name);

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // Use a Payer ID (A unique identifier of the payer generated
            // and provided by the facilitator. This is required when
            // creating or using a tokenized funding instrument)
            // and the `CreditCardDetails`
            $fi = new fundingInstrument();
            $fi->setCreditCard($card);

            // ### Payer
            // A resource representing a Payer that funds a payment
            // Use the List of `FundingInstrument` and the Payment Method
            // as 'credit_card'
            $payer = new payer();
            $payer->setPaymentMethod("credit_card")
                 ->setFundingInstruments([$fi]);
        }

        if($pay_type == 'paypal'){
            $payer = new payer();
            $payer->setPaymentMethod("paypal");
        }

        $item1 = new item();
        $item1->setName($package_for . ' ' . __('Package') . ' : ' . $package->package_title)
                ->setDescription($package_for . ' ' . __('Package') . ' : ' . $package->package_title)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($order_amount);



        $itemList = new itemList();
        $itemList->setItems([$item1]);

        //Payment Amount
        $amount = new amount();
        $amount->setCurrency("USD")
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal($order_amount);


        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = new transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description)
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        $payment = new payment();

        if($pay_type=='paypal'){
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('payment.status', $package_id)) /** Specify return URL * */
                ->setCancelUrl(URL::route('payment.status', $package_id));
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions([$transaction]);
        }else{
            $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]);
        }


        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $payment->create($this->_api_context);
        } catch (\PPConnectionException $ex) {

            flash($e->getMessage());
            return Redirect::route($this->redirectTo);

           // return response()->json(["error" => $ex->getMessage()], 400);

        }
        if($payment->state ==='approved'){

            /**
             * Write Here Your Database insert logic.
             */
            if (Auth::guard('company')->check()) {
                $company = Auth::guard('company')->user();
                $this->updateCompanyPackage($company, $package);
            }
            if (Auth::check()) {
                $user = Auth::user();
                $this->updateJobSeekerPackage($user, $package);
            }

            flash(__('You have successfully subscribed to selected package'))->success();
            //return Redirect::route($this->redirectTo);
        } else {
            flash(__('Package subscription failed'));
           // return Redirect::route($this->redirectTo);
        }

        if($pay_type !='paypal'){
            return Redirect::route($this->redirectTo);
        }else{
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session * */
            Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal * */
                return Redirect::away($redirect_url);
            }
            flash(__('Unknown error occurred'));
            return Redirect::route($this->redirectTo);
        }
    }

    public function check_cc($number)
    {
        global $type;

        $cardtype = array(
            "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$|^2(?:2(?:2[1-9]|[3-9][0-9])|[3-6][0-9][0-9]|7(?:[01][0-9]|20))[0-9]{12}$/",
            "amex"       => "/^3[47][0-9]{13}$/",
            "discover"   => "/^65[4-9][0-9]{13}|64[4-9][0-9]{13}|6011[0-9]{12}|(622(?:12[6-9]|1[3-9][0-9]|[2-8][0-9][0-9]|9[01][0-9]|92[0-5])[0-9]{10})$/",
        );

        if (preg_match($cardtype['visa'],$number))
        {
             $type= "visa";
            return 'visa';

        }
        else if (preg_match($cardtype['mastercard'],$number))
        {
            $type= "mastercard";
            return 'mastercard';
        }
        else if (preg_match($cardtype['amex'],$number))
        {
        $type= "amex";
            return 'amex';

        }
        else if (preg_match($cardtype['discover'],$number))
        {
        $type= "discover";
            return 'discover';
        }
        else
        {
            return false;
        }
    }

    public function getUpgradePaymentStatus(Request $request, $package_id)
    {

        $package = Package::findOrFail($package_id);

        /** Get the payment ID before session clear * */
        $payment_id = $request->get('paymentId'); //Session::get('paypal_payment_id');
        /** clear the session payment ID * */
        Session::forget('paypal_payment_id');
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            flash(__('Subscription failed'));
            return Redirect::route($this->redirectTo);
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary * */
        /** to execute a PayPal account payment. * */
        /** The payer_id is added to the request query parameters * */
        /** when the user is redirected from paypal back to your site * */
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /*         * Execute the payment * */
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later * */
        if ($result->getState() == 'approved') {
            /** it's all right * */
            /** Here Write your database logic like that insert record or value in database if you want * */
            if (Auth::guard('company')->check()) {
                $company = Auth::guard('company')->user();
                $this->updateCompanyPackage($company, $package);
            }
            if (Auth::check()) {
                $user = Auth::user();
                $this->updateJobSeekerPackage($user, $package);
            }

            flash(__('You have successfully subscribed to selected package'))->success();
            return Redirect::route($this->redirectTo);
        }
        flash(__('Subscription failed'));
        return Redirect::route($this->redirectTo);
    }

    public function getPaymentStatus(Request $request, $package_id)
    {
        $package = Package::findOrFail($package_id);
        /*         * ******************************************* */

        /** Get the payment ID before session clear * */
        $payment_id = $request->get('paymentId'); //Session::get('paypal_payment_id');
        /** clear the session payment ID * */
        Session::forget('paypal_payment_id');
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            flash(__('Subscription failed'));
            return Redirect::route($this->redirectTo);
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        /** PaymentExecution object includes information necessary * */
        /** to execute a PayPal account payment. * */
        /** The payer_id is added to the request query parameters * */
        /** when the user is redirected from paypal back to your site * */
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /*         * Execute the payment * */
        $result = $payment->execute($execution, $this->_api_context);
        /** dd($result);exit; /** DEBUG RESULT, remove it later * */
        if ($result->getState() == 'approved') {
            /** it's all right * */
            /** Here Write your database logic like that insert record or value in database if you want * */
            if (Auth::guard('company')->check()) {
                $company = Auth::guard('company')->user();
                $this->addCompanyPackage($company, $package);
            }
            if (Auth::check()) {
                $user = Auth::user();
                $this->addJobSeekerPackage($user, $package);
            }

            flash(__('You have successfully subscribed to selected package'))->success();
            return Redirect::route($this->redirectTo);
        }
        flash(__('Subscription failed'));
        return Redirect::route($this->redirectTo);
    }

    public function orderFreePackage(Request $request, $package_id)
    {
        $package = Package::findOrFail($package_id);
        /*         * ******************************************* */
            /** it's all right * */
            /** Here Write your database logic like that insert record or value in database if you want * */
            if (Auth::guard('company')->check()) {
                $company = Auth::guard('company')->user();
                $this->addCompanyPackage($company, $package);
            }
            if (Auth::check()) {
                $user = Auth::user();
                $this->addJobSeekerPackage($user, $package);
            }

            flash(__('You have successfully subscribed to selected package'))->success();
            return Redirect::route($this->redirectTo);
    }

    public function milestonePaymentAdd()
    {
        return view('job.inc.milestone_payment_add')->with('post_data',session('post_data'));
    }

    public function milestonePaymentSave(Request $request)
    {   
        $package_id = '';
        $hired_user = '';
        $request->buyer_id = '';
        $request->buyer_name = '';
        $request->cost = '';
        $application_id = ''; 

        $rules = [
             'pay_type'=>'required',
              'card_name'=>'required',
              'card_no'=>'required',
              'ccExpiryMonth'=>'required',
              'ccExpiryYear'=>'required',
              'cvvNumber'=>'required'
        ];


        $validation = Validator::make($request->all(),$rules);

        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        else
        {
            if($request->pay_type == 'credit_card'){
            $pay_type = 'credit_card';
            }else if($request->pay_type == 'paypal'){
                $pay_type = 'paypal';
            }else{
                flash(__('Invalid Method'))->error();
                return redirect()->back()->withInput();
            }

            if(Auth::guard('company')->check()){

                $buyer_id = Auth::guard('company')->user()->id;

                $employer_details = Company::where('id',$buyer_id)->first();
                $job_details = Job::where('id',$request->input('job_id'))->first();
                $order_amount=0;
                $order_amount = $request->input('price');
                if($employer_details)
                {
                    $description=$employer_details->name.'('.$buyer_id.') has Fixed the advanced milestone amount for job '.$job_details->title.'('.$job_details->id.')'.' for milestone '.$request->input('milestone_title');

                    if($pay_type == 'credit_card')
                    {
                        if(!isset($request->card_name) || $request->card_name=='' ){
                            flash(__('Card Name is Required'))->error();
                            return redirect()->back()->withInput();
                        }
                        if(!is_numeric($request->ccExpiryMonth) || $request->ccExpiryMonth=='' ){
                            flash(__('Card Month is Required'))->error();
                            return redirect()->back()->withInput();
                        }
                        if(!is_numeric($request->card_no) || $request->card_no=='' ){
                            flash(__('Card Number is Required'))->error();
                            return redirect()->back()->withInput();
                        }
                        if(!is_numeric($request->ccExpiryYear) || $request->ccExpiryYear=='' ){
                            flash(__('Card Expire Year is Required'))->error();
                            return redirect()->back()->withInput();
                        }
                        if(!is_numeric($request->cvvNumber) || $request->cvvNumber==''  ){
                            flash(__('CVV is Required'))->error();
                            return redirect()->back()->withInput();
                        }

                        if(strlen((string)$request->cvvNumber) > 3 || strlen((string)$request->cvvNumber) < 3){
                            flash(__('CVV is Required'))->error();
                            return redirect()->back()->withInput();
                        }

                        if($this->check_cc($request->card_no) !== false){
                            $card_type = $this->check_cc($request->card_no);
                        }else{
                            $card_type = 'Error';
                            flash(__('Card Type is Invalid'))->error();
                            return redirect()->back()->withInput();
                        }

                        $card = new creditCard();
                        $card->setType($card_type)
                            ->setNumber($request->card_no)
                            ->setExpireMonth($request->ccExpiryMonth)
                            ->setExpireYear($request->ccExpiryYear)
                            ->setCvv2($request->cvvNumber)
                            ->setFirstName($request->card_name)
                            ->setLastName($request->card_name);
                        $fi = new fundingInstrument();
                        $fi->setCreditCard($card);

                        $payer = new payer();
                        $payer->setPaymentMethod("credit_card")
                              ->setFundingInstruments([$fi]);
                    }
                    if($pay_type == 'paypal'){
                        $payer = new payer();
                        $payer->setPaymentMethod("paypal");
                    }
                    $item1 = new item();
                    $item1->setName($description)
                            ->setDescription($description)
                            ->setCurrency('USD')
                            ->setQuantity(1)
                            ->setPrice($order_amount);

                    $itemList = new itemList();
                    $itemList->setItems([$item1]);
                    $amount = new amount();
                    $amount->setCurrency("USD")->setTotal($order_amount);
                    $transaction = new transaction();
                    $transaction->setAmount($amount)
                        ->setItemList($itemList)
                        ->setDescription($description)
                        ->setInvoiceNumber(uniqid());
                    $payment = new payment();
                    if($pay_type=='paypal'){
                        $redirect_urls = new RedirectUrls();
                        $redirect_urls->setReturnUrl(URL::route('payment.status', $package_id)) /** Specify return URL * */
                            ->setCancelUrl(URL::route('payment.status', $package_id));
                        $payment->setIntent('Sale')
                            ->setPayer($payer)
                            ->setRedirectUrls($redirect_urls)
                            ->setTransactions([$transaction]);
                    }else{
                        $payment->setIntent("sale")
                        ->setPayer($payer)
                        ->setTransactions([$transaction]);
                    }

                    try {
                        $payment->create($this->_api_context);
                    }catch (\PPConnectionException $ex) {
                        flash($ex->getMessage());
                        return Redirect::route($this->redirectTo);
                    }

                    if($payment->state ==='approved'){
                        $transactions = $payment->getTransactions();
                        $relatedResources = $transactions[0]->getRelatedResources();
                        $sale = $relatedResources[0]->getSale();
                        $saleId = $sale->getId();
                        $invoice_number=$transactions[0]->getInvoiceNumber();

                        
                        $package_id = $request->session()->get('listed_job');
                        $hired_user = $request->input('freelancer');

                        $request->package_id = $package_id;
                        $request->hireduser = $request->input('freelancer');

                        echo $request->package_id; echo "<br>";
                        echo $request->hireduser; 

                        $vals = DB::table('job_apply')
                            ->where('job_id', $package_id)
                            ->where('user_id', $hired_user)
                            ->get();
                        $user = User::findOrFail($hired_user);

                        $package = Job::findOrFail($package_id);

                        echo "<pre>";

                        print_r($user);
                        print_r($package); exit;
                    
                    }
                    else
                    {
                        echo 1;
                    }

                }
                else
                {
                    flash(__('Invalid User 0'))->error();
                    return redirect()->back()->withInput();
                }

            }else{
                flash(__('Invalid User 1'))->error();
                return redirect()->back()->withInput();
            }

        }
        
    }

}
