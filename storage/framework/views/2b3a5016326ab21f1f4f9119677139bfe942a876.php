<?php $__env->startPush('styles'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Header start -->
    <?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Header end -->
    <!-- Inner Page Title start -->
    <?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Applied Jobs')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container">
            <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="row">
                <?php echo $__env->make('includes.user_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <div class="col-md-9 col-sm-8">
                    <div class="myads">
                        <h3><?php echo e(__('Applied Jobs')); ?></h3>
                        <ul class="searchList">
                            <!-- job start -->
                            <?php if(isset($jobs) && count($jobs)): ?>
                                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $company = $job->getCompany(); ?>
                                    <?php if(null !== $company): ?>
                                        <li>
                                            <div class="row">
                                                <div class="col-md-8 col-sm-8">
                                                    <div class="jobimg"><?php echo e($company->printCompanyImage()); ?></div>
                                                    <div class="jobinfo">
                                                        <h3><a href="<?php echo e(route('job.detail', [$job->slug])); ?>"
                                                               title="<?php echo e($job->title); ?>"><?php echo e($job->title); ?></a></h3>
                                                        <div class="companyName"><a
                                                                    href="<?php echo e(route('company.detail', $company->slug)); ?>"
                                                                    title="<?php echo e($company->name); ?>"><?php echo e($company->name); ?></a>
                                                        </div>
                                                        <div class="location">
                                                            <label class="fulltime"
                                                                   title="<?php echo e($job->getJobShift('job_shift')); ?>">
                                                                <?php echo e($job->getJobShift('job_shift')); ?>

                                                            </label> - <span><?php echo e($job->getCity('city')); ?></span></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="listbtn">
                                                        <a href="<?php echo e(route('job.detail', [$job->slug])); ?>">
                                                            <?php echo e(__('View Details')); ?>

                                                        </a>
                                                    </div>

                                                    <div class="listbtn">
                                                        <?php if($job->hiredStatus==0): ?>
                                                            <button type="button" disabled="disabled"
                                                                    class="btn btn-danger btn-block">Pending
                                                            </button>
                                                        <?php else: ?>
                                                            <input type="hidden" class="form-control" id="feedback-id"
                                                                   value=""/>
                                                            <button type="button" class="btn btn-success btn-block">
                                                                Hired
                                                            </button>
                                                            <?php if($job->appliedUser->isCandidateContractStatus != "" && $job->appliedUser->isCandidateContractStatus == "open"): ?>
                                                                <?php if($job->appliedUser->isCandidateContractStatus == "close"): ?>
                                                                    <?php if($job->appliedUser['EmployerCloseContract'] >= Carbon\Carbon::now()->subDays(90)): ?>
                                                                        <button type="button" class="btn btn-danger btn-block" data-id="<?php echo e($job->id); ?>"
                                                                                data-toggle="modal" data-target="#closeContract--<?php echo e($job->id); ?>"
                                                                                onclick="addFeedback(<?php echo e($job->id); ?>)">
                                                                            Close Contract
                                                                        </button>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-danger btn-block" data-id="<?php echo e($job->id); ?>"
                                                                            data-toggle="modal" data-target="#closeContract--<?php echo e($job->id); ?>"
                                                                            onclick="addFeedback(<?php echo e($job->id); ?>)">
                                                                        Close Contract
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php elseif($job->appliedUser->isEmployeerContractStatus == "open"  && $job->appliedUser->isCandidateContractStatus == "close"): ?>

                                                                <?php if($job->appliedUser['CandidateCloseContract'] >= Carbon\Carbon::now()->subDays(90)): ?>
                                                                    <button type="button" class="btn btn-info btn-block"
                                                                            data-toggle="modal" data-target="#closeContract"
                                                                            onclick="editFeedback(<?php echo e($company->id); ?>,
                                                                            <?php echo e($job->appliedUser->job_id); ?>,
                                                                            <?php echo e($company->id); ?>)">
                                                                        Update Feedback
                                                                    </button>
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
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
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
                                                                                                <strong>Rating:</strong></label>
                                                                                            <div id="rateYo--<?php echo e($job->id); ?>"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-12">
                                                                                        <div class="form-group">
                                                                                            <label style="margin-bottom: 10px;">
                                                                                                Give Feedback:
                                                                                            </label>
                                                                                            <textarea class="form-control" id="feedback--<?php echo e($job->id); ?>"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">Close
                                                                            </button>
                                                                            <input type="hidden"
                                                                                   value="<?php echo e($company->email); ?>"
                                                                                   id="companyEmail--<?php echo e($job->id); ?>">
                                                                            <input type="hidden"
                                                                                   value="<?php echo e($company->name); ?>"
                                                                                   id="companyName--<?php echo e($job->id); ?>">
                                                                            <button type="button"
                                                                                    class="btn btn-primary"
                                                                                    id="btn-review"
                                                                                    onclick="saveReview(<?php echo e($company->id); ?>, <?php echo e($job->id); ?>,<?php echo e($company->id); ?>)">
                                                                                Give Review
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <p><?php echo str_limit(strip_tags($job->description), 150, '...'); ?></p>
                                        </li>
                                        <!-- job end -->
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </ul>
                        <?php echo e($jobs->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('includes.immediate_available_btn', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        var rating = 1;

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

            var companyEmail = $('#companyEmail--'+jobId).val();
            var companyName = $('#companyName--'+jobId).val();
            var feedbackId = $('#feedback-id').val();
            var feedback = $('#feedback--'+jobId).val();

            console.log("feedback",feedback);
            var type = 'candidate';
            if (feedbackId == "") {
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
                        'companyEmail': companyEmail,
                        'companyName': companyName,
                    },
                    success: function (response) {
                        location.reload();
                    },
                });
            } else {
                console.log("feedback123",feedback);
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
                        'companyEmail': companyEmail,
                        'companyName': companyName,
                    },
                    success: function (response) {
                        location.reload();
                    }
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
                    console.log(jobId,response.feedback.feedback,response.feedback.id);

                    $('#closeContract--'+jobId).modal("show");
                    $('#feedback--'+jobId).val(response.feedback.feedback);
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