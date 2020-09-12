<?php $__env->startSection('content'); ?> 
<!-- Header start --> 
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Header end --> 
<!-- Inner Page Title start --> 
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Milestones list')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<!-- Inner Page Title end -->


<!-- Delete Milestone Confirmation Modal : START -->
<div class="modal fade" id="deleteMilestoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <?php echo Form::open(array('method' => 'post', 'route' => ['post.deletemilestone'])); ?> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
      </div>
      <div class="modal-body">
            <h6>Are you sure want to delete milestone ?</h6>
            <input type="hidden" name="deleteMilestoneId" class="deleteMilestoneId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-danger" value="Delete">
      </div>
    </div>
    <?php echo Form::close(); ?> 
  </div>
</div>
<!-- Delete Milestone Confirmation Modal : END -->
<!-- =========== Verify Work modal  ==========  -->
<div class="modal fade" id="verifyworkmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <?php echo Form::open(array('method' => 'post', 'route' => ['post.completemilestone'])); ?> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">Mark as complete the milestone</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <b class="boldtitle">Details : </b>
            <p class="display_submit_message"></p>
            <input type="hidden" name="completemilestoneId" class="completemilestoneId">
        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Approve">
      </div>
    </div>
    <?php echo Form::close(); ?> 
  </div>
</div>
<!-- =========== Verify Work modal  ==========  -->

<div class="listpgWraper">
    <div class="container"> <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
             <?php echo $__env->make('includes.company_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="col-md-8">
                <div class="userccount">
                    <div class="col-md-12 text-right">
                        <a href="<?php echo e(route('milestones.front.job', [$job->id])); ?>" class="btn btn-info btn-sm" title="Add Milestones">
                            <span class="fa fa-plus"></span> Add milestones
                        </a>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6"><h5><?php echo e($job->title); ?></h5></div>
                    </div>
                    <hr class="rowseparator">
                    <?php if(count($milestones)>0): ?>
                    <?php
                        $i = 1;
                    ?>
                    <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $startdate = date("d M, Y", strtotime($milestone->start_date));
                        $enddate = date("d M, Y", strtotime($milestone->end_date));
                    ?>
                    <div class="row">
                        <div class="col-md-9">
                            <h3><a class="milestonelisttitle" href="<?php echo e(route('job.milestone.edit', [$milestone->id])); ?>">Milestone : <?php echo e($milestone->milestone_title); ?></a></h3>
                        </div>
                        <div class="col-md-3 text-right">
                            <?php if($milestone->status==0): ?>
                                <span class="btn btn-info btn-sm">Open</span>
                            <?php endif; ?>
                            <?php if($milestone->status==1): ?>
                                <span class="btn btn-warning btn-sm">In progress</span>
                            <?php endif; ?>
                            <?php if($milestone->status==2): ?>
                                <span onclick="verifywork(<?php echo e($milestone->id); ?>)" class="btn btn-primary btn-sm">Submitted</span>
                            <?php endif; ?>
                             <?php if($milestone->status==3): ?>
                                <span class="btn btn-success btn-sm">Completed</span>
                            <?php endif; ?>
                            <?php if($milestone->status==4): ?>
                                <span class="btn btn-warning btn-sm">Paused</span>
                            <?php endif; ?>

                            <span class="btn btn-danger btn-sm" onclick="deleteMilestone(<?php echo e($milestone->id); ?>)">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <ul >
                                <li class="milestoneobjects">Start date : <?php echo e($startdate); ?> </li>
                                <li class="milestoneobjects">End date : <?php echo e($enddate); ?> </li>
                                <li class="milestoneobjects">Price : <?php echo e($milestone->price); ?> </li>
                            </ul>
                        </div>

                        <div class="col-md-8 no-gutters">
                            <p class="milestonedescription"> <?php echo e(str_limit($milestone->description, 400, '')); ?> 
                                <?php if(strlen($milestone->description) > 400): ?>
                                    <span id="dots_<?php echo e($milestone->id); ?>">...</span>
                                    <span id="moredescription_<?php echo e($milestone->id); ?>" class="moredescription"><?php echo e(substr($milestone->description, 400)); ?></span>
                                    <button class="btn-link btn-anchor" href="javascript:void(0)" onclick="readmore(<?php echo e($milestone->id); ?>)" id="readmorebtn_<?php echo e($milestone->id); ?>">Read more</button>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <hr class="rowseparator">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?> 
                        <p>No milestones found!!!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

 
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>