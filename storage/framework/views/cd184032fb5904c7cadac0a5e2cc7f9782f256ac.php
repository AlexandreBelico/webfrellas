<?php $__env->startSection('content'); ?>
<!-- Header start -->
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Header end --> 
<!-- Inner Page Title start -->
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Payment Details')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Inner Page Title end -->
<div class="listpgWraper"> 
    <div class="container"> <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
            <?php echo $__env->make('includes.user_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <div class="row">
                        <div class="col-md-12">
                                <h3><?php echo e($jobDetails[0]->title); ?> - <small>Milestones payment details</small    ></h3> 
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        <?php if($singlejobpayment && count($singlejobpayment) > 0): ?>
                        <?php $__currentLoopData = $singlejobpayment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number"><?php echo e($job->milestone_title); ?></a></h3>
                                    </div>
                                    <div class="jobdescription"><?php echo $job->description; ?></div>
                                     <div class="row mt-5">
                                        <div class="col-lg-4">
                                            <p><span style="font-weight: 600">Amount : </span> <?php echo e($job->price); ?>$</p>
                                        </div>

                                        <div class="col-lg-5">
                                                <p style="position: absolute;"><span style="font-weight: 600">Payment status : </span>       
                                                    <?php if($job->payment_status == 0): ?> 
                                                        <span class="badge badge-pill badge-success">pending</span>
                                                    <?php endif; ?>
                                                    <?php if($job->payment_status == 1): ?> 
                                                        <span class="badge badge-success" style="background: green;">Paid</span>
                                                    <?php endif; ?>
                                            </p>
                                        </div>
                                     </div>
                                </div>

                                <div class="col-md-4 col-sm-4 text-center">
                                <div class="listbtn">
                                    <b>Milestone status</b>
                                </div>
                                <div class="listbtn">
                                   <?php if($job->status==0): ?>
                                    <span class="btn btn-info btn-sm">Open</span>
                                    <?php endif; ?>
                                    <?php if($job->status==1): ?>
                                        <span class="btn btn-warning btn-sm">In progress</span>
                                    <?php endif; ?>
                                    <?php if($job->status==2): ?>
                                        <span class="btn btn-primary btn-sm">Submitted</span>
                                    <?php endif; ?>
                                     <?php if($job->status==3): ?>
                                        <span class="btn btn-success btn-sm">Completed</span>
                                    <?php endif; ?>
                                    <?php if($job->status==4): ?>
                                        <span class="btn btn-warning btn-sm">Paused</span>
                                    <?php endif; ?>
                                   <span class="btn btn-"></span>
                                </div>

                                <div class="clearfix"></div>
                                </div>
                            </div>
                           
                        </li>
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
<?php echo $__env->make('includes.immediate_available_btn', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>