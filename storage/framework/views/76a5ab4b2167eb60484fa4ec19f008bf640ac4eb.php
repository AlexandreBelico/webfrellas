<ul class="row profilestat">
    <li class="col-md-2 col-sm-4 col-xs-6">
        <div class="inbox"> <i class="fa fa-clock-o" aria-hidden="true"></i>
            <h6><a href="<?php echo e(route('posted.jobs')); ?>"><?php echo e(Auth::guard('company')->user()->countOpenJobs()); ?></a></h6>
            <strong><?php echo e(__('Open Jobs')); ?></strong> </div>
    </li>
    <li class="col-md-2 col-sm-4 col-xs-6">
        <div class="inbox"> <i class="fa fa-user-o" aria-hidden="true"></i>
            <h6><a href="<?php echo e(route('company.followers')); ?>"><?php echo e(Auth::guard('company')->user()->countFollowers()); ?></a></h6>
            <strong><?php echo e(__('Followers')); ?></strong> </div>
    </li>
    
</ul>