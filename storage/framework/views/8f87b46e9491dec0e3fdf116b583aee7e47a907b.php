<?php $__env->startSection('content'); ?>
    <!-- Header start -->
    <?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Header end -->
    <!-- Inner Page Title start -->
    <?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Job Applications')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
            <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="row">
                <?php echo $__env->make('includes.company_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <div class="col-md-9 col-sm-8">
                    <div class="myads">
                        <h3><?php echo e(__('Job Applications')); ?></h3>
                        <ul class="searchList">
                            <!-- job start -->

                            <?php if(isset($job_applications) && count($job_applications)): ?>
                                <?php $__currentLoopData = $job_applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job_application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $user = $job_application->getUser();

                                        $job = $job_application->getJob();

                                        $company = $job->getCompany();

                                    ?>
                                    <?php if($job_application !== null  && $user !== null && $job !== null && $company !== null ): ?>
                                        <li>
                                            <div class="row">
                                                <div class="col-md-5 col-sm-5">
                                                    <div class="jobimg"><?php echo e($user->printUserImage(100, 100)); ?></div>
                                                    <div class="jobinfo">
                                                        <h3>
                                                            <a href="<?php echo e(route('applicant.profile', $job_application->id)); ?>"><?php echo e($user->getName()); ?></a>
                                                        </h3>
                                                        <div class="location"> <?php echo e($user->getLocation()); ?></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>

                                                

                                                <div class="col-md-4 col-sm-4">
                                                    <div class="minsalary"><?php echo e($job_application->expected_salary); ?> <?php echo e($job_application->salary_currency); ?>

                                                        <span>/ <?php echo e($job->getSalaryPeriod('salary_period')); ?></span></div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="listbtn">
                                                        <a href="<?php echo e(route('applicant.profile.job', [$job_application->id, $job->id])); ?>"><?php echo e(__('View Profile')); ?></a>
                                                    </div>
                                                    <input type="hidden" class="form-control" id="feedback-id"
                                                           value=""/>
                                                    <?php if($job_application['isEmployeerContractStatus'] != "" && $job_application['isEmployeerContractStatus'] == "open"): ?>

                                                        <?php if($job_application['isCandidateContractStatus'] == "close"): ?>
                                                            <?php if($job_application['CandidateCloseContract'] >= Carbon\Carbon::now()->subDays(90)): ?>
                                                                <div class="listbtn">
                                                                    <button type="button" class="btn btn-danger btn-block" data-id="<?php echo e($job->id); ?>"
                                                                            data-toggle="modal" data-target="#closeContract--<?php echo e($job->id); ?>"
                                                                            onclick="addFeedback(<?php echo e($job->id); ?>)">
                                                                        Close Contract
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <div class="listbtn">
                                                                <button type="button" class="btn btn-danger btn-block" data-id="<?php echo e($job->id); ?>"
                                                                        data-toggle="modal" data-target="#closeContract--<?php echo e($job->id); ?>"
                                                                        onclick="addFeedback(<?php echo e($job->id); ?>)">
                                                                    Close Contract
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php elseif($job_application['isEmployeerContractStatus'] == "close"  && $job_application['isCandidateContractStatus'] == "open"): ?>


                                                        <?php if($job_application['EmployerCloseContract'] >= Carbon\Carbon::now()->subDays(90)): ?>
                                                            <div class="listbtn">
                                                                <button type="button" class="btn btn-success btn-block"
                                                                        data-toggle="modal" data-target="#closeContract"
                                                                        onclick="editFeedback(<?php echo e($user->id); ?>, <?php echo e($job->id); ?>,<?php echo e($company->id); ?>)">
                                                                    Update Review
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <!-- Modal -->
                                                    <div class="modal fade" id="closeContract--<?php echo e($job->id); ?>" tabindex="-1"
                                                         role="dialog" aria-labelledby="closeContractLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h5 class="modal-title"
                                                                        id="closeContractLabel--<?php echo e($job->id); ?>">
                                                                        Give Review</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="form-closeContract"
                                                                          name="form-closeContract">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label style="margin-bottom: 10px;">
                                                                                        <b>Rating:</b>
                                                                                    </label>
                                                                                    <div id="rateYo--<?php echo e($job->id); ?>"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12">
                                                                                <div class="form-group">
                                                                                    <label style="margin-bottom: 10px;">
                                                                                        <b>Give Feedback:</b>
                                                                                    </label>
                                                                                    <textarea class="form-control" id="feedback--<?php echo e($job->id); ?>"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <input type="hidden" id="userName--<?php echo e($job->id); ?>"
                                                                           value="<?php echo e($user->name); ?>">
                                                                    <input type="hidden" id="userEmail--<?php echo e($job->id); ?>"
                                                                           value="<?php echo e($user->email); ?>">
                                                                    <button type="button" class="btn btn-primary"
                                                                            onclick="saveReview(<?php echo e($user->id); ?>,
                                                                            <?php echo e($job->id); ?>,<?php echo e($company->id); ?>)">
                                                                        Give Review
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p><?php echo e(str_limit($user->getProfileSummary('summary'),150,'...')); ?></p>
                                        </li>
                                        <!-- job end -->
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        var rating;

        function addFeedback(jobId) {
            $("#rateYo--"+jobId).rateYo({
                rating: 1,
                halfStar: true,
                onSet: function (r, rateYoInstance) {
                    rating = r;
                }
            });
        }

        function saveReview(userId, jobId, companyId) {
            var feedBackId = $('#feedback-id').val();
            var userName = $('#userName--'+jobId).val();
            var userEmail = $('#userEmail--'+jobId).val();
            var feedback = $('#feedback--'+jobId).val();
            var type = 'employer';

            if (feedBackId == "") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "<?php echo e(\Illuminate\Support\Facades\URL::to('feedback')); ?>",
                    type: "POST",
                    data: {
                        'user_id': userId,
                        'job_id': jobId,
                        'company_id': companyId,
                        'rating': rating,
                        'feedback': feedback,
                        'type': type,
                        'userName': userName,
                        'userEmail': userEmail
                    },
                    success: function (response) {
                        // location.reload();
                    },
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "<?php echo e(\Illuminate\Support\Facades\URL::to('update_feedback')); ?>" + '/' + userId + '/' + jobId + '/' + companyId,
                    type: "PUT",
                    data: {
                        'user_id': userId,
                        'job_id': jobId,
                        'company_id': companyId,
                        'rating': rating,
                        'feedback': feedback,
                        'type': type,
                        'userName': userName,
                        'userEmail': userEmail
                    },
                    success: function (response) {
                        // location.reload();
                    },
                });
            }
        }

        function editFeedback(userId, jobId, companyId) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(\Illuminate\Support\Facades\URL::to('editfeedback')); ?>" + '/' + userId + '/' + jobId + '/' + companyId,
                type: "GET",
                success: function (response) {
                    $('#closeContract--'+jobId).modal("show");
                    var feedback = $('#feedback--'+jobId).val(response.feedback.feedback);
                    var feedbackId = $('#feedback-id').val(response.feedback.id);
                    rating = response.feedback.rating;
                    $("#rateYo--"+jobId).rateYo({
                        rating: rating,
                        halfStar: true,
                        onSet: function (r, rateYoInstance) {
                            rating = r;
                        }
                    });
                },
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>