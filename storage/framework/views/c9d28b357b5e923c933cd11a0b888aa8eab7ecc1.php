<div class="section">
    <div class="container"> 
        <!-- title start -->
        <div class="titleTop">
            <div class="subtitle"><?php echo e(__('Here You Can See')); ?></div>
            <h3><?php echo e(__('Success')); ?> <span><?php echo e(__('Stories')); ?></span></h3>
        </div>
        <!-- title end -->

        <ul class="testimonialsList">
            <?php if(isset($testimonials) && count($testimonials)): ?>
            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="item">        
                <div class="clientname"><?php echo e($testimonial->testimonial_by); ?></div>
                <p>"<?php echo e($testimonial->testimonial); ?>"</p>
                <div class="clientinfo"><?php echo e($testimonial->company); ?></div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </ul>
    </div>
</div>