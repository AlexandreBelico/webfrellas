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
                                <h3>Payment Details</h3>
                        </div>
                    </div>
                    <ul class="searchList">
                        <!-- job start --> 
                        <?php if($jobs && count($jobs) > 0): ?>
                        <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number"><?php echo e($job['title']); ?></a></h3>
                                    </div>
                                   
                                     <div class="jobdescription"><?php echo $job['description']; ?></div>
                                     
                                         <div class="row mt-5">
                                             <div class="col-lg-6">
                                                 <p><span style="font-weight: 600">Total Milestones : </span> <span><?php echo e($job['totalMilestones']); ?></span></p>
                                                 <p><span style="font-weight: 600">Completed Milestones : </span> <?php echo e($job['completedMilestones']); ?></p>
                                             </div>
                                             <div class="col-lg-6">
                                                 <p><span style="font-weight: 600">Total Amount : </span> <?php echo e($job['totalMilestonePrice']); ?>$</p>
                                                 <p><span style="font-weight: 600">Paid : </span> 00$</p>
                                             </div>
                                         </div>
                                     
                                </div>
                                <div class="col-md-4 col-sm-4 text-right">
                                
                                <div class="listbtn"></div>
                                <div class="listbtn">
                                    <a class="btn btn-info" href="<?php echo e(route('job.singlejobpaymentdetail', [$job['slug']])); ?>"><?php echo e(__('Payment details')); ?></a>
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