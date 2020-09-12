<?php

/* * ******** OrderController ************ */
Route::get('order-free-package/{id}', 'OrderController@orderFreePackage')->name('order.free.package');

Route::get('order-form/{id}/{new_or_upgrade}', 'OrderController@orderForm')->name('order.form');
Route::post('order-package', 'OrderController@orderPackage')->name('order.package');
Route::post('pay-fee', 'OrderController@orderPayment')->name('pay.fee');
Route::post('order-upgrade-package', 'OrderController@orderUpgradePackage')->name('order.upgrade.package');
Route::get('paypal-payment-status/{id}', 'OrderController@getPaymentStatus')->name('payment.status');
Route::get('paypal-upgrade-payment-status/{id}', 'OrderController@getUpgradePaymentStatus')->name('upgrade.payment.status');
Route::get('stripe-order-form/{id}/{new_or_upgrade}', 'StripeOrderController@stripeOrderForm')->name('stripe.order.form');
Route::post('stripe-order-package', 'StripeOrderController@stripeOrderPackage')->name('stripe.order.package');
Route::post('stripe-order-upgrade-package', 'StripeOrderController@stripeOrderUpgradePackage')->name('stripe.order.upgrade.package');
Route::get('milestone-payment-add','OrderController@milestonePaymentAdd')->name('milestone.payment.add');
Route::post('milestone-payment-save','OrderController@milestonePaymentSave')->name('milestone.payment.save');
Route::get('weeklypayment','OrderController@weeklyPayment');
Route::get('jobpayment/{slug}', 'Job\JobController@singleJobpaymentDetail')->name('job.singlejobpaymentdetail');

Route::get('approval-link/{id}', 'OrderController@approvalLink')->name('payment.approvallink');