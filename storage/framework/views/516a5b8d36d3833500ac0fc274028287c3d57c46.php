<div class="section greybg">
    <div class="container"> 
        <!-- title start -->
        <div class="titleTop">            
            <h3><?php echo e(__('Top')); ?> <span><?php echo e(__('Employers')); ?></span></h3>
        </div>
        <!-- title end -->

        <ul class="employerList">
            <!--employer-->
            <?php if(isset($topCompanyIds) && count($topCompanyIds)): ?>
            <?php $__currentLoopData = $topCompanyIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company_id_num_jobs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $company = App\Company::where('id', '=', $company_id_num_jobs->company_id)->active()->first();
            if (null !== $company) {
                ?>
                <li class="item" data-toggle="tooltip" data-placement="bottom" title="<?php echo e($company->name); ?>" data-original-title="<?php echo e($company->name); ?>"><a href="<?php echo e(route('company.detail', $company->slug)); ?>" title="<?php echo e($company->name); ?>"><?php echo e($company->printCompanyImage()); ?></a></li>
                <?php
            }
            ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>

    </div>    
</div>


<div class="largebanner shadow3">
<div class="adin">
<?php echo $siteSetting->index_page_below_top_employes_ad; ?>

</div>
<div class="clearfix"></div>
</div>
