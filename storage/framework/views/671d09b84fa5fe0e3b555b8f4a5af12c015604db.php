<div class="section greybg">
    <div class="container"> 
        <!-- title start -->
        <div class="titleTop">
            <div class="subtitle"><?php echo e(__('Here You Can See')); ?></div>
            <h3><?php echo e(__('Latest')); ?> <span><?php echo e(__('Jobs')); ?></span></h3>
        </div>
        <!-- title end -->

        <ul class="jobslist row">
            <?php if(isset($latestJobs) && count($latestJobs)): ?>
            <?php $__currentLoopData = $latestJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $latestJob): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $company = $latestJob->getCompany(); ?>
            <?php if(null !== $company): ?>
            <!--Job start-->
            <li class="col-md-4">
                <div class="jobint">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <a href="<?php echo e(route('job.detail', [$latestJob->slug])); ?>" title="<?php echo e($latestJob->title); ?>">
                                <?php echo e($company->printCompanyImage()); ?>

                            </a>
                        </div>
                        <div class="col-md-9 col-sm-9">
                            <h4><a href="<?php echo e(route('job.detail', [$latestJob->slug])); ?>" title="<?php echo e($latestJob->title); ?>"><?php echo e($latestJob->title); ?></a></h4>
                            <div class="company"><a href="<?php echo e(route('company.detail', $company->slug)); ?>" title="<?php echo e($company->name); ?>"><?php echo e($company->name); ?></a> - <span><?php echo e($latestJob->getCity('city')); ?></span></div>
                            <div class="jobloc">
                                <label class="fulltime" title="<?php echo e($latestJob->getJobType('job_type')); ?>"><?php echo e($latestJob->getJobType('job_type')); ?></label> </div>
                        </div>                       
                    </div>
                </div>
            </li>
            <!--Job end--> 
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>
        <!--view button-->
        <div class="viewallbtn"><a href="<?php echo e(route('job.list')); ?>"><?php echo e(__('View All Latest Jobs')); ?></a></div>
        <!--view button end--> 
    </div>
</div>