<?php $__env->startSection('content'); ?>
<!-- Header start -->
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Header end --> 
<!-- Inner Page Title start -->
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Timesheets')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Inner Page Title end -->

<div class="listpgWraper">
    <div class="container">  <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
            <?php echo $__env->make('includes.company_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <div class="row">
                        <div class="col-md-12">
                                <h3>Development Status Details</h3>
                        </div>
                    </div>
                    <ul class="searchList">
                        <?php if(isset($job_applications) && count($job_applications)): ?>
                        <?php $__currentLoopData = $job_applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number"><?php echo e($job->title); ?></a></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="jobdescription"><?php echo $job->description; ?></div>
                                </div>
                                <div class="col-md-4 col-sm-4 text-right">
                                
                                <div class="listbtn"><a href="<?php echo e(route('milestones.list', [$job->id])); ?>"><?php echo e(__('View Details')); ?></a></div>
                                <div class="listbtn">
                                    <button type="button" class="btn btn-info btn-block">
                                        <?php echo e($job->developmentstatus); ?>

                                    </button>
                                </div>

                                <div class="clearfix"></div>
                                </div>
                            </div>
                           
                        </li>
                        <!-- job end --> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <?php echo e($job_applications->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>