<?php $__env->startSection('content'); ?>
    <!-- Header start -->
    <?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Header end -->
    <!-- Inner Page Title start -->
    <?php echo $__env->make('includes.inner_page_title', ['page_title'=>__($page_title)], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <!-- Job Header start -->
            <div class="job-header">
                <div class="jobinfo">
                    <div class="row">
                        <div class="col-md-8 col-sm-8">
                            <!-- Candidate Info -->
                            <div class="candidateinfo">
                                <div class="userPic"><?php echo e($user->printUserImage()); ?></div>
                                <div class="title">
                                    <?php echo e($user->getName()); ?>

                                    <?php if((bool)$user->is_immediate_available): ?>
                                        <sup style="font-size:12px; color:#090;"><?php echo e(__('Immediate Available For Work')); ?></sup>
                                    <?php endif; ?>
                                </div>
                                <div class="desi"><?php echo e($user->getLocation()); ?></div>
                                <div class="loctext"><i class="fa fa-history"
                                                        aria-hidden="true"></i> <?php echo e(__('Member Since')); ?>

                                    , <?php echo e($user->created_at->format('M d, Y')); ?></div>
                                <div class="loctext"><i class="fa fa-map-marker"
                                                        aria-hidden="true"></i> <?php echo e($user->street_address); ?></div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <!-- Candidate Contact -->

                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="jobButtons">
                    <?php if(isset($job) && isset($company)): ?>
                        <?php if(Auth::guard('company')->check() && Auth::guard('company')->user()->isFavouriteApplicant($user->id, $job->id, $company->id)): ?>
                            <a href="<?php echo e(route('remove.from.favourite.applicant', [$job_application->id, $user->id, $job->id, $company->id])); ?>"
                               class="btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo e(__('Hired Applicant')); ?>

                            </a>
                        <?php else: ?>
                            <button onclick="hire_freelancer()" class="btn"><i class="fa fa-floppy-o"
                                                                               aria-hidden="true"></i> <?php echo e(__('Hire This Applicant')); ?>

                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(Request::segment(3) > 0): ?>
                        <a href="javascript:;" onclick="send_message()" class="btn"><i class="fa fa-envelope"
                                                                                       aria-hidden="true"></i> <?php echo e(__('Send Message')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Detail start -->
            <div class="row">
                <div class="col-md-8">
                    <!-- About Employee start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3><?php echo e(__('About me')); ?></h3>
                            <p><?php echo e($user->getProfileSummary('summary')); ?></p>
                        </div>
                    </div>

                    <!-- Education start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3><?php echo e(__('Education')); ?></h3>
                            <div class="" id="education_div"></div>
                        </div>
                    </div>

                    <!-- Experience start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3><?php echo e(__('Experience')); ?></h3>
                            <div class="" id="experience_div"></div>
                        </div>
                    </div>

                    <!-- Portfolio start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3><?php echo e(__('Portfolio')); ?></h3>
                            <div class="" id="projects_div"></div>
                        </div>
                    </div>

                    <!-- Portfolio start -->
                    <div class="job-header">
                        <div class="contentbox">
                            <h3 style="padding-bottom: 20px;"><?php echo e(__('Work history and feedback')); ?></h3>
                            <div class="" id="projects_div">
                                <?php $__currentLoopData = $projectFeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($o->jobApply['isCandidateContractStatus'] == "close" && $o->jobApply['isEmployeerContractStatus'] == "close"): ?>
                                        <div class="project-review">
                                            <h4><?php echo e($o->jobDetails->title); ?></h4>
                                            <div class="rating">
                                                <div class="row">
                                                    <div class="col-lg-1">
                                                        <div class="rateyo" data-rateyo-rating="<?php echo e($o->rating); ?>"
                                                             data-rateyo-num-stars="5" data-rateyo-score="3">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                    <span style="padding: 0;margin-top: -5px; margin-right: 3px; font-weight: bold;">
                                                        <?php if(strpos($o->rating, ".")): ?>
                                                            <?php echo e($o->rating); ?>0
                                                        <?php else: ?>
                                                            <?php echo e($o->rating); ?>.00
                                                        <?php endif; ?>
                                                    </span>
                                                        <span><?php echo e(\Carbon\Carbon::parse($o->created_at)->format('M Y')); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p style="padding: 5px 0;">
                                                <?php echo e($o->feedback); ?>

                                            </p>
                                        </div>
                                        <hr>
                                    <?php elseif($o->jobApply['isCandidateContractStatus'] == "close"
                                        && $o->jobApply['isEmployeerContractStatus'] == "open" ||
                                         $o->jobApply['isCandidateContractStatus'] == "open"
                                        && $o->jobApply['isEmployeerContractStatus'] == "close"): ?>

                                        <?php if($o->jobApply['EmployerCloseContract'] <= Carbon\Carbon::now()->subDays(90)): ?>
                                            <div class="project-review">
                                                <h4><?php echo e($o->jobDetails->title); ?></h4>
                                                <div class="rating">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <span><?php echo e(\Carbon\Carbon::parse($o->created_at)->format('M Y')); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p style="padding: 5px 0;">
                                                    No feedback given
                                                </p>
                                            </div>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Candidate Detail start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3><?php echo e(__('Candidate Detail')); ?></h3>
                            <ul class="jbdetail">

                                <li class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Is Email Verified')); ?></div>
                                    <div class="col-md-6 col-xs-6"><span><?php echo e(((bool)$user->verified)? 'Yes':'No'); ?></span>
                                    </div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Immediate Available')); ?></div>
                                    <div class="col-md-6 col-xs-6">
                                        <span><?php echo e(((bool)$user->is_immediate_available)? 'Yes':'No'); ?></span></div>
                                </li>

                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Age')); ?></div>
                                    <div class="col-md-6 col-xs-6"><span><?php echo e($user->getAge()); ?> Years</span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Gender')); ?></div>
                                    <div class="col-md-6 col-xs-6"><span><?php echo e($user->getGender('gender')); ?></span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Marital Status')); ?></div>
                                    <div class="col-md-6 col-xs-6">
                                        <span><?php echo e($user->getMaritalStatus('marital_status')); ?></span></div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Experience')); ?></div>
                                    <div class="col-md-6 col-xs-6">
                                        <span><?php echo e($user->getJobExperience('job_experience')); ?></span></div>
                                </li>
                                <li class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Career Level')); ?></div>
                                    <div class="col-md-6 col-xs-6">
                                        <span><?php echo e($user->getCareerLevel('career_level')); ?></span></div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Current Salary')); ?></div>
                                    <div class="col-md-6 col-xs-6"><span
                                                class="permanent"><?php echo e($user->current_salary); ?> <?php echo e($user->salary_currency); ?></span>
                                    </div>
                                </li>
                                <li style="display:none;" class="row">
                                    <div class="col-md-6 col-xs-6"><?php echo e(__('Expected Salary')); ?></div>
                                    <div class="col-md-6 col-xs-6"><span
                                                class="freelance"><?php echo e($user->expected_salary); ?> <?php echo e($user->salary_currency); ?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Google Map start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3><?php echo e(__('Skills')); ?></h3>
                            <div id="skill_div"></div>
                        </div>
                    </div>

                    <div class="job-header">
                        <div class="jobdetail">
                            <h3><?php echo e(__('Languages')); ?></h3>
                            <div id="language_div"></div>
                        </div>
                    </div>
                    <!-- Contact Company start -->
                    <div class="job-header">
                        <div class="jobdetail">
                            <h3 id="contact_applicant"><?php echo e(__($form_title)); ?></h3>
                            <div id="alert_messages"></div>
                            <?php
                            $from_name = $from_email = $from_phone = $subject = $message = $from_id = '';
                            if (isset($company)) {
                                $from_name = $company->name;
                                $from_email = $company->email;
                                $from_phone = $company->phone;
                                $from_id = $company->id;
                            }
                            if (Auth::guard('company')->check()) {
                                $from_name = Auth::guard('company')->user()->name;
                                $from_email = Auth::guard('company')->user()->email;
                                $from_phone = Auth::guard('company')->user()->phone;
                                $from_id = Auth::guard('company')->user()->id;
                            }
                            $from_name = old('name', $from_name);
                            $from_email = old('email', $from_email);
                            $from_phone = old('phone', $from_phone);
                            $subject = old('subject');
                            $message = old('message');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hireFreelancer" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <?php echo $__env->make('order.pay_with_custom_paypal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="sendmessage" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="" id="send-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="seeker_id" id="seeker_id" value="<?php echo e($user->id); ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control" name="message" id="message" cols="10" rows="7"></textarea>
                            <input type="hidden" name="job_id" value="<?php echo e(Request::segment(3)); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
    <style type="text/css">
        .formrow iframe {
            height: 78px;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
    <script type="text/javascript">
        $(document).ready(function () {
            /*$("#rateYo").rateYo({
                rating: 1,
                readOnly: true,
                onSet: function (r, rateYoInstance) {
                    rating = r;
                }
            });*/
            $(function () {
                $(".rateyo").rateYo({
                    readOnly: true,
                    starWidth: "10px",
                }).on("rateyo.change", function (e, data) {
                    var rating = data.rating;
                });
            });
            $(document).on('click', '#send_applicant_message', function () {
                var postData = $('#send-applicant-message-form').serialize();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('contact.applicant.message.send')); ?>",
                    data: postData,
                    //dataType: 'json',
                    success: function (data) {
                        response = JSON.parse(data);
                        var res = response.success;
                        if (res == 'success') {
                            var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
                            $('#alert_messages').html(errorString);
                            $('#send-applicant-message-form').hide('slow');
                            $(document).scrollTo('.alert', 2000);
                        } else {
                            var errorString = '<div class="alert alert-danger" role="alert"><ul>';
                            response = JSON.parse(data);
                            $.each(response, function (index, value) {
                                errorString += '<li>' + value + '</li>';
                            });
                            errorString += '</ul></div>';
                            $('#alert_messages').html(errorString);
                            $(document).scrollTo('.alert', 2000);
                        }
                    },
                });
            });
            showEducation();
            showProjects();
            showExperience();
            showSkills();
            showLanguages();
        });

        function showProjects() {
            $.post("<?php echo e(route('show.applicant.profile.projects', $user->id)); ?>", {
                user_id: <?php echo e($user->id); ?>,
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>'
            })
                .done(function (response) {
                    $('#projects_div').html(response);
                });
        }

        function showExperience() {
            $.post("<?php echo e(route('show.applicant.profile.experience', $user->id)); ?>", {
                user_id: <?php echo e($user->id); ?>,
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>'
            })
                .done(function (response) {
                    $('#experience_div').html(response);
                });
        }

        function showEducation() {
            $.post("<?php echo e(route('show.applicant.profile.education', $user->id)); ?>", {
                user_id: <?php echo e($user->id); ?>,
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>'
            })
                .done(function (response) {
                    $('#education_div').html(response);
                });
        }

        function showLanguages() {
            $.post("<?php echo e(route('show.applicant.profile.languages', $user->id)); ?>", {
                user_id: <?php echo e($user->id); ?>,
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>'
            })
                .done(function (response) {
                    $('#language_div').html(response);
                });
        }

        function showSkills() {
            $.post("<?php echo e(route('show.applicant.profile.skills', $user->id)); ?>", {
                user_id: <?php echo e($user->id); ?>,
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>'
            })
                .done(function (response) {
                    $('#skill_div').html(response);
                });
        }

        function hire_freelancer() {
            const el = document.createElement('div')
            el.innerHTML = "Please <a class='btn' href='<?php echo e(route('login')); ?>' onclick='set_session()'>log in</a> as a Employer and try again."
            <?php if(null!==(Auth::guard('company')->user())): ?>

               <?php if($job->job_rate_type==0): ?>
               // swal({
               //    title: 'Are you sure?',
               //    text: "Are you sure want to hire this candidate ?",
               //    icon: 'warning',
               //    showCancelButton: true,
               //    showConfirmButton:true,
               //    confirmButtonText: 'Yes, delete it!'
               //  }).then((result) => {
               //    if (result.value) {
               //      swal(
               //        'Successfully Hired!',
               //        'You have hired this candidate for our job',
               //        'success'
               //      )
               //    }
               //  })
               if(confirm('Are you sure hire this candidate for Job ?'))
               {
                    $.post("<?php echo e(route('hire.candidate', $job_application->id)); ?>", {
                        job_application_id: <?php echo e($job_application->id); ?>,
                        job_candidate_id:<?php echo e($user->id); ?>,
                        job_employee_id:<?php echo e(Auth::guard('company')->user()->id); ?>,
                        _method: 'POST',
                        _token: '<?php echo e(csrf_token()); ?>'
                    }).done(function (response) {
                        if(response)
                        {
                            window.location.reload();
                            location.reload();
                        }
                    });
                }
               
               <?php elseif($job->job_rate_type==1): ?>
                 $('#hireFreelancer').modal('show');
               <?php else: ?>
                 swal({
                    title: "Job Rate type is not selected for this job. Please select",
                    content: el,
                    icon: "error",
                    button: "OK",
                });
               <?php endif; ?>  
               
            <?php else: ?>
            swal({
                title: "You are not Loged in",
                content: el,
                icon: "error",
                button: "OK",
            });
            <?php endif; ?>
        }

        function send_message() {
            const el = document.createElement('div')
            el.innerHTML = "Please <a class='btn' href='<?php echo e(route('login')); ?>' onclick='set_session()'>log in</a> as a Employer and try again."
            <?php if(null!==(Auth::guard('company')->user())): ?>
            $('#sendmessage').modal('show');
            <?php else: ?>
            swal({
                title: "You are not Loged in",
                content: el,
                icon: "error",
                button: "OK",
            });
            <?php endif; ?>
        }

        if ($("#send-form").length > 0) {
            $("#send-form").validate({
                validateHiddenInputs: true,
                ignore: "",

                rules: {
                    message: {
                        required: true,
                        maxlength: 5000
                    },
                },
                messages: {

                    message: {
                        required: "Message is required",
                    }

                },
                submitHandler: function (form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    <?php if(null !== (Auth::guard('company')->user())): ?>
                    $.ajax({
                        url: "<?php echo e(route('submit-message-seeker')); ?>",
                        type: "POST",
                        data: $('#send-form').serialize(),
                        success: function (response) {
                            $("#send-form").trigger("reset");
                            $('#sendmessage').modal('hide');
                            swal({
                                title: "Success",
                                text: response["msg"],
                                icon: "success",
                                button: "OK",
                            });
                        }
                    });
                    <?php endif; ?>
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>