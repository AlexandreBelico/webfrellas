<?php

Route::get('company-home', 'Company\CompanyController@index')->name('company.home');
Route::get('companies', 'Company\CompaniesController@company_listing')->name('company.listing');
Route::get('company-profile', 'Company\CompanyController@companyProfile')->name('company.profile');
Route::put('update-company-profile', 'Company\CompanyController@updateCompanyProfile')->name('update.company.profile');
Route::get('posted-jobs', 'Company\CompanyController@postedJobs')->name('posted.jobs');
Route::get('company/{slug}', 'Company\CompanyController@companyDetail')->name('company.detail');
Route::post('contact-company-message-send', 'Company\CompanyController@sendContactForm')->name('contact.company.message.send');
Route::post('contact-applicant-message-send', 'Company\CompanyController@sendApplicantContactForm')->name('contact.applicant.message.send');
Route::get('list-applied-users/{job_id}', 'Company\CompanyController@listAppliedUsers')->name('list.applied.users');
Route::get('list-favourite-applied-users/{job_id}', 'Company\CompanyController@listFavouriteAppliedUsers')->name('list.favourite.applied.users');
Route::get('add-to-favourite-applicant/{application_id}/{user_id}/{job_id}/{company_id}', 'Company\CompanyController@addToFavouriteApplicant')->name('add.to.favourite.applicant');
Route::get('remove-from-favourite-applicant/{application_id}/{user_id}/{job_id}/{company_id}', 'Company\CompanyController@removeFromFavouriteApplicant')->name('remove.from.favourite.applicant');
Route::get('applicant-profile/{application_id}', 'Company\CompanyController@applicantProfile')->name('applicant.profile');
Route::get('applicant-profile-job/{application_id}/{job_id}', 'Company\CompanyController@applicantProfile')->name('applicant.profile.job');
Route::get('user-profile/{id}', 'Company\CompanyController@userProfile')->name('user.profile');
Route::get('company-followers', 'Company\CompanyController@companyFollowers')->name('company.followers');

Route::get('payment-management','UserController@getUserPaymentDetails')->name('my.payment_management');

Route::get('delete-payment-management','UserController@deleteUserPaymentDetails')->name('my.delete.paypal.details');


Route::post('paypal-save-details','UserController@saveUserPaymentDetails')->name('paypal.save.detail');

//Start Added By Hetal_
Route::post('hire-candidate-job/{id}','Company\CompanyController@hireCandidateJob')->name('hire.candidate');
//End Added By Hetal_


/* Route::get('company-messages', 'Company\CompanyController@companyMessages')->name('company.messages'); */
Route::post('submit-message-seeker', 'CompanyMessagesController@submitnew_message_seeker')->name('submit-message-seeker');

Route::get('company-messages', 'CompanyMessagesController@all_messages')->name('company.messages');
Route::get('append-messages', 'CompanyMessagesController@append_messages')->name('append-message');
Route::get('append-only-messages', 'CompanyMessagesController@appendonly_messages')->name('append-only-message');
Route::post('company-submit-messages', 'CompanyMessagesController@submit_message')->name('company.submit-message');
Route::get('company-message-detail/{id}', 'Company\CompanyController@companyMessageDetail')->name('company.message.detail');

// =========== Added By Hetal Start =============
Route::get('all-timesheet-details/{id}', 'Company\CompanyController@singleJobTimesheetDetails')->name('timesheet.details');
Route::post('change-timesheet-status', 'Company\CompanyController@changeTimesheetStatus')->name('post.timeline.changestatus');

Route::get('jobs-development-status', 'Company\CompanyController@developmentStatus')->name('jobs.development.status');

Route::get('job-milestone-edit/{id}', 'Company\CompanyController@editMilestone')->name('job.milestone.edit');
Route::post('job-milestone-update', 'Company\CompanyController@updateMilestone')->name('job.milestone.update');
Route::post('job-milestone-delete', 'Company\CompanyController@deleteMilestone')->name('post.deletemilestone');

// =========== Added By Hetal End =============

/* =========== Chat Routes ================ */
Route::get('company-chats', 'CompanyMessagesController@company_chats')->name('companychats.messages');
Route::post('get-user-messages', 'CompanyMessagesController@get_user_messages')->name('get-user-messages');
Route::post('post_user_messages', 'CompanyMessagesController@post_user_messages')->name('post-user-messages');
/* =========== Chat Routes ================ */

/* Routes for file upload with chat */
Route::post('post-companyfileupload', 'CompanyMessagesController@fileupload_action')->name('post-companyfileupload');

Route::post('deletecompanyfile-post', 'CompanyMessagesController@deletefile_action')->name('deletecompanyfile-post');
Route::post('get-user-last-message', 'CompanyMessagesController@get_last_message')->name('get-user-last-message');
//Route::post('feedback', 'FeedbackController@giveFeedback');
Route::post('feedback', 'FeedbackController@giveFeedback');
Route::get('editfeedback/{userId}/{jobId}/{companyId}', 'FeedbackController@editFeedbackById');
Route::put('update_feedback/{userId}/{jobId}/{companyId}', 'FeedbackController@updateFeedback');

/* Routes for file upload with chat */

