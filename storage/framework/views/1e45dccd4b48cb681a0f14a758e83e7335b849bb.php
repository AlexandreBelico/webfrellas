<?php $__env->startSection('content'); ?> 
<!-- Header start --> 
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Header end --> 
<!-- Inner Page Title start --> 
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Create Milestones')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container"> <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
             <?php echo $__env->make('includes.company_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="col-md-8">
                <div class="userccount">
                    <div class="formpanel"> <?php echo Form::open(array('method' => 'post', 'route' => ['post.milestone.job', $job])); ?> 
                        <!-- Job Information -->
                        <h5><?php echo e($job->title); ?></h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="formrow<?php echo e($errors->has('freelancer') ? ' has-error' : ''); ?>">
                                <select class="form-control" name="freelancer" id="freelancer">
                                    <option value="">Select User</option>
                                    <?php if(isset($job_applications) && count($job_applications)): ?>
                                    <?php $__currentLoopData = $job_applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job_application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $user = $job_application->getUser();
                                    ?>
                                    <?php if(null !== $user): ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->getName()); ?></option>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <?php if($errors->has('freelancer')): ?> <span class="help-block"> <strong><?php echo e($errors->first('freelancer')); ?></strong> </span> <?php endif; ?>
                            </div>
                            </div>
                            <input type="hidden" name="job_id" value="<?php echo e($job->id); ?>">
                            <div class="col-md-12">
                                <div class="formrow<?php echo e($errors->has('milestone_title') ? ' has-error' : ''); ?>">
                                    <input type="text" class="form-control" name="milestone_title" placeholder="Enter title of milestone">
                                     <?php if($errors->has('milestone_title')): ?> <span class="help-block"> <strong><?php echo e($errors->first('milestone_title')); ?></strong> </span> <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="formrow<?php echo e($errors->has('task_details') ? ' has-error' : ''); ?>">
                                    <textarea class="form-control" name="task_details" placeholder="Enter Details of task"></textarea>
                                     <?php if($errors->has('task_details')): ?> <span class="help-block"> <strong><?php echo e($errors->first('task_details')); ?></strong> </span> <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="formrow<?php echo e($errors->has('price') ? ' has-error' : ''); ?>"> 
                                    <input type="number" class="form-control" name="price" placeholder="Price">
                                    <?php if($errors->has('price')): ?> <span class="help-block"> <strong><?php echo e($errors->first('price')); ?></strong> </span> <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="formrowformrow<?php echo e($errors->has('start_date') ? ' has-error' : ''); ?>">
                                <div class='input-group date' id='startdate'>
                                    <input type='text' class="form-control" name="start_date" placeholder="Start date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <?php if($errors->has('start_date')): ?> <span class="help-block"> <strong><?php echo e($errors->first('start_date')); ?></strong> </span> <?php endif; ?>
                              </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="formrow<?php echo e($errors->has('end_date') ? ' has-error' : ''); ?>">
                                <div class='input-group date' id='enddate'>
                                    <input type='text' class="form-control" name="end_date" placeholder="End date" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <?php if($errors->has('end_date')): ?> <span class="help-block"> <strong><?php echo e($errors->first('end_date')); ?></strong> </span> <?php endif; ?>
                            </div>
                            </div>
                            
                        </div>
                        <br>
                        <input type="submit" class="btn" value="<?php echo e(__('Submit Milestone')); ?>">
                        <?php echo Form::close(); ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>