<?php
if (!isset($seo)) {
    $seo = (object)array('seo_title' => $siteSetting->site_name, 'seo_description' => $siteSetting->site_name, 'seo_keywords' => $siteSetting->site_name, 'seo_other' => '');
}
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" class="<?php echo e((session('localeDir', 'ltr'))); ?>" dir="<?php echo e((session('localeDir', 'ltr'))); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(__($seo->seo_title)); ?></title>
    <meta name="Description" content="<?php echo $seo->seo_description; ?>">
    <meta name="Keywords" content="<?php echo $seo->seo_keywords; ?>">
    <?php echo $seo->seo_other; ?>

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="<?php echo e(asset('/public/favicon.ico')); ?>">
    <!-- Slider -->
    <link href="<?php echo e(asset('/public/js/revolution-slider/css/settings.css')); ?>" rel="stylesheet">
    <!-- Owl carousel -->
    <link href="<?php echo e(asset('/public/css/owl.carousel.css')); ?>" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo e(asset('/public/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo e(asset('/public/css/font-awesome.css')); ?>" rel="stylesheet">
    <!-- Custom Style -->
    <link href="<?php echo e(asset('/public/css/main.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('/public/css/chat.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <?php if((session('localeDir', 'ltr') == 'rtl')): ?>
    <!-- Rtl Style -->
    <link href="<?php echo e(asset('/public/css/rtl-style.css')); ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?php echo e(asset('/public/admin_assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('/public/admin_assets/global/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('/public/admin_assets/global/plugins/select2/css/select2-bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-notifications@1.0.3/dist/stylesheets/bootstrap-notifications.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="<?php echo e(asset('/')); ?>js/html5shiv.min.js"></script>
          <script src="<?php echo e(asset('/')); ?>js/respond.min.js"></script>
        <![endif]-->
    <style type="text/css">
        .milestonedescription { text-align: left !important; color: #666 !important;}
        .milestoneobjects {list-style-type: none; margin-bottom: 10px !important;}
        .moredescription { display: none; }
        .display_submit_message { margin-top: 10px; }
        .boldtitle { font-weight: 600;  }
        .jobdescription strong { font-weight: 600 !important;  }
        .jobdescription em { font-style: italic !important;  }
        .milestonelisttitle { font-size: 18px; font-weight: 600;color: #00a8ff;  }
    </style>

    <style>
        .jq-ry-container{
            padding: 1px !important;
            margin: 0;
        }
        .job-display {
            background: transparent;
            position: fixed;
            left: 20px;
            bottom: 20px;
            width: 300px;
            z-index: 1000;
        }
        .close-job {
            border-radius: 50%;
            border: 1px solid #8b96a4;
            position: absolute;
            right: -3%;
            top: -13%;
        }
        .close-job:active {
            border: 1px solid #8b96a4;
        }
        .notify {
            background: #8b96a4;
            margin: 15px 0;
            padding: 10px 5px;
            border: 1px solid #2b4a5c;
            position: relative;
        }
        .dropdown-container{
            left:-215px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <?php if(Auth::check() && !Auth::guard('company')->check()): ?>
        <section class="job-display">
            <ul class="job-list" id="job-list">
                
            </ul>
        </section>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
    <!-- Bootstrap's JavaScript -->
    <script src="<?php echo e(asset('/public/js/jquery-2.1.4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/public/js/bootstrap.min.js')); ?>"></script>
    <!-- Owl carousel -->
    <script src="<?php echo e(asset('/public/js/owl.carousel.js')); ?>"></script>
    <script src="<?php echo e(asset('/public/admin_assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('/public/admin_assets/global/plugins/Bootstrap-3-Typeahead/bootstrap3-typeahead.min.js')); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo e(asset('/public/js/chat.js')); ?>"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="<?php echo e(asset('/public/admin_assets/global/plugins/select2/js/select2.full.min.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('/public/admin_assets/global/plugins/jquery.scrollTo.min.js')); ?>" type="text/javascript"></script>
    <!-- Revolution Slider -->
    <script type="text/javascript" src="<?php echo e(asset('/public/js/revolution-slider/js/jquery.themepunch.tools.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/public/js/revolution-slider/js/jquery.themepunch.revolution.min.js')); ?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <!--Begin:Pusher Notification--->
    <!-- <script src="https://js.pusher.com/5.1/pusher.min.js"></script> -->
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
    <!--End:Pusher Notification--->
    <script>
        CKEDITOR.replace('summary-ckeditor',
            {
                entities: false,
                basicEntities: false,
                entities_greek: false,
                entities_latin: false,
            });
    </script>
    <?php echo NoCaptcha::renderJs(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <!-- Custom js -->
    <script src="<?php echo e(asset('/public/js/script.js')); ?>"></script>
    <script type="text/JavaScript">
        $(document).ready(function(){
            $('#startdate').datepicker();
            $('#enddate').datepicker();
            $('#whichdate').datepicker("setDate", new Date());

            $(document).scrollTo('.has-error', 2000);
            });
            function showProcessingForm(btn_id){
            $("#"+btn_id).val( 'Processing .....' );
            $("#"+btn_id).attr('disabled','disabled');
        }

        function readmore(milestoneId) {
             var dots = document.getElementById("dots_"+milestoneId);
             var moreText = document.getElementById("moredescription_"+milestoneId);
             var btnText = document.getElementById("readmorebtn_"+milestoneId);

            if (dots.style.display === "none") {
                dots.style.display = "inline";
                btnText.innerHTML = "Read more";
                moreText.style.display = "none";
            } else {
                dots.style.display = "none";
                btnText.innerHTML = "Read less";
                moreText.style.display = "inline";
            }
        }

        function Submitmilestone(milestoneId) {
            $('.submitmilestoneId').val(milestoneId);
            $('#Submitmilestonemodal').modal('show');
        }

        function getClientJobslist(clientId) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
            $.ajax({
            method: 'POST',
            url: '<?php echo e(route("job.gethiredjobslist")); ?>',
            data: {'clientId' : clientId},
            success: function(response){
                $('#jobsofclient').html('');
                if(response!=0){
                   var response = JSON.parse(response);
                   $('#jobsofclient').html('<option>Select Job</option>');
                   $.each(response, function(index, value){
                        $('#jobsofclient').append('<option value="'+value.id+'">'+value.title+'</option>');
                    });
                } else {
                     $('#jobsofclient').html('<option>No Jobs found</option>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
        }

        function getMilestonesList(jobId){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
            $.ajax({
            method: 'POST',
            url: '<?php echo e(route("milestone.list")); ?>',
            data: {'jobId' : jobId},
            success: function(response){
                    $('#milestonesofclient').html('');
                if(response!=0){
                    response = JSON.parse(response);
                    var i= 0 ;
                    $.each(response, function(index, value){
                        i = parseInt(i) + parseInt(1);
                        $('#milestonesofclient').append('<option value="'+value.id+'">'+value.milestone_title+'</option>');
                    });
                } else {
                    $('#milestonesofclient').html('<option value="">No milestones found</option>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
          });
        }

        function verifywork(milestoneId) {

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
            $.ajax({
            method: 'POST',
            url: '<?php echo e(route("milestone.verifywork")); ?>',
            data: {'milestoneId' : milestoneId},
            success: function(response){
                var response = JSON.parse(response);
                if(response[0].submit_message!=null){
                    var submit_message = response[0].submit_message;
                } else {
                    var submit_message = 'No details found!!!';
                }

                var milestoneId = response[0].id;

                $('.display_submit_message').text(submit_message);
                $('.completemilestoneId').val(milestoneId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

            $('#verifyworkmodal').modal('show');
        }

        function changeTimesheetStatus(status, timesheetId) {
            $('.timesheetid').val(timesheetId);
            $('.changedstatusvalue').val(status);
            $('#changetimesheetstatusmodal').modal('show');
        }

        function deleteMilestone(milestoneId) {
            $('.deleteMilestoneId').val(milestoneId);
            $('#deleteMilestoneModal').modal('show');
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////
        //Begin : For Web based notification with pusher
        ///////////////////////////////////////////////////////////////////////////////////////////////
        var notificationsWrapper = $('.dropdown-notifications');
        var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount = parseInt(notificationsCountElem.data('count'));
        var notifications = notificationsWrapper.find('ul.dropdown-menu');
        /*if (notificationsCount <= 0) {
            notificationsWrapper.hide();
        }*/
        var notifypusher = new Pusher('d0c7990bbda7deb1300c', {
            cluster: 'ap2',
            encrypted: false
        });
        // Subscribe to the channel we specified in our Laravel Event
        var notifyChannel = notifypusher.subscribe('job-apply-event');
        console.log("notifications testing");
        // Bind a function to a Event (the full Laravel class)
        notifyChannel.bind('App\\Events\\JobApplyEvent', function(data) {
            console.log("in"); console.log(data); console.log("#");
            var existingNotifications = notifications.html();
            var custom_link = window.location.origin+`/view-public-profile/`+data.send_user_id;
            var profile_link = '';
            if(data.send_user_profile_pic != 0 ){
                profile_link = window.location.origin+`/user_images/`+data.send_user_profile_pic;
            }else{
                profile_link = ``;
            }
            var newNotificationHtml = `
                <li class="notification active">
                    <div class="media" onclick="`+custom_link+`" style="cursor:pointer;">
                      <div class="media-left">
                        <div class="media-object">
                          <img src="`+profile_link+`" class="img-circle" alt="image" style="width: 50px; height: 50px;">
                        </div>
                      </div>
                      <div class="media-body">
                        <strong class="notification-title">` + data.message + `</strong>
                        <div class="notification-meta">
                          <small class="timestamp">about a minute ago</small>
                        </div>
                      </div>
                    </div>
                </li>
              `;
              notifications.html(newNotificationHtml + existingNotifications);
              notificationsCount += 1;
              notificationsCountElem.attr('data-count', notificationsCount);
              notificationsWrapper.find('.notif-count').text(notificationsCount);
              notificationsWrapper.show();
          });

            /*if(notification_user_id !='' && notification_msg!=''){
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
                $.ajax({
                    method: 'POST',
                    url: '<?php echo e(route("post.notifications.details")); ?>',
                    data: {'user_id' : notification_user_id, 'message' : notification_msg}, 
                    success: function(response){ 
                        console.log(response);  
                        //alert(response);  
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            }*/
        ///////////////////////////////////////////////////////////////////////////////////////////////
        //End : For Web based notification with pusher
        ///////////////////////////////////////////////////////////////////////////////////////////////
    </script>
</body>

</html>