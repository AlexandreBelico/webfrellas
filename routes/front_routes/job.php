<?php
Route::get('job/{slug}', 'Job\JobController@jobDetail')->name('job.detail');
Route::get('apply/{slug}', 'Job\JobController@applyJob')->name('apply.job');
Route::post('apply/{slug}', 'Job\JobController@postApplyJob')->name('post.apply.job');
Route::get('jobs', 'Job\JobController@jobsBySearch')->name('job.list');
Route::get('add-to-favourite-job/{job_slug}', 'Job\JobController@addToFavouriteJob')->name('add.to.favourite');
Route::get('remove-from-favourite-job/{job_slug}', 'Job\JobController@removeFromFavouriteJob')->name('remove.from.favourite');
Route::get('my-job-applications', 'Job\JobController@myJobApplications')->name('my.job.applications');
Route::get('my-favourite-jobs', 'Job\JobController@myFavouriteJobs')->name('my.favourite.jobs');
Route::get('post-job', 'Job\JobPublishController@createFrontJob')->name('post.job');
Route::post('store-front-job', 'Job\JobPublishController@storeFrontJob')->name('store.front.job');
Route::get('edit-front-job/{id}', 'Job\JobPublishController@editFrontJob')->name('edit.front.job');
Route::put('update-front-job/{id}', 'Job\JobPublishController@updateFrontJob')->name('update.front.job');
Route::delete('delete-front-job', 'Job\JobPublishController@deleteJob')->name('delete.front.job');
Route::get('job-seekers', 'Job\JobSeekerController@jobSeekersBySearch')->name('job.seeker.list');
Route::get('notification', 'Job\NotificationController@notificationPage')->name('notification.list');
Route::post('notification/load_notification', 'Job\NotificationController@getNotificationList')->name('notification.load_notification');
Route::get('pusher-notification', 'PusherNotificationController@sendNotification');


Route::post('submit-message', 'Job\SeekerSendController@submit_message')->name('submit-message');

Route::get('create-milestone-front-job/{id}', 'Job\JobPublishController@create_milestones')->name('milestones.front.job'); // Added by hetal

// =========== Added By Hetal Start =============
Route::get('milestones-list/{slug}', 'Job\JobPublishController@list_milestones')->name('milestones.list');
Route::post('submit-milestone/{slug}', 'Job\JobPublishController@postMilestone')->name('post.milestone.job'); 
Route::post('submit-milestone-as-complete', 'Job\JobController@submitMilestone')->name('post.submitmilestone');
Route::get('change-milestone-status/{id}/{id1}', 'Job\JobController@changestatus')->name('milestone.changestatus');
Route::post('verify-milestone-work', 'Job\JobPublishController@verifywork')->name('milestone.verifywork');
Route::post('complete-milestone-work', 'Job\JobPublishController@completemilestone')->name('post.completemilestone');
Route::get('get-timesheet-details', 'Job\JobController@getTimesheetDetails')->name('job.timesheets');
Route::get('add-timesheet', 'Job\JobController@addTimesheet')->name('post.addtimesheet');
Route::post('hired-client-jobs', 'Job\JobController@gethiredjobslist')->name('job.gethiredjobslist');
Route::post('single-job-milestone-list', 'Job\JobController@milestonesList')->name('milestone.list');
Route::post('submit-timeline-details', 'Job\JobController@submitTimeline')->name('post.milestone.timeline');
Route::get('single-job-timesheet-details/{id}', 'Job\JobController@singleJobTimesheetDetails')->name('single.job.timesheet');
Route::get('jobs-development-status-details', 'Job\JobController@developmentStatus')->name('jobs.developmentstatus');

Route::get('weekly-timesheet', 'Job\JobController@timesheetWeeklyReport');
Route::get('payment-details', 'Job\JobController@paymentdetails')->name('job.paymentdetails');

// =========== Added By Hetal Start =============

Route::post('submit-notifications-details', 'Job\JobController@submitNotificationsDetails')->name('post.notifications.details');

Route::get('apply1/test', function () {
	 
	/*$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'front_routes' . DIRECTORY_SEPARATOR;
	echo  realpath(__DIR__);*/
	/*$user_id = 18; 
	$username = 'test';
	//echo URL::to('/view-public-profile/'.$user_id);
	 $user_profile_url = URL::to('/view-public-profile/'.$user_id);
        $link = "<a href=".$user_profile_url.">".$username."</a>";
        echo $link;*/
   //$link = "<a href='{{ URL::to('/view-public-profile'.$user_id) }}'>{$username}</a>";
    //echo $link;
});

Route::prefix('admin123')->group(function () {
    Route::get('users', function () {
        echo "tested";
    });
});

Route::get('apply1/1test', function () {
    event(new App\Events\JobApplyEvent('Guest','18','test.jpg'));
    return "Event has been sent!";
});
