<?php $__env->startSection('content'); ?>
<!-- Header start -->
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Header end --> 
<!-- Inner Page Title start -->
<?php echo $__env->make('includes.inner_page_title', ['page_title'=>__('Timesheets')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Inner Page Title end -->

<!-- Modal -->
<div class="modal fade" id="changetimesheetstatusmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <?php echo Form::open(array('method' => 'post', 'route' => 'post.timeline.changestatus')); ?> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
      </div>
      <div class="modal-body">
         <h6>Are you sure want to change status ?</h6>
         <input type="hidden" name="timesheetid" class="timesheetid" id="timesheetid">
         <input type="hidden" name="changedstatusvalue" class="changedstatusvalue">
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" name="approve" value="Approve">
        <input type="submit" class="btn btn-danger" name="reject" value="Reject">
      </div>
    </div>
    <?php echo Form::close(); ?> 
  </div>
</div>

<div class="listpgWraper">
    <div class="container">  <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="row">
            <?php echo $__env->make('includes.company_dashboard_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="col-md-9 col-sm-8"> 
                <div class="myads">
                    <?php if(isset($timesheetDetails) && count($timesheetDetails)): ?>
                        <h3>Timesheet Details of <?php echo e($timesheetDetails[0]->title); ?></h3>
                    <?php else: ?>
                        <h3>No timesheet details found</h3>
                    <?php endif; ?>
                    <ul class="searchList">
                        <!-- job start --> 
                        <?php if(isset($timesheetDetails) && count($timesheetDetails)): ?>
                        <?php $__currentLoopData = $timesheetDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timesheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $whichdate = $timesheet->whichdate;
                            $whichdate = date_format(date_create($whichdate), 'd M, Y')
                        ?>
                        <li>
                            <div class="row">
                                <div class="col-md-10 col-sm-10">
                                    <div class="jobinfo">
                                        <h3><a href="javascript:void(0)" title="Milestone number">Milestone : <?php echo e($timesheet->milestone_title); ?></a></h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php if($timesheet->status==0): ?>
                                    <div class="col-md-2 col-sm-2 text-right" onclick="changeTimesheetStatus(1, <?php echo e($timesheet->id); ?>)">
                                        <span class="btn btn-warning btn-sm">Pending</span>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php elseif($timesheet->status==1): ?>
                                    <div class="col-md-2 col-sm-2 text-right">
                                        <span class="btn btn-success btn-sm">Approved</span>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php elseif($timesheet->status==2): ?>
                                    <div class="col-md-2 col-sm-2 text-right">
                                        <span class="btn btn-danger btn-sm">Rejected</span>
                                        <div class="clearfix"></div>
                                    </div>  
                                <?php endif; ?>  
                                
                            </div>
                            <p>Date : <?php echo e($whichdate); ?></p>
                            <p>Time spent : <?php echo e($timesheet->time_spent); ?></p>
                            <p><?php echo e($timesheet->description); ?></p>
                        </li>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <?php echo e($timesheetDetails->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
    function deleteJob(id) {
    var msg = 'Are you sure?';
    if (confirm(msg)) {
    $.post("<?php echo e(route('delete.front.job')); ?>", {id: id, _method: 'DELETE', _token: '<?php echo e(csrf_token()); ?>'})
            .done(function (response) {
            if (response == 'ok')
            {
            $('#job_li_' + id).remove();
            } else
            {
            alert('Request Failed!');
            }
            });
    }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>