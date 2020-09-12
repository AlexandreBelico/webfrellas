<div class="section greybg">
    <div class="container"> 
        <!-- title start -->
        <div class="titleTop">
            <div class="subtitle"><?php echo e(__('Here You Can See')); ?></div>
            <h3><?php echo e(__('Featured')); ?> <span><?php echo e(__('Jobs')); ?></span></h3>
        </div>
        <!-- title end --> 

        <!--Featured Job start-->
        <ul class="jobslist row">
            <?php if(isset($featuredJobs) && count($featuredJobs)): ?>
            <?php $__currentLoopData = $featuredJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featuredJob): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $company = $featuredJob->getCompany(); ?>
            <?php if(null !== $company): ?>
            <!--Job start-->
            <li class="col-md-6">
                <div class="jobint">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                            <a href="<?php echo e(route('job.detail', [$featuredJob->slug])); ?>" title="<?php echo e($featuredJob->title); ?>">
                                <?php echo e($company->printCompanyImage()); ?>

                            </a>
                        </div>
                        <div class="col-md-7 col-sm-7">
                            <h4><a href="<?php echo e(route('job.detail', [$featuredJob->slug])); ?>" title="<?php echo e($featuredJob->title); ?>"><?php echo e($featuredJob->title); ?></a></h4>
                            <div class="company"><a href="<?php echo e(route('company.detail', $company->slug)); ?>" title="<?php echo e($company->name); ?>"><?php echo e($company->name); ?></a></div>
                            <div class="jobloc">
                                <label class="fulltime" title="<?php echo e($featuredJob->getJobType('job_type')); ?>"><?php echo e($featuredJob->getJobType('job_type')); ?></label> - <span><?php echo e($featuredJob->getCity('city')); ?></span></div>
                        </div>
                        <div class="col-md-3 col-sm-3"><a href="<?php echo e(route('job.detail', [$featuredJob->slug])); ?>" class="applybtn"><?php echo e(__('View Detail')); ?></a></div>
                    </div>
                </div>
            </li>
            <!--Job end--> 
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </ul>
        <!--Featured Job end--> 

        <!--button start-->
        <div class="viewallbtn"><a href="<?php echo e(route('job.list', ['is_featured'=>1])); ?>"><?php echo e(__('View All Featured Jobs')); ?></a></div>
        <!--button end--> 
    </div>
</div>